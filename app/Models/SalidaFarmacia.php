<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaFarmacia extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'salidas_farmacia';

    public const TIPO_MERMA     = 'merma';
    public const TIPO_CADUCIDAD = 'caducidad';
    public const TIPO_AJUSTE    = 'ajuste';

    public const TIPOS = [
        self::TIPO_MERMA,
        self::TIPO_CADUCIDAD,
        self::TIPO_AJUSTE,
    ];

    protected $fillable = [
        'tipo',
        'id_usuario',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleSalidaFarmacia::class, 'id_salida_farmacia');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
