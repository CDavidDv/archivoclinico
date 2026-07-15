<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSalidaFarmacia extends Model
{
    use HasFactory;

    protected $table = 'detalle_salidas_farmacia';

    protected $fillable = [
        'id_salida_farmacia',
        'id_medicamento',
        'id_lote_farmacia',
        'cantidad',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaFarmacia::class, 'id_salida_farmacia');
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
