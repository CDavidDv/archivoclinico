<?php

namespace App\Services;

use App\Models\DetalleDispensacion;
use App\Models\Dispensacion;
use App\Models\LoteFarmacia;
use App\Models\Receta;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DispensacionService
{
    /**
     * Dispensa una receta descontando stock FEFO de lotes de farmacia.
     *
     * @param array $cantidades [id_detalle_receta => cantidad a surtir]
     */
    public function dispensar(Receta $receta, array $cantidades, Usuario $usuario, ?string $observaciones = null): Dispensacion
    {
        return DB::transaction(function () use ($receta, $cantidades, $usuario, $observaciones) {

            if (!$receta->puedeDispensarse()) {
                throw ValidationException::withMessages([
                    'receta' => $receta->isCancelada()
                        ? 'La receta está cancelada.'
                        : 'La receta ya fue surtida.',
                ]);
            }

            $detalles = $receta->detalles()
                ->with('medicamento')
                ->get()
                ->keyBy('id');

            $dispensacion = Dispensacion::create([
                'id_receta'     => $receta->id,
                'id_usuario'    => $usuario->id,
                'fecha'         => now(),
                'observaciones' => $observaciones,
            ]);

            $algoDispensado = false;

            foreach ($cantidades as $idDetalle => $cantidad) {
                $cantidad = (int) $cantidad;

                if ($cantidad <= 0) {
                    continue;
                }

                $detalle = $detalles->get($idDetalle);

                if (!$detalle) {
                    throw ValidationException::withMessages([
                        'detalles' => 'Renglón de receta inválido.',
                    ]);
                }

                if ($cantidad > $detalle->cantidadPendiente()) {
                    throw ValidationException::withMessages([
                        'detalles' => sprintf(
                            'La cantidad a surtir de %s (%d) excede lo pendiente (%d).',
                            $detalle->medicamento->nombre,
                            $cantidad,
                            $detalle->cantidadPendiente()
                        ),
                    ]);
                }

                $partidas = $this->descontarFefo($detalle->id_medicamento, $cantidad, $detalle->medicamento->nombre);

                foreach ($partidas as $partida) {
                    DetalleDispensacion::create([
                        'id_dispensacion'   => $dispensacion->id,
                        'id_detalle_receta' => $detalle->id,
                        'id_lote_farmacia'  => $partida['id_lote_farmacia'],
                        'cantidad'          => $partida['cantidad'],
                    ]);
                }

                $detalle->increment('cantidad_surtida', $cantidad);
                $algoDispensado = true;
            }

            if (!$algoDispensado) {
                throw ValidationException::withMessages([
                    'detalles' => 'Indica al menos una cantidad a surtir.',
                ]);
            }

            $receta->actualizarEstatus();

            return $dispensacion;
        });
    }

    /**
     * Descuenta stock de un medicamento lote por lote (FEFO).
     * Los lotes caducados quedan excluidos por el scope.
     * Debe llamarse dentro de una transacción.
     *
     * @return array [ [id_lote_farmacia, cantidad], ... ]
     * @throws ValidationException si el stock disponible es insuficiente
     */
    private function descontarFefo(int $idMedicamento, int $cantidad, string $nombre): array
    {
        $lotes = LoteFarmacia::where('id_medicamento', $idMedicamento)
            ->disponiblesFefo()
            ->lockForUpdate()
            ->get();

        $disponible = $lotes->sum('cantidad_actual');

        if ($disponible < $cantidad) {
            throw ValidationException::withMessages([
                'detalles' => sprintf(
                    'Stock insuficiente de %s: disponible %d, solicitado %d.',
                    $nombre,
                    $disponible,
                    $cantidad
                ),
            ]);
        }

        $partidas  = [];
        $pendiente = $cantidad;

        foreach ($lotes as $lote) {
            if ($pendiente <= 0) {
                break;
            }

            $tomar = min($lote->cantidad_actual, $pendiente);

            $lote->decrement('cantidad_actual', $tomar);

            $partidas[] = [
                'id_lote_farmacia' => $lote->id,
                'cantidad'         => $tomar,
            ];

            $pendiente -= $tomar;
        }

        return $partidas;
    }
}
