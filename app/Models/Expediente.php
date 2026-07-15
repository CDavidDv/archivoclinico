<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DerechoHabiente;
use App\Models\Documento;
use App\Models\Prestamo;

class Expediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'id_derecho_habiente',
        'localizacion',
        'tipo',
        'fecha_creacion',
        'fecha_eliminacion'
    ];

    public function derechoHabiente()
    {
        return $this->belongsTo(DerechoHabiente::class, 'id_derecho_habiente');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_expediente');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_expediente');
    }


}
