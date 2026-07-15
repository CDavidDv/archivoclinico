<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_abastecimiento', function (Blueprint $table) {
            $table->id();

            // Se asigna después de crear (SOL-000001)
            $table->string('folio', 20)->nullable()->unique();

            $table->enum('modulo_solicitante', ['farmacia', 'archivo']);

            $table->foreignId('id_usuario_solicita')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->foreignId('id_usuario_atiende')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete();

            $table->enum('estatus', [
                'pendiente',
                'aprobada',
                'surtida',
                'rechazada'
            ])->default('pendiente');

            $table->date('fecha_solicitud');
            $table->text('observaciones')->nullable();
            $table->string('motivo_rechazo')->nullable();

            $table->timestamps();

            $table->index('estatus');
        });

        Schema::create('detalle_solicitudes_abastecimiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_solicitud')
                ->constrained('solicitudes_abastecimiento')
                ->cascadeOnDelete();

            $table->foreignId('id_producto')
                ->constrained('productos')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad_solicitada');
            $table->unsignedInteger('cantidad_surtida')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_solicitudes_abastecimiento');
        Schema::dropIfExists('solicitudes_abastecimiento');
    }
};
