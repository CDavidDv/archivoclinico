<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expediente;

class DerechoHabiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'rfc',
        'nss',
        'clave_identificacion',
        'fecha_nacimiento',
        'genero',
        'imagen',
        'fecha_registro',
        'sintomas',
        'tratamiento',
        'clave_generada'
    ];

    public function expediente()
    {
        return $this->hasOne(Expediente::class, 'id_derecho_habiente');
    }

    protected static function booted()
    {
        static::created(function ($derechoHabiente) {

            if (!$derechoHabiente->expediente) {

                Expediente::create([
                    'codigo' => 'EXP-' . str_pad($derechoHabiente->id, 6, '0', STR_PAD_LEFT),
                    'id_derecho_habiente' => $derechoHabiente->id,
                    'localizacion' => 'Archivo Clínico',
                    'tipo' => 'normal',
                    'fecha_creacion' => now(),
                ]);

            }
        });
    }

    /**
     * Genera la clave institucional:
     * Primeros 10 caracteres del RFC + clave_identificacion
     */
    public static function generarClaveCompleta($rfc, $claveIdentificacion)
    {

        if (strlen($rfc) < 10) {
            throw new \Exception("El RFC debe tener al menos 10 caracteres.");
        }

        $rfc = strtoupper($rfc);
        
        $rfcBase = substr($rfc, 0, 10);

        return $rfcBase . '/' . $claveIdentificacion;
    }

    public function getClaveCompletaAttribute()
    {
        if (!$this->rfc || strlen($this->rfc) < 10) {
            return null;
        }

        $rfcBase = strtoupper(substr($this->rfc, 0, 10));

        return $rfcBase . '/' . $this->clave_identificacion;
    }

}
