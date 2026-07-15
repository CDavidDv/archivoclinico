<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'rfc',
        'numero_empleado',
        'cargo',
        'area',
        'tipo'
    ];

    public function prestamos(){
        return $this->hasMany(Prestamo::class, 'id_medico');
    }

}
