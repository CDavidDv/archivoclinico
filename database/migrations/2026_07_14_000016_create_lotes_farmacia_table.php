<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_farmacia', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_medicamento')
                ->constrained('medicamentos')
                ->cascadeOnDelete();

            $table->string('numero_lote', 50);

            // Los medicamentos siempre caducan
            $table->date('caducidad');

            $table->unsignedInteger('cantidad_actual')->default(0);

            $table->timestamps();

            $table->unique(['id_medicamento', 'numero_lote']);
            $table->index(['id_medicamento', 'caducidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_farmacia');
    }
};
