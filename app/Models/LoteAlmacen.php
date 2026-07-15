<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LoteAlmacen extends Model
{
    use HasFactory;

    protected $table = 'lotes_almacen';

    protected $fillable = [
        'id_producto',
        'numero_lote',
        'caducidad',
        'cantidad_actual',
    ];

    protected $casts = [
        'caducidad' => 'date',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    /* =====================================================
       SCOPES
    ===================================================== */

    /**
     * Lotes con existencia y no caducados, ordenados FEFO
     * (primero el que caduca más pronto; los sin caducidad al final).
     */
    public function scopeDisponiblesFefo(Builder $query): Builder
    {
        return $query
            ->where('cantidad_actual', '>', 0)
            ->where(function (Builder $q) {
                $q->whereNull('caducidad')
                  ->orWhereDate('caducidad', '>=', Carbon::today());
            })
            ->orderByRaw('caducidad IS NULL')
            ->orderBy('caducidad')
            ->orderBy('id');
    }

    public function scopeCaducados(Builder $query): Builder
    {
        return $query
            ->where('cantidad_actual', '>', 0)
            ->whereNotNull('caducidad')
            ->whereDate('caducidad', '<', Carbon::today());
    }

    public function isCaducado(): bool
    {
        return $this->caducidad !== null
            && $this->caducidad->isBefore(Carbon::today());
    }
}
