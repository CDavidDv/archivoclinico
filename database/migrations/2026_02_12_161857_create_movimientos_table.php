<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')
            ->constrained('usuarios')
            ->onDelete('cascade');
            $table->string('accion');
            $table->string('tabla_afectada')->nullable();
            $table->string('id_registro_afectado')->nullable();
            $table->timestamp('fecha_accion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
