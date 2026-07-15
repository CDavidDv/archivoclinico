<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LoteFarmacia extends Model
{
    use HasFactory;

    protected $table = 'lotes_farmacia';

    protected $fillable = [
        'id_medicamento',
        'numero_lote',
        'caducidad',
        'cantidad_actual',
    ];

    protected $casts = [
        'caducidad' => 'date',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }

    /* =====================================================
       SCOPES
    ===================================================== */

    /**
     * Lotes con existencia y no caducados, ordenados FEFO.
     */
    public function scopeDisponiblesFefo(Builder $query): Builder
    {
        return $query
            ->where('cantidad_actual', '>', 0)
            ->whereDate('caducidad', '>=', Carbon::today())
            ->orderBy('caducidad')
            ->orderBy('id');
    }

    public function scopePorCaducar(Builder $query, int $dias = 30): Builder
    {
        return $query
            ->where('cantidad_actual', '>', 0)
            ->whereDate('caducidad', '>=', Carbon::today())
            ->whereDate('caducidad', '<=', Carbon::today()->addDays($dias));
    }

    public function scopeCaducados(Builder $query): Builder
    {
        return $query
            ->where('cantidad_actual', '>', 0)
            ->whereDate('caducidad', '<', Carbon::today());
    }

    public function isCaducado(): bool
    {
        return $this->caducidad->isBefore(Carbon::today());
    }
}
