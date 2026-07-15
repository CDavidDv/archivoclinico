<?php

namespace App\Services;

use App\Models\DetalleEntradaFarmacia;
use App\Models\DetalleSalidaFarmacia;
use App\Models\EntradaFarmacia;
use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use App\Models\SalidaFarmacia;
use App\Models\Transferencia;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventarioFarmaciaService
{
    /**
     * Entrada manual (ajuste) a farmacia.
     *
     * @param array $renglones [ [id_medicamento, numero_lote, caducidad, cantidad], ... ]
     */
    public function registrarEntrada(array $datos, array $renglones, Usuario $usuario): EntradaFarmacia
    {
        return DB::transaction(function () use ($datos, $renglones, $usuario) {

            $entrada = EntradaFarmacia::create([
                'tipo'          => EntradaFarmacia::TIPO_AJUSTE,
                'id_usuario'    => $usuario->id,
                'fecha'         => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            foreach ($renglones as $renglon) {
                $this->ingresarLote(
                    $entrada,
                    (int) $renglon['id_medicamento'],
                    $renglon['numero_lote'],
                    $renglon['caducidad'],
                    (int) $renglon['cantidad']
                );
            }

            return $entrada;
        });
    }

    /**
     * Entrada a farmacia generada por una transferencia de almacén.
     * Debe llamarse dentro de la transacción de la transferencia.
     *
     * @param array $renglones [ [id_medicamento, numero_lote, caducidad, cantidad], ... ]
     */
    public function registrarEntradaPorTransferencia(
        Transferencia $transferencia,
        array $renglones,
        Usuario $usuario
    ): EntradaFarmacia {
        $entrada = EntradaFarmacia::create([
            'tipo'             => EntradaFarmacia::TIPO_TRANSFERENCIA,
            'id_transferencia' => $transferencia->id,
            'id_usuario'       => $usuario->id,
            'fecha'            => $transferencia->fecha,
            'observaciones'    => 'Transferencia ' . $transferencia->folio,
        ]);

        foreach ($renglones as $renglon) {
            $this->ingresarLote(
                $entrada,
                (int) $renglon['id_medicamento'],
                $renglon['numero_lote'],
                $renglon['caducidad'],
                (int) $renglon['cantidad']
            );
        }

        return $entrada;
    }

    /**
     * Salida de farmacia (merma, caducidad, ajuste) descontando lotes específicos.
     *
     * @param array $renglones [ [id_lote_farmacia, cantidad], ... ]
     */
    public function registrarSalida(array $datos, array $renglones, Usuario $usuario): SalidaFarmacia
    {
        return DB::transaction(function () use ($datos, $renglones, $usuario) {

            $salida = SalidaFarmacia::create([
                'tipo'          => $datos['tipo'],
                'id_usuario'    => $usuario->id,
                'fecha'         => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            foreach ($renglones as $renglon) {
                $lote = LoteFarmacia::whereKey($renglon['id_lote_farmacia'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $cantidad = (int) $renglon['cantidad'];

                if ($lote->cantidad_actual < $cantidad) {
                    throw ValidationException::withMessages([
                        'detalles' => sprintf(
                            'El lote %s solo tiene %d unidades (solicitado %d).',
                            $lote->numero_lote,
                            $lote->cantidad_actual,
                            $cantidad
                        ),
                    ]);
                }

                $lote->decrement('cantidad_actual', $cantidad);

                DetalleSalidaFarmacia::create([
                    'id_salida_farmacia' => $salida->id,
                    'id_medicamento'     => $lote->id_medicamento,
                    'id_lote_farmacia'   => $lote->id,
                    'cantidad'           => $cantidad,
                ]);
            }

            return $salida;
        });
    }

    /**
     * Crea o incrementa un lote de farmacia y registra el detalle de entrada.
     */
    private function ingresarLote(
        EntradaFarmacia $entrada,
        int $idMedicamento,
        string $numeroLote,
        string $caducidad,
        int $cantidad
    ): void {
        $lote = LoteFarmacia::firstOrCreate(
            [
                'id_medicamento' => $idMedicamento,
                'numero_lote'    => $numeroLote,
            ],
            [
                'caducidad' => $caducidad,
            ]
        );

        $lote->increment('cantidad_actual', $cantidad);

        DetalleEntradaFarmacia::create([
            'id_entrada_farmacia' => $entrada->id,
            'id_medicamento'      => $idMedicamento,
            'id_lote_farmacia'    => $lote->id,
            'cantidad'            => $cantidad,
        ]);
    }
}
