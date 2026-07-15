<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();

            // Se asigna después de crear (REC-000001)
            $table->string('folio', 20)->nullable()->unique();

            $table->foreignId('id_derecho_habiente')
                ->constrained('derecho_habientes')
                ->restrictOnDelete();

            $table->foreignId('id_medico')
                ->constrained('medicos')
                ->restrictOnDelete();

            // Usuario que captura la receta
            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->date('fecha_receta');
            $table->string('diagnostico')->nullable();
            $table->text('indicaciones')->nullable();

            $table->enum('estatus', [
                'pendiente',
                'parcial',
                'surtida',
                'cancelada'
            ])->default('pendiente');

            $table->timestamps();

            $table->index('estatus');
        });

        Schema::create('detalle_recetas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_receta')
                ->constrained('recetas')
                ->cascadeOnDelete();

            $table->foreignId('id_medicamento')
                ->constrained('medicamentos')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad_prescrita');
            $table->unsignedInteger('cantidad_surtida')->default(0);
            $table->string('dosis', 150)->nullable();

            $table->timestamps();

            $table->unique(['id_receta', 'id_medicamento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_recetas');
        Schema::dropIfExists('recetas');
    }
};
