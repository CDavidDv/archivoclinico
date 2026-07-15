<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDispensacion extends Model
{
    use HasFactory;

    protected $table = 'detalle_dispensaciones';

    protected $fillable = [
        'id_dispensacion',
        'id_detalle_receta',
        'id_lote_farmacia',
        'cantidad',
    ];

    public function dispensacion()
    {
        return $this->belongsTo(Dispensacion::class, 'id_dispensacion');
    }

    public function detalleReceta()
    {
        return $this->belongsTo(DetalleReceta::class, 'id_detalle_receta');
    }

    public function lote()
    {
        return $this->belongsTo(LoteFarmacia::class, 'id_lote_farmacia');
    }
}
