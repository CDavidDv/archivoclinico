<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalArchivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'numero_empleado',
        'rfc',
        'cargo',
        'area',
        'tipo'
    ];

    public function prestamosEntregados(){
        return $this->hasMany(Prestamo::class, 'entregado_por');
    }

    public function prestamosRecibidos(){
        return $this->hasMany(Prestamo::class, 'recibido_por');
    }

}
