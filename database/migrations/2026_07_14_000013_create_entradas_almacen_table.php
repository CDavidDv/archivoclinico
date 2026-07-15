<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas_almacen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_proveedor')
                ->nullable()
                ->constrained('proveedores')
                ->nullOnDelete();

            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->enum('tipo', ['compra', 'ajuste'])->default('compra');
            $table->date('fecha');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('detalle_entradas_almacen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_entrada_almacen')
                ->constrained('entradas_almacen')
                ->cascadeOnDelete();

            $table->foreignId('id_producto')
                ->constrained('productos')
                ->restrictOnDelete();

            $table->foreignId('id_lote_almacen')
                ->constrained('lotes_almacen')
                ->restrictOnDelete();

            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_entradas_almacen');
        Schema::dropIfExists('entradas_almacen');
    }
};
