<?php

namespace App\Services;

use App\Models\Autorizacion;
use App\Models\DetalleDispensacion;
use App\Models\Dispensacion;
use App\Models\LoteFarmacia;
use App\Models\Receta;
use App\Models\Usuario;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DispensacionService
{
    /**
     * Dispensa una receta descontando stock FEFO de lotes de farmacia.
     *
     * @param array        $cantidades    [id_detalle_receta => cantidad a surtir]
     * @param Usuario|null $autorizador   Jefe de área que autoriza excepciones (validado previamente).
     * @param string|null  $motivo        Motivo de la autorización.
     */
    public function dispensar(
        Receta $receta,
        array $cantidades,
        Usuario $usuario,
        ?string $observaciones = null,
        ?Usuario $autorizador = null,
        ?string $motivo = null
    ): Dispensacion {
        return DB::transaction(function () use ($receta, $cantidades, $usuario, $observaciones, $autorizador, $motivo) {

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

                $medicamento = $detalle->medicamento;
                $pendiente   = $detalle->cantidadPendiente();

                // Excepciones aplicables a este renglón.
                $excepciones = $this->excepcionesRequeridas($detalle, $medicamento, $cantidad, $pendiente, $receta);

                if (!empty($excepciones) && !$autorizador) {
                    throw ValidationException::withMessages([
                        'autorizacion_requerida' => $this->mensajeAutorizacion($excepciones, $medicamento->nombre, $cantidad, $pendiente),
                    ]);
                }

                $partidas = $this->descontarFefo($detalle->id_medicamento, $cantidad, $medicamento->nombre);

                foreach ($partidas as $partida) {
                    DetalleDispensacion::create([
                        'id_dispensacion'   => $dispensacion->id,
                        'id_detalle_receta' => $detalle->id,
                        'id_lote_farmacia'  => $partida['id_lote_farmacia'],
                        'cantidad'          => $partida['cantidad'],
                    ]);
                }

                // Registra en bitácora cada excepción autorizada.
                foreach ($excepciones as $tipo) {
                    Autorizacion::create([
                        'tipo'                => $tipo,
                        'id_usuario_solicita' => $usuario->id,
                        'id_usuario_autoriza' => $autorizador->id,
                        'id_dispensacion'     => $dispensacion->id,
                        'id_detalle_receta'   => $detalle->id,
                        'id_medicamento'      => $detalle->id_medicamento,
                        'cantidad_prescrita'  => $detalle->cantidad_prescrita,
                        'cantidad_autorizada' => $cantidad,
                        'motivo'              => $motivo,
                        'fecha_accion'        => now(),
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
     * Determina qué excepciones (que requieren autorización) aplican al surtir
     * $cantidad de un renglón de receta.
     *
     * @return string[] tipos de Autorizacion
     */
    private function excepcionesRequeridas($detalle, $medicamento, int $cantidad, int $pendiente, Receta $receta): array
    {
        $tipos = [];

        if ($cantidad > $pendiente) {
            // ¿El exceso se explica exactamente por entregar cajas completas?
            $porCajas = $medicamento->entregaPorCajas()
                && $cantidad === $medicamento->piezasEnCajas($pendiente);

            $tipos[] = $porCajas
                ? Autorizacion::TIPO_PRESENTACION_COMERCIAL
                : Autorizacion::TIPO_EXCESO_CANTIDAD;
        }

        if ($medicamento->controlado && $this->surtidoAnticipado($detalle->id_medicamento, $receta->id_derecho_habiente, $medicamento->dias_restriccion)) {
            $tipos[] = Autorizacion::TIPO_CONTROLADO_ANTICIPADO;
        }

        return $tipos;
    }

    private function mensajeAutorizacion(array $excepciones, string $nombre, int $cantidad, int $pendiente): string
    {
        if (in_array(Autorizacion::TIPO_CONTROLADO_ANTICIPADO, $excepciones, true)) {
            return sprintf(
                'Se requiere autorización del jefe de área: %s es un medicamento controlado y no ha transcurrido el periodo mínimo entre surtidos.',
                $nombre
            );
        }

        if (in_array(Autorizacion::TIPO_PRESENTACION_COMERCIAL, $excepciones, true)) {
            return sprintf(
                'Se requiere autorización del jefe de área: la presentación comercial de %s obliga a entregar %d piezas (prescrito/pendiente: %d).',
                $nombre,
                $cantidad,
                $pendiente
            );
        }

        return sprintf(
            'Se requiere autorización del jefe de área: la cantidad a surtir de %s (%d) excede lo pendiente (%d).',
            $nombre,
            $cantidad,
            $pendiente
        );
    }

    /**
     * ¿Se surtió este medicamento controlado al derechohabiente dentro del
     * periodo de restricción?
     */
    private function surtidoAnticipado(int $idMedicamento, int $idDerechoHabiente, int $diasRestriccion): bool
    {
        $ultima = $this->ultimoSurtido($idMedicamento, $idDerechoHabiente);

        if (!$ultima) {
            return false;
        }

        return $ultima->copy()->addDays($diasRestriccion)->isFuture();
    }

    /** Fecha del último surtido de un medicamento a un derechohabiente. */
    public function ultimoSurtido(int $idMedicamento, int $idDerechoHabiente): ?Carbon
    {
        $ultima = DB::table('detalle_dispensaciones as dd')
            ->join('dispensaciones as di', 'di.id', '=', 'dd.id_dispensacion')
            ->join('detalle_recetas as dr', 'dr.id', '=', 'dd.id_detalle_receta')
            ->join('recetas as r', 'r.id', '=', 'dr.id_receta')
            ->where('dr.id_medicamento', $idMedicamento)
            ->where('r.id_derecho_habiente', $idDerechoHabiente)
            ->max('di.fecha');

        return $ultima ? Carbon::parse($ultima) : null;
    }

    /**
     * Estado de restricción de un medicamento controlado para un derechohabiente,
     * usado por la pantalla de dispensación.
     */
    public function estadoControlado($medicamento, int $idDerechoHabiente): array
    {
        if (!$medicamento->controlado) {
            return ['controlado' => false, 'bloqueado' => false, 'ultima_fecha' => null, 'disponible_desde' => null, 'dias_restriccion' => 0];
        }

        $dias   = (int) $medicamento->dias_restriccion;
        $ultima = $this->ultimoSurtido($medicamento->id, $idDerechoHabiente);
        $desde  = $ultima ? $ultima->copy()->addDays($dias) : null;

        return [
            'controlado'       => true,
            'bloqueado'        => $desde ? $desde->isFuture() : false,
            'ultima_fecha'     => $ultima?->toDateString(),
            'disponible_desde' => $desde?->toDateString(),
            'dias_restriccion' => $dias,
        ];
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
