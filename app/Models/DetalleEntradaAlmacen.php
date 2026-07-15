<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEntradaAlmacen extends Model
{
    use HasFactory;

    protected $table = 'detalle_entradas_almacen';

    protected $fillable = [
        'id_entrada_almacen',
        'id_producto',
        'id_lote_almacen',
        'cantidad',
        'precio_unitario',
    ];

    public function entrada()
    {
        return $this->belongsTo(EntradaAlmacen::class, 'id_entrada_almacen');
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
