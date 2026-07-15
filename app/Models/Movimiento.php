<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla_afectada',
        'id_registro_afectado',
        'fecha_accion'
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    protected static function booted()
    {
        static::creating(function ($movimiento) {
            $movimiento->fecha_accion = now();
        });
    }

}
