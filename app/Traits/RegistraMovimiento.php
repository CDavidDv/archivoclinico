<?php

namespace App\Traits;

use App\Models\Movimiento;
use Illuminate\Support\Facades\Auth;

trait RegistraMovimiento
{
    protected static function bootRegistraMovimiento()
    {
        static::created(function ($model) {
            self::registrar('crear', $model);
        });

        static::updated(function ($model) {
            self::registrar('editar', $model);
        });

        static::deleted(function ($model) {
            self::registrar('eliminar', $model);
        });
    }

    protected static function registrar($accion, $model)
    {
        if (!Auth::check()) return;

        Movimiento::create([
            'id_usuario' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => $model->getTable(),
            'id_registro_afectado' => $model->id,
            'fecha_accion' => now(),
        ]);
    }
}