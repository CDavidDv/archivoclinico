<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {

            $table->id();

            /* ============================
               RELACIONES
            ============================ */

            $table->foreignId('id_expediente')
                ->constrained('expedientes')
                ->restrictOnDelete();

            $table->foreignId('id_medico')
                ->constrained('medicos')
                ->restrictOnDelete();

            $table->foreignId('entregado_por')
                ->constrained('personal_archivos')
                ->restrictOnDelete();

            $table->foreignId('recibido_por')
                ->nullable()
                ->constrained('personal_archivos')
                ->nullOnDelete();


            /* ============================
               INFORMACIÓN DEL PRÉSTAMO
            ============================ */

            $table->string('area_destino', 150);

            $table->date('fecha_salida');
            $table->date('fecha_regreso');

            // Ahora es datetime
            $table->dateTime('fecha_devolucion_real')->nullable();

            $table->unsignedInteger('dias_asignados');


            /* ============================
               ESTATUS
            ============================ */

            $table->enum('estatus', [
                'pendiente',
                'devuelto',
                'vencido'
            ])->default('pendiente');

            /* ============================
               ÍNDICES PARA RENDIMIENTO
            ============================ */

            $table->index('estatus');
            $table->index('fecha_regreso');
            $table->index('fecha_devolucion_real');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};