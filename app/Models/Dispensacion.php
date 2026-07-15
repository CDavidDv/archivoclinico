<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispensacion extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'dispensaciones';

    protected $fillable = [
        'id_receta',
        'id_usuario',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleDispensacion::class, 'id_dispensacion');
    }
}
