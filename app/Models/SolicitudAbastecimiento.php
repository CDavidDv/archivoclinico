<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAbastecimiento extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'solicitudes_abastecimiento';

    /* =====================================================
       CONSTANTES
    ===================================================== */

    public const ESTATUS_PENDIENTE = 'pendiente';
    public const ESTATUS_APROBADA  = 'aprobada';
    public const ESTATUS_SURTIDA   = 'surtida';
    public const ESTATUS_RECHAZADA = 'rechazada';

    public const MODULO_FARMACIA = 'farmacia';
    public const MODULO_ARCHIVO  = 'archivo';

    protected $fillable = [
        'folio',
        'modulo_solicitante',
        'id_usuario_solicita',
        'id_usuario_atiende',
        'estatus',
        'fecha_solicitud',
        'observaciones',
        'motivo_rechazo',
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
    ];

    /* =====================================================
       FOLIO AUTOMÁTICO
    ===================================================== */

    protected static function booted(): void
    {
        static::created(function (SolicitudAbastecimiento $solicitud) {
            $solicitud->updateQuietly([
                'folio' => 'SOL-' . str_pad($solicitud->id, 6, '0', STR_PAD_LEFT),
            ]);
        });
    }

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function detalles()
    {
        return $this->hasMany(DetalleSolicitudAbastecimiento::class, 'id_solicitud');
    }

    public function usuarioSolicita()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_solicita');
    }

    public function usuarioAtiende()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_atiende');
    }

    public function transferencias()
    {
        return $this->hasMany(Transferencia::class, 'id_solicitud');
    }

    /* =====================================================
       MÁQUINA DE ESTADOS
    ===================================================== */

    public function puedeAprobarse(): bool
    {
        return $this->estatus === self::ESTATUS_PENDIENTE;
    }

    public function puedeRechazarse(): bool
    {
        return $this->estatus === self::ESTATUS_PENDIENTE;
    }

    public function puedeSurtirse(): bool
    {
        return $this->estatus === self::ESTATUS_APROBADA;
    }

    public function scopeDelModulo(Builder $query, string $modulo): Builder
    {
        return $query->where('modulo_solicitante', $modulo);
    }
}
