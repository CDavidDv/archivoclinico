<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaAlmacen extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'salidas_almacen';

    public const TIPO_USO_INTERNO = 'uso_interno';
    public const TIPO_MERMA       = 'merma';
    public const TIPO_AJUSTE      = 'ajuste';

    public const TIPOS = [
        self::TIPO_USO_INTERNO,
        self::TIPO_MERMA,
        self::TIPO_AJUSTE,
    ];

    protected $fillable = [
        'id_usuario',
        'tipo',
        'area_destino',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleSalidaAlmacen::class, 'id_salida_almacen');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
