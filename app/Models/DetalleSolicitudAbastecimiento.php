<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSolicitudAbastecimiento extends Model
{
    use HasFactory;

    protected $table = 'detalle_solicitudes_abastecimiento';

    protected $fillable = [
        'id_solicitud',
        'id_producto',
        'cantidad_solicitada',
        'cantidad_surtida',
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudAbastecimiento::class, 'id_solicitud');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
