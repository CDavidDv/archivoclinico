<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->id();

            // Se asigna después de crear (TRF-000001)
            $table->string('folio', 20)->nullable()->unique();

            $table->enum('destino', ['farmacia', 'area']);
            $table->string('area_destino', 150)->nullable();

            $table->foreignId('id_solicitud')
                ->nullable()
                ->constrained('solicitudes_abastecimiento')
                ->nullOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->date('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('detalle_transferencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_transferencia')
                ->constrained('transferencias')
                ->cascadeOnDelete();

            $table->foreignId('id_producto')
                ->constrained('productos')
                ->restrictOnDelete();

            $table->foreignId('id_lote_almacen')
                ->constrained('lotes_almacen')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_transferencias');
        Schema::dropIfExists('transferencias');
    }
};
