<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'medicamentos';

    protected $fillable = [
        'clave',
        'nombre',
        'sustancia_activa',
        'presentacion',
        'unidad_medida',
        'stock_minimo',
        'id_producto',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function lotes()
    {
        return $this->hasMany(LoteFarmacia::class, 'id_medicamento');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    /* =====================================================
       STOCK
    ===================================================== */

    public function getStockTotalAttribute(): int
    {
        return (int) ($this->attributes['stock_total']
            ?? $this->lotes()->sum('cantidad_actual'));
    }

    public function scopeConStockTotal(Builder $query): Builder
    {
        return $query->withSum('lotes as stock_total', 'cantidad_actual');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
