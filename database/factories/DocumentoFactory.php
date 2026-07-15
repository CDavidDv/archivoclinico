<?php

namespace Database\Factories;

use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Documento>
 */
class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition(): array
    {
        return [
            'id_expediente'    => Expediente::factory(),
            'nombre_documento' => fake()->words(3, true),
            'ruta_archivo'     => 'documentos/1/' . fake()->uuid() . '.pdf',
            'nombre_original'  => 'documento.pdf',
            'tipo_archivo'     => 'pdf',
            'tamano'           => fake()->numberBetween(1000, 500000),
            'hash'             => fake()->md5(),
            'fecha_anexo'      => now()->toDateString(),
        ];
    }
}
