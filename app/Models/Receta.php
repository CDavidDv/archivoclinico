<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'recetas';

    /* =====================================================
       CONSTANTES
    ===================================================== */

    public const ESTATUS_PENDIENTE = 'pendiente';
    public const ESTATUS_PARCIAL   = 'parcial';
    public const ESTATUS_SURTIDA   = 'surtida';
    public const ESTATUS_CANCELADA = 'cancelada';

    protected $fillable = [
        'folio',
        'id_derecho_habiente',
        'id_medico',
        'id_usuario',
        'fecha_receta',
        'diagnostico',
        'indicaciones',
        'estatus',
    ];

    protected $casts = [
        'fecha_receta' => 'date',
    ];

    /* =====================================================
       FOLIO AUTOMÁTICO
    ===================================================== */

    protected static function booted(): void
    {
        static::created(function (Receta $receta) {
            $receta->updateQuietly([
                'folio' => 'REC-' . str_pad($receta->id, 6, '0', STR_PAD_LEFT),
            ]);
        });
    }

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function detalles()
    {
        return $this->hasMany(DetalleReceta::class, 'id_receta');
    }

    public function derechoHabiente()
    {
        return $this->belongsTo(DerechoHabiente::class, 'id_derecho_habiente');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function dispensaciones()
    {
        return $this->hasMany(Dispensacion::class, 'id_receta');
    }

    /* =====================================================
       SCOPES / HELPERS
    ===================================================== */

    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereIn('estatus', [
            self::ESTATUS_PENDIENTE,
            self::ESTATUS_PARCIAL,
        ]);
    }

    public function isSurtida(): bool
    {
        return $this->estatus === self::ESTATUS_SURTIDA;
    }

    public function isCancelada(): bool
    {
        return $this->estatus === self::ESTATUS_CANCELADA;
    }

    public function puedeDispensarse(): bool
    {
        return in_array($this->estatus, [
            self::ESTATUS_PENDIENTE,
            self::ESTATUS_PARCIAL,
        ], true);
    }

    public function puedeCancelarse(): bool
    {
        return $this->estatus === self::ESTATUS_PENDIENTE;
    }

    /**
     * Recalcula el estatus según lo surtido en sus detalles.
     */
    public function actualizarEstatus(): void
    {
        $detalles = $this->detalles()->get();

        $completos = $detalles->every(
            fn ($d) => $d->cantidad_surtida >= $d->cantidad_prescrita
        );

        $algoSurtido = $detalles->sum('cantidad_surtida') > 0;

        $this->update([
            'estatus' => $completos
                ? self::ESTATUS_SURTIDA
                : ($algoSurtido ? self::ESTATUS_PARCIAL : self::ESTATUS_PENDIENTE),
        ]);
    }
}
