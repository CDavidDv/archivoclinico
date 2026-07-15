<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Traits\RegistraMovimiento;

class Prestamo extends Model
{
    use HasFactory, RegistraMovimiento;

    /* =====================================================
       CONSTANTES
    ===================================================== */

    public const ESTATUS_PENDIENTE = 'pendiente';
    public const ESTATUS_DEVUELTO  = 'devuelto';
    public const ESTATUS_VENCIDO   = 'vencido';

    /* =====================================================
       MASS ASSIGNMENT
    ===================================================== */

    protected $fillable = [
        'id_expediente',
        'id_medico',
        'entregado_por',
        'recibido_por',
        'area_destino',
        'fecha_salida',
        'fecha_regreso',
        'fecha_devolucion_real',
        'dias_asignados',
        'estatus',
    ];

    /* =====================================================
       CASTS
    ===================================================== */

    protected $casts = [
        'fecha_salida'          => 'date',
        'fecha_regreso'         => 'date',
        'fecha_devolucion_real' => 'datetime',
    ];

    protected $appends = [
        'estatus_automatico',
    ];

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function entregadoPor()
    {
        return $this->belongsTo(PersonalArchivo::class, 'entregado_por');
    }

    public function recibidoPor()
    {
        return $this->belongsTo(PersonalArchivo::class, 'recibido_por');
    }

    /* =====================================================
       ESTATUS AUTOMÁTICO INTELIGENTE
    ===================================================== */

    public function getEstatusAutomaticoAttribute(): string
    {
        if ($this->fecha_devolucion_real !== null) {
            return self::ESTATUS_DEVUELTO;
        }

        if ($this->fecha_regreso === null) {
            return self::ESTATUS_PENDIENTE;
        }

        return Carbon::today()->gt($this->fecha_regreso)
            ? self::ESTATUS_VENCIDO
            : self::ESTATUS_PENDIENTE;
    }

    /*
    |--------------------------------------------------------------------------
    | SINCRONIZAR ESTATUS AUTOMÁTICAMENTE
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::saving(function ($prestamo) {
            $prestamo->estatus = $prestamo->estatus_automatico;
        });
    }

    /* =====================================================
       SCOPES
    ===================================================== */

    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereNull('fecha_devolucion_real');
    }

    public function scopeDevueltos(Builder $query): Builder
    {
        return $query->whereNotNull('fecha_devolucion_real');
    }

    public function scopeVencidos(Builder $query): Builder
    {
        return $query->whereNull('fecha_devolucion_real')
            ->whereDate('fecha_regreso', '<', Carbon::today());
    }

    /* =====================================================
       HELPERS
    ===================================================== */

    public function isPendiente(): bool
    {
        return $this->estatus_automatico === self::ESTATUS_PENDIENTE;
    }

    public function isDevuelto(): bool
    {
        return $this->estatus_automatico === self::ESTATUS_DEVUELTO;
    }

    public function isVencido(): bool
    {
        return $this->estatus_automatico === self::ESTATUS_VENCIDO;
    }

    /* =====================================================
       MÉTODOS DE NEGOCIO
    ===================================================== */

    public function marcarComoDevuelto(int $personalId): void
    {
        if ($this->isDevuelto()) {
            throw new \DomainException('El préstamo ya fue devuelto.');
        }

        $this->update([
            'recibido_por'          => $personalId,
            'fecha_devolucion_real' => now(),
        ]);
    }

    public function reactivar(): void
    {
        $this->update([
            'recibido_por'          => null,
            'fecha_devolucion_real' => null,
        ]);
    }
}