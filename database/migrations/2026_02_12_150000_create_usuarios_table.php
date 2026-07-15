<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_usuario')->unique();
            $table->string('email')->unique();
            $table->string('telefono', 20)->nullable();

            $table->string('password');

            $table->enum('rol', [
                'medico',
                'archivo',
                'administrador',
                'farmacia',
                'almacen'
            ])->default('archivo');

            $table->rememberToken(); //  login
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
