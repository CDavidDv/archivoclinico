<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReceta extends Model
{
    use HasFactory;

    protected $table = 'detalle_recetas';

    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'cantidad_prescrita',
        'cantidad_surtida',
        'dosis',
    ];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }

    public function cantidadPendiente(): int
    {
        return max(0, $this->cantidad_prescrita - $this->cantidad_surtida);
    }
}
