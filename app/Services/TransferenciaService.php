<?php

namespace App\Services;

use App\Models\DetalleTransferencia;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use App\Models\Transferencia;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransferenciaService
{
    public function __construct(
        private readonly InventarioAlmacenService $inventarioAlmacen,
        private readonly InventarioFarmaciaService $inventarioFarmacia,
    ) {}

    /**
     * Transfiere productos del almacén a farmacia o a otra área.
     * Todo ocurre en una sola transacción: si un renglón falla,
     * no se descuenta nada.
     *
     * @param array $datos     [destino, area_destino?, id_solicitud?, fecha, observaciones?]
     * @param array $renglones [ [id_producto, cantidad], ... ]
     */
    public function transferir(array $datos, array $renglones, Usuario $usuario): Transferencia
    {
        return DB::transaction(function () use ($datos, $renglones, $usuario) {

            $destino = $datos['destino'];

            $solicitud = null;

            if (!empty($datos['id_solicitud'])) {
                $solicitud = SolicitudAbastecimiento::whereKey($datos['id_solicitud'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$solicitud->puedeSurtirse()) {
                    throw ValidationException::withMessages([
                        'id_solicitud' => 'La solicitud no está aprobada; no puede surtirse.',
                    ]);
                }
            }

            // A farmacia solo pueden ir productos vinculados a un medicamento
            if ($destino === Transferencia::DESTINO_FARMACIA) {
                $this->validarVinculosFarmacia($renglones);
            }

            $transferencia = Transferencia::create([
                'destino'       => $destino,
                'area_destino'  => $destino === Transferencia::DESTINO_AREA
                    ? $datos['area_destino']
                    : null,
                'id_solicitud'  => $solicitud?->id,
                'id_usuario'    => $usuario->id,
                'fecha'         => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            $renglonesFarmacia = [];

            foreach ($renglones as $renglon) {
                $idProducto = (int) $renglon['id_producto'];
                $cantidad   = (int) $renglon['cantidad'];

                $partidas = $this->inventarioAlmacen->descontarFefo($idProducto, $cantidad);

                foreach ($partidas as $partida) {
                    DetalleTransferencia::create([
                        'id_transferencia' => $transferencia->id,
                        'id_producto'      => $idProducto,
                        'id_lote_almacen'  => $partida['id_lote_almacen'],
                        'cantidad'         => $partida['cantidad'],
                    ]);

                    if ($destino === Transferencia::DESTINO_FARMACIA) {
                        $loteAlmacen = $partida['lote'];

                        $renglonesFarmacia[] = [
                            'id_medicamento' => Producto::find($idProducto)->medicamento->id,
                            'numero_lote'    => $loteAlmacen->numero_lote,
                            'caducidad'      => $loteAlmacen->caducidad->toDateString(),
                            'cantidad'       => $partida['cantidad'],
                        ];
                    }
                }
            }

            if ($renglonesFarmacia !== []) {
                $this->inventarioFarmacia->registrarEntradaPorTransferencia(
                    $transferencia->fresh(),
                    $renglonesFarmacia,
                    $usuario
                );
            }

            if ($solicitud) {
                $this->marcarSolicitudSurtida($solicitud, $renglones, $usuario);
            }

            return $transferencia;
        });
    }

    /**
     * Valida que todo producto destinado a farmacia tenga medicamento
     * vinculado y que sus lotes por transferir tengan caducidad
     * (lotes_farmacia la exige).
     */
    private function validarVinculosFarmacia(array $renglones): void
    {
        foreach ($renglones as $renglon) {
            $producto = Producto::with('medicamento')
                ->find($renglon['id_producto']);

            if (!$producto?->medicamento) {
                throw ValidationException::withMessages([
                    'detalles' => sprintf(
                        'El producto %s no tiene medicamento vinculado en farmacia; no puede transferirse a farmacia.',
                        $producto?->clave ?? '#' . $renglon['id_producto']
                    ),
                ]);
            }

            $sinCaducidad = $producto->lotes()
                ->disponiblesFefo()
                ->whereNull('caducidad')
                ->exists();

            if ($sinCaducidad) {
                throw ValidationException::withMessages([
                    'detalles' => sprintf(
                        'El producto %s tiene lotes sin caducidad; farmacia requiere lotes con caducidad.',
                        $producto->clave
                    ),
                ]);
            }
        }
    }

    private function marcarSolicitudSurtida(
        SolicitudAbastecimiento $solicitud,
        array $renglones,
        Usuario $usuario
    ): void {
        $detalles = $solicitud->detalles()->get()->keyBy('id_producto');

        foreach ($renglones as $renglon) {
            $detalle = $detalles->get((int) $renglon['id_producto']);

            $detalle?->increment('cantidad_surtida', (int) $renglon['cantidad']);
        }

        $solicitud->update([
            'estatus'            => SolicitudAbastecimiento::ESTATUS_SURTIDA,
            'id_usuario_atiende' => $usuario->id,
        ]);
    }
}
