<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registra el usuario que cargó el documento, para el buscador y auditoría.
     */
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('subido_por')->nullable()->after('fecha_anexo');
            $table->index('subido_por', 'idx_documentos_subido_por');
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropIndex('idx_documentos_subido_por');
            $table->dropColumn('subido_por');
        });
    }
};
