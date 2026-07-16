<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columnas para:
     *  - Presentación comercial (surtido por cajas completas).
     *  - Medicamentos controlados (restricción de surtido anticipado).
     */
    public function up(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            // Piezas que contiene cada presentación comercial (caja/frasco).
            // Cuando es > 1 el medicamento sólo se entrega en múltiplos de este valor.
            $table->unsignedInteger('piezas_por_presentacion')
                ->default(1)
                ->after('presentacion');

            // Medicamento controlado: sujeto a restricción de surtido anticipado.
            $table->boolean('controlado')
                ->default(false)
                ->after('stock_minimo');

            // Días mínimos entre surtidos para un medicamento controlado.
            $table->unsignedInteger('dias_restriccion')
                ->default(28)
                ->after('controlado');
        });
    }

    public function down(): void
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->dropColumn(['piezas_por_presentacion', 'controlado', 'dias_restriccion']);
        });
    }
};
