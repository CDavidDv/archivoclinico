<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_almacen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_producto')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->string('numero_lote', 50);

            // Nullable: insumos y papelería pueden no caducar
            $table->date('caducidad')->nullable();

            $table->unsignedInteger('cantidad_actual')->default(0);

            $table->timestamps();

            $table->unique(['id_producto', 'numero_lote']);
            $table->index(['id_producto', 'caducidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_almacen');
    }
};
