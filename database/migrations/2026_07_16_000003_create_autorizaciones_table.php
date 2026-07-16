<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bitácora de autorizaciones de excepción durante la dispensación:
     *  - exceso_cantidad       : se surtió más de lo prescrito.
     *  - presentacion_comercial: se entregaron cajas completas que exceden lo prescrito.
     *  - controlado_anticipado : surtido de un controlado antes del periodo permitido.
     */
    public function up(): void
    {
        Schema::create('autorizaciones', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', [
                'exceso_cantidad',
                'presentacion_comercial',
                'controlado_anticipado',
            ]);

            // Quien operaba la dispensación (solicitó la excepción).
            $table->foreignId('id_usuario_solicita')
                ->constrained('usuarios')
                ->restrictOnDelete();

            // El jefe/superior que aprobó con sus credenciales.
            $table->foreignId('id_usuario_autoriza')
                ->constrained('usuarios')
                ->restrictOnDelete();

            // Contexto de la dispensación.
            $table->foreignId('id_dispensacion')
                ->nullable()
                ->constrained('dispensaciones')
                ->nullOnDelete();

            $table->foreignId('id_detalle_receta')
                ->nullable()
                ->constrained('detalle_recetas')
                ->nullOnDelete();

            $table->foreignId('id_medicamento')
                ->nullable()
                ->constrained('medicamentos')
                ->nullOnDelete();

            $table->unsignedInteger('cantidad_prescrita')->nullable();
            $table->unsignedInteger('cantidad_autorizada')->nullable();

            $table->text('motivo');

            $table->timestamp('fecha_accion')->useCurrent();
            $table->timestamps();

            $table->index('tipo');
            $table->index('id_dispensacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones');
    }
};
