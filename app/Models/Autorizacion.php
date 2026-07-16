<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones';

    public const TIPO_EXCESO_CANTIDAD        = 'exceso_cantidad';
    public const TIPO_PRESENTACION_COMERCIAL = 'presentacion_comercial';
    public const TIPO_CONTROLADO_ANTICIPADO  = 'controlado_anticipado';

    protected $fillable = [
        'tipo',
        'id_usuario_solicita',
        'id_usuario_autoriza',
        'id_dispensacion',
        'id_detalle_receta',
        'id_medicamento',
        'cantidad_prescrita',
        'cantidad_autorizada',
        'motivo',
        'fecha_accion',
    ];

    protected $casts = [
        'fecha_accion' => 'datetime',
    ];

    public function solicitante()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_solicita');
    }

    public function autorizador()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_autoriza');
    }

    public function dispensacion()
    {
        return $this->belongsTo(Dispensacion::class, 'id_dispensacion');
    }

    public function detalleReceta()
    {
        return $this->belongsTo(DetalleReceta::class, 'id_detalle_receta');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }

    public function getTipoLegibleAttribute(): string
    {
        return match ($this->tipo) {
            self::TIPO_EXCESO_CANTIDAD        => 'Exceso de cantidad prescrita',
            self::TIPO_PRESENTACION_COMERCIAL => 'Surtido por presentación comercial',
            self::TIPO_CONTROLADO_ANTICIPADO  => 'Surtido anticipado de controlado',
            default                            => $this->tipo,
        };
    }
}
