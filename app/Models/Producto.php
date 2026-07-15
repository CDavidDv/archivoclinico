<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'productos';

    public const CATEGORIA_MEDICAMENTO = 'medicamento';
    public const CATEGORIA_INSUMO      = 'insumo';
    public const CATEGORIA_PAPELERIA   = 'papeleria';
    public const CATEGORIA_OTRO        = 'otro';

    public const CATEGORIAS = [
        self::CATEGORIA_MEDICAMENTO,
        self::CATEGORIA_INSUMO,
        self::CATEGORIA_PAPELERIA,
        self::CATEGORIA_OTRO,
    ];

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'categoria',
        'unidad_medida',
        'stock_minimo',
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
        return $this->hasMany(LoteAlmacen::class, 'id_producto');
    }

    public function medicamento()
    {
        return $this->hasOne(Medicamento::class, 'id_producto');
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
