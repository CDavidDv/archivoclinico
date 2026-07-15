<?php

namespace App\Models;

use App\Traits\RegistraMovimiento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaFarmacia extends Model
{
    use HasFactory, RegistraMovimiento;

    protected $table = 'entradas_farmacia';

    public const TIPO_TRANSFERENCIA = 'transferencia';
    public const TIPO_AJUSTE        = 'ajuste';

    protected $fillable = [
        'tipo',
        'id_transferencia',
        'id_usuario',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleEntradaFarmacia::class, 'id_entrada_farmacia');
    }

    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class, 'id_transferencia');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
