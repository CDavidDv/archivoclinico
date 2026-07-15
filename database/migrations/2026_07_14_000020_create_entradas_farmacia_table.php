<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas_farmacia', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['transferencia', 'ajuste'])->default('ajuste');

            $table->foreignId('id_transferencia')
                ->nullable()
                ->constrained('transferencias')
                ->nullOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->date('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('detalle_entradas_farmacia', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_entrada_farmacia')
                ->constrained('entradas_farmacia')
                ->cascadeOnDelete();

            $table->foreignId('id_medicamento')
                ->constrained('medicamentos')
                ->restrictOnDelete();

            $table->foreignId('id_lote_farmacia')
                ->constrained('lotes_farmacia')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_entradas_farmacia');
        Schema::dropIfExists('entradas_farmacia');
    }
};
