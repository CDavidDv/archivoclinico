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
        'piezas_por_presentacion',
        'unidad_medida',
        'stock_minimo',
        'controlado',
        'dias_restriccion',
        'id_producto',
        'activo',
    ];

    protected $casts = [
        'activo'                  => 'boolean',
        'controlado'              => 'boolean',
        'piezas_por_presentacion' => 'integer',
        'dias_restriccion'        => 'integer',
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

    /* =====================================================
       PRESENTACIÓN COMERCIAL
    ===================================================== */

    /** El medicamento sólo se entrega en cajas completas. */
    public function entregaPorCajas(): bool
    {
        return (int) $this->piezas_por_presentacion > 1;
    }

    /** Cajas completas necesarias para cubrir una cantidad de piezas. */
    public function cajasNecesarias(int $piezas): int
    {
        $ppp = max(1, (int) $this->piezas_por_presentacion);
        return (int) ceil($piezas / $ppp);
    }

    /** Piezas reales que se entregan al surtir en cajas completas. */
    public function piezasEnCajas(int $piezas): int
    {
        $ppp = max(1, (int) $this->piezas_por_presentacion);
        return $this->cajasNecesarias($piezas) * $ppp;
    }
}
