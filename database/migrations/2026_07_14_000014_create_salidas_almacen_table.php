<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas_almacen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->enum('tipo', ['uso_interno', 'merma', 'ajuste'])->default('uso_interno');
            $table->string('area_destino', 150)->nullable();
            $table->date('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('detalle_salidas_almacen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_salida_almacen')
                ->constrained('salidas_almacen')
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
        Schema::dropIfExists('detalle_salidas_almacen');
        Schema::dropIfExists('salidas_almacen');
    }
};
