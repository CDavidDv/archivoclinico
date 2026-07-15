<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSalidaAlmacen extends Model
{
    use HasFactory;

    protected $table = 'detalle_salidas_almacen';

    protected $fillable = [
        'id_salida_almacen',
        'id_producto',
        'id_lote_almacen',
        'cantidad',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaAlmacen::class, 'id_salida_almacen');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function lote()
    {
        return $this->belongsTo(LoteAlmacen::class, 'id_lote_almacen');
    }
}
