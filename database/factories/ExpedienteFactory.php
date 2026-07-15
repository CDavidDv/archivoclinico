<?php

namespace Database\Factories;

use App\Models\DerechoHabiente;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expediente>
 *
 * Nota: DerechoHabiente crea su Expediente automáticamente al crearse.
 * Esta factory es para casos que necesitan un expediente suelto.
 */
class ExpedienteFactory extends Factory
{
    protected $model = Expediente::class;

    public function definition(): array
    {
        return [
            'codigo'              => fake()->unique()->bothify('EXP-######'),
            'id_derecho_habiente' => DerechoHabiente::factory(),
            'localizacion'        => 'Archivo Clínico',
            'tipo'                => 'normal',
            'fecha_creacion'      => now(),
        ];
    }
}
