<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 30)->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->enum('categoria', [
                'medicamento',
                'insumo',
                'papeleria',
                'otro'
            ])->default('insumo');

            $table->string('unidad_medida', 30)->default('pieza');
            $table->unsignedInteger('stock_minimo')->default(0);
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
