<?php

namespace Database\Seeders;

use App\Models\DerechoHabiente;
use App\Models\DetalleReceta;
use App\Models\DetalleSolicitudAbastecimiento;
use App\Models\EntradaAlmacen;
use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Receta;
use App\Models\SolicitudAbastecimiento;
use App\Models\Usuario;
use App\Services\InventarioAlmacenService;
use Illuminate\Database\Seeder;

class InventarioDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (EntradaAlmacen::exists()) {
            return; // Demo ya sembrado.
        }

        $this->crearEntradaAlmacen();
        $this->crearLotesFarmacia();
        $this->crearRecetaPendiente();
        $this->crearSolicitudPendiente();
    }

    /**
     * Entrada de compra que da existencias iniciales al almacén
     * (incluye un lote por caducar para las alertas).
     */
    private function crearEntradaAlmacen(): void
    {
        $almacenista = Usuario::where('nombre_usuario', 'almacen1')->first();
        $proveedor   = Proveedor::first();

        $producto = fn (string $clave) => Producto::where('clave', $clave)->firstOrFail()->id;

        app(InventarioAlmacenService::class)->registrarEntrada(
            [
                'id_proveedor'  => $proveedor?->id,
                'tipo'          => EntradaAlmacen::TIPO_COMPRA,
                'fecha'         => now()->toDateString(),
                'observaciones' => 'Compra inicial de demostración',
            ],
            [
                ['id_producto' => $producto('PROD-001'), 'numero_lote' => 'LA-00001', 'caducidad' => null, 'cantidad' => 200, 'precio_unitario' => 85.00],
                ['id_producto' => $producto('PROD-001'), 'numero_lote' => 'LA-00002', 'caducidad' => null, 'cantidad' => 100, 'precio_unitario' => 85.00],
                ['id_producto' => $producto('PROD-002'), 'numero_lote' => 'LA-00003', 'caducidad' => now()->addYears(2)->toDateString(), 'cantidad' => 25, 'precio_unitario' => 3.50],
                ['id_producto' => $producto('PROD-003'), 'numero_lote' => 'LA-00004', 'caducidad' => now()->addDays(20)->toDateString(), 'cantidad' => 40, 'precio_unitario' => 12.00],
                ['id_producto' => $producto('PROD-004'), 'numero_lote' => 'PAR-2401', 'caducidad' => now()->addMonths(18)->toDateString(), 'cantidad' => 60, 'precio_unitario' => 22.00],
                ['id_producto' => $producto('PROD-010'), 'numero_lote' => 'LA-00005', 'caducidad' => now()->addMonths(6)->toDateString(), 'cantidad' => 10, 'precio_unitario' => 45.00],
            ],
            $almacenista
        );
    }

    /**
     * Lotes de farmacia con caducidades mixtas para las alertas:
     * sano, por caducar (<30 días), caducado, stock bajo y sin stock.
     */
    private function crearLotesFarmacia(): void
    {
        $lotes = [
            // Paracetamol - stock saludable, dos lotes (para ver FEFO)
            ['clave' => 'MED-001', 'lote' => 'LF-00001', 'caducidad' => now()->addMonths(18), 'cantidad' => 100],
            ['clave' => 'MED-001', 'lote' => 'LF-00002', 'caducidad' => now()->addMonths(6), 'cantidad' => 50],

            // Amoxicilina - stock bajo (mínimo 15)
            ['clave' => 'MED-002', 'lote' => 'LF-00003', 'caducidad' => now()->addMonths(12), 'cantidad' => 8],

            // Ibuprofeno - por caducar (≤30 días)
            ['clave' => 'MED-003', 'lote' => 'LF-00004', 'caducidad' => now()->addDays(15), 'cantidad' => 30],

            // Omeprazol - caducado
            ['clave' => 'MED-004', 'lote' => 'LF-00005', 'caducidad' => now()->subDays(30), 'cantidad' => 20],

            // Losartán - en el mínimo exacto
            ['clave' => 'MED-005', 'lote' => 'LF-00006', 'caducidad' => now()->addMonths(9), 'cantidad' => 10],

            // Metformina - sin stock
            ['clave' => 'MED-006', 'lote' => 'LF-00007', 'caducidad' => now()->addMonths(3), 'cantidad' => 0],
        ];

        foreach ($lotes as $datos) {
            $medicamento = Medicamento::where('clave', $datos['clave'])->firstOrFail();

            LoteFarmacia::firstOrCreate(
                [
                    'id_medicamento' => $medicamento->id,
                    'numero_lote'    => $datos['lote'],
                ],
                [
                    'caducidad'       => $datos['caducidad']->toDateString(),
                    'cantidad_actual' => $datos['cantidad'],
                ]
            );
        }
    }

    /**
     * Receta pendiente en la cola de farmacia.
     */
    private function crearRecetaPendiente(): void
    {
        $receta = Receta::create([
            'id_derecho_habiente' => DerechoHabiente::firstOrFail()->id,
            'id_medico'           => Medico::firstOrFail()->id,
            'id_usuario'          => Usuario::where('nombre_usuario', 'medico1')->firstOrFail()->id,
            'fecha_receta'        => now()->toDateString(),
            'diagnostico'         => 'Faringitis aguda',
            'indicaciones'        => 'Reposo e hidratación',
            'estatus'             => Receta::ESTATUS_PENDIENTE,
        ]);

        DetalleReceta::create([
            'id_receta'          => $receta->id,
            'id_medicamento'     => Medicamento::where('clave', 'MED-001')->firstOrFail()->id,
            'cantidad_prescrita' => 2,
            'cantidad_surtida'   => 0,
            'dosis'              => '1 tableta cada 8 horas por 5 días',
        ]);

        DetalleReceta::create([
            'id_receta'          => $receta->id,
            'id_medicamento'     => Medicamento::where('clave', 'MED-002')->firstOrFail()->id,
            'cantidad_prescrita' => 1,
            'cantidad_surtida'   => 0,
            'dosis'              => '1 cápsula cada 12 horas por 7 días',
        ]);
    }

    /**
     * Solicitud de abastecimiento pendiente de aprobación.
     */
    private function crearSolicitudPendiente(): void
    {
        $solicitud = SolicitudAbastecimiento::create([
            'modulo_solicitante'  => SolicitudAbastecimiento::MODULO_FARMACIA,
            'id_usuario_solicita' => Usuario::where('nombre_usuario', 'farmacia1')->firstOrFail()->id,
            'estatus'             => SolicitudAbastecimiento::ESTATUS_PENDIENTE,
            'fecha_solicitud'     => now()->toDateString(),
            'observaciones'       => 'Reabastecimiento de paracetamol para farmacia',
        ]);

        DetalleSolicitudAbastecimiento::create([
            'id_solicitud'        => $solicitud->id,
            'id_producto'         => Producto::where('clave', 'PROD-004')->firstOrFail()->id,
            'cantidad_solicitada' => 20,
            'cantidad_surtida'    => 0,
        ]);
    }
}
