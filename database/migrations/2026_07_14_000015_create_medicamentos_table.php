<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 30)->unique();
            $table->string('nombre');
            $table->string('sustancia_activa');
            $table->string('presentacion', 100);
            $table->string('unidad_medida', 30)->default('pieza');
            $table->unsignedInteger('stock_minimo')->default(0);

            // Vínculo con el catálogo de almacén para transferencias
            $table->foreignId('id_producto')
                ->nullable()
                ->unique()
                ->constrained('productos')
                ->nullOnDelete();

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
