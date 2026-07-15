<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'transferencias';

    public const DESTINO_FARMACIA = 'farmacia';
    public const DESTINO_AREA     = 'area';

    protected $fillable = [
        'folio',
        'destino',
        'area_destino',
        'id_solicitud',
        'id_usuario',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /* =====================================================
       FOLIO AUTOMÁTICO
    ===================================================== */

    protected static function booted(): void
    {
        static::created(function (Transferencia $transferencia) {
            $transferencia->updateQuietly([
                'folio' => 'TRF-' . str_pad($transferencia->id, 6, '0', STR_PAD_LEFT),
            ]);
        });
    }

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function detalles()
    {
        return $this->hasMany(DetalleTransferencia::class, 'id_transferencia');
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudAbastecimiento::class, 'id_solicitud');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function entradaFarmacia()
    {
        return $this->hasOne(EntradaFarmacia::class, 'id_transferencia');
    }
}
