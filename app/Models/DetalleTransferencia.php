<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTransferencia extends Model
{
    use HasFactory;

    protected $table = 'detalle_transferencias';

    protected $fillable = [
        'id_transferencia',
        'id_producto',
        'id_lote_almacen',
        'cantidad',
    ];

    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class, 'id_transferencia');
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
