<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEntradaFarmacia extends Model
{
    use HasFactory;

    protected $table = 'detalle_entradas_farmacia';

    protected $fillable = [
        'id_entrada_farmacia',
        'id_medicamento',
        'id_lote_farmacia',
        'cantidad',
    ];

    public function entrada()
    {
        return $this->belongsTo(EntradaFarmacia::class, 'id_entrada_farmacia');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }

    public function lote()
    {
        return $this->belongsTo(LoteFarmacia::class, 'id_lote_farmacia');
    }
}
