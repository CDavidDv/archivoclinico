<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'rfc',
        'telefono',
        'email',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function entradas()
    {
        return $this->hasMany(EntradaAlmacen::class, 'id_proveedor');
    }
}
