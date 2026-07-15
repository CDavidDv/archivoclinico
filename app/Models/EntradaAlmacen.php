<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaAlmacen extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'entradas_almacen';

    public const TIPO_COMPRA = 'compra';
    public const TIPO_AJUSTE = 'ajuste';

    public const TIPOS = [
        self::TIPO_COMPRA,
        self::TIPO_AJUSTE,
    ];

    protected $fillable = [
        'id_proveedor',
        'id_usuario',
        'tipo',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleEntradaAlmacen::class, 'id_entrada_almacen');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
