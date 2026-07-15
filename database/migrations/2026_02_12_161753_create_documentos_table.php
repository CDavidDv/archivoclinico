<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELACIÓN
            |--------------------------------------------------------------------------
            */
            $table->foreignId('id_expediente')
                ->constrained('expedientes')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN DEL DOCUMENTO
            |--------------------------------------------------------------------------
            */

            $table->string('nombre_documento', 255)
                ->comment('Nombre descriptivo asignado por el usuario');

            $table->string('nombre_original', 255)
                ->nullable()
                ->comment('Nombre original del archivo subido');

            $table->string('ruta_archivo', 255)
                ->comment('Ruta relativa en storage. Ej: documentos/1/archivo.pdf');

            $table->string('tipo_archivo', 20)
                ->nullable()
                ->comment('Extensión del archivo: pdf, jpg, mp4, etc');

            $table->unsignedBigInteger('tamano')
                ->nullable()
                ->comment('Tamaño del archivo en bytes');

            $table->string('hash', 64)
                ->nullable()
                ->comment('Hash único del archivo para evitar duplicados');

            $table->date('fecha_anexo')
                ->comment('Fecha en que el documento fue anexado al expediente');

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | ÍNDICES (OPTIMIZACIÓN)
            |--------------------------------------------------------------------------
            */

            $table->index('id_expediente', 'idx_documentos_expediente');
            $table->index('fecha_anexo', 'idx_documentos_fecha');
            $table->index(['id_expediente', 'fecha_anexo'], 'idx_documentos_exp_fecha');

            // Búsquedas por tipo
            $table->index('tipo_archivo', 'idx_documentos_tipo');

            // Para evitar duplicados (opcional)
            $table->index('hash', 'idx_documentos_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};