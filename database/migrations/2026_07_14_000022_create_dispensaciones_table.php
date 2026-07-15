<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_receta')
                ->constrained('recetas')
                ->restrictOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->dateTime('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('detalle_dispensaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_dispensacion')
                ->constrained('dispensaciones')
                ->cascadeOnDelete();

            $table->foreignId('id_detalle_receta')
                ->constrained('detalle_recetas')
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
        Schema::dropIfExists('detalle_dispensaciones');
        Schema::dropIfExists('dispensaciones');
    }
};
