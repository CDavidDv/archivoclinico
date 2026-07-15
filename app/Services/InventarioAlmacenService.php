<?php

namespace App\Services;

use App\Models\DetalleEntradaAlmacen;
use App\Models\DetalleSalidaAlmacen;
use App\Models\EntradaAlmacen;
use App\Models\LoteAlmacen;
use App\Models\Producto;
use App\Models\SalidaAlmacen;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventarioAlmacenService
{
    /**
     * Registra una entrada de almacén con sus renglones.
     *
     * @param array $datos     [id_proveedor?, tipo, fecha, observaciones?]
     * @param array $renglones [ [id_producto, numero_lote, caducidad?, cantidad, precio_unitario?], ... ]
     */
    public function registrarEntrada(array $datos, array $renglones, Usuario $usuario): EntradaAlmacen
    {
        return DB::transaction(function () use ($datos, $renglones, $usuario) {

            $entrada = EntradaAlmacen::create([
                'id_proveedor'  => $datos['id_proveedor'] ?? null,
                'id_usuario'    => $usuario->id,
                'tipo'          => $datos['tipo'] ?? EntradaAlmacen::TIPO_COMPRA,
                'fecha'         => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            foreach ($renglones as $renglon) {
                $lote = LoteAlmacen::firstOrCreate(
                    [
                        'id_producto' => $renglon['id_producto'],
                        'numero_lote' => $renglon['numero_lote'],
                    ],
                    [
                        'caducidad' => $renglon['caducidad'] ?? null,
                    ]
                );

                $lote->increment('cantidad_actual', $renglon['cantidad']);

                DetalleEntradaAlmacen::create([
                    'id_entrada_almacen' => $entrada->id,
                    'id_producto'        => $renglon['id_producto'],
                    'id_lote_almacen'    => $lote->id,
                    'cantidad'           => $renglon['cantidad'],
                    'precio_unitario'    => $renglon['precio_unitario'] ?? null,
                ]);
            }

            return $entrada;
        });
    }

    /**
     * Registra una salida de almacén descontando stock FEFO.
     *
     * @param array $datos     [tipo, area_destino?, fecha, observaciones?]
     * @param array $renglones [ [id_producto, cantidad], ... ]
     */
    public function registrarSalida(array $datos, array $renglones, Usuario $usuario): SalidaAlmacen
    {
        return DB::transaction(function () use ($datos, $renglones, $usuario) {

            $salida = SalidaAlmacen::create([
                'id_usuario'    => $usuario->id,
                'tipo'          => $datos['tipo'] ?? SalidaAlmacen::TIPO_USO_INTERNO,
                'area_destino'  => $datos['area_destino'] ?? null,
                'fecha'         => $datos['fecha'],
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            foreach ($renglones as $renglon) {
                $partidas = $this->descontarFefo(
                    (int) $renglon['id_producto'],
                    (int) $renglon['cantidad']
                );

                foreach ($partidas as $partida) {
                    DetalleSalidaAlmacen::create([
                        'id_salida_almacen' => $salida->id,
                        'id_producto'       => $renglon['id_producto'],
                        'id_lote_almacen'   => $partida['id_lote_almacen'],
                        'cantidad'          => $partida['cantidad'],
                    ]);
                }
            }

            return $salida;
        });
    }

    /**
     * Descuenta stock de un producto lote por lote (FEFO).
     * Debe llamarse dentro de una transacción.
     *
     * @return array [ [id_lote_almacen, cantidad, lote], ... ]
     * @throws ValidationException si el stock disponible es insuficiente
     */
    public function descontarFefo(int $idProducto, int $cantidad): array
    {
        $lotes = LoteAlmacen::where('id_producto', $idProducto)
            ->disponiblesFefo()
            ->lockForUpdate()
            ->get();

        $disponible = $lotes->sum('cantidad_actual');

        if ($disponible < $cantidad) {
            $producto = Producto::find($idProducto);

            throw ValidationException::withMessages([
                'detalles' => sprintf(
                    'Stock insuficiente de %s: disponible %d, solicitado %d.',
                    $producto?->clave ?? "producto #{$idProducto}",
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
                'id_lote_almacen' => $lote->id,
                'cantidad'        => $tomar,
                'lote'            => $lote,
            ];

            $pendiente -= $tomar;
        }

        return $partidas;
    }
}
