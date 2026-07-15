<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Amplía el enum de rol a los 5 roles del sistema integral y agrega
     * la columna telefono en instalaciones ya migradas (MySQL).
     * En instalaciones frescas (tests/sqlite) la migración original ya
     * incluye estos cambios, por eso las guardas.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE usuarios MODIFY rol " .
                "ENUM('medico','archivo','administrador','farmacia','almacen') " .
                "NOT NULL DEFAULT 'archivo'"
            );
        }

        if (!Schema::hasColumn('usuarios', 'telefono')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('telefono', 20)->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE usuarios MODIFY rol " .
                "ENUM('medico','archivo','administrador') " .
                "NOT NULL DEFAULT 'archivo'"
            );
        }
    }
};
