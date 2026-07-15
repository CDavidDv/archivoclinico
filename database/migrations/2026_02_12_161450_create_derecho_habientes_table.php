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
        Schema::create('derecho_habientes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');

            $table->string('rfc'); // Puede repetirse
            $table->string('nss')->unique(); // NSS único

            $table->integer('clave_identificacion');
            $table->string('clave_generada'); // Puede repetirse

            $table->date('fecha_nacimiento');
            $table->string('genero');

            $table->string('imagen')->nullable();
            $table->date('fecha_registro')->nullable();

            $table->text('sintomas')->nullable();
            $table->text('tratamiento')->nullable();

            // 🔒 Persona única
            $table->unique([
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'fecha_nacimiento'
            ], 'persona_unica');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('derecho_habientes');
    }
};
