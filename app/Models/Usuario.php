<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    /* =====================================================
       ROLES
    ===================================================== */

    public const ROL_MEDICO        = 'medico';
    public const ROL_ARCHIVO       = 'archivo';
    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_FARMACIA      = 'farmacia';
    public const ROL_ALMACEN       = 'almacen';

    public const ROLES = [
        self::ROL_MEDICO,
        self::ROL_ARCHIVO,
        self::ROL_ADMINISTRADOR,
        self::ROL_FARMACIA,
        self::ROL_ALMACEN,
    ];

    /* =====================================================
       MASS ASSIGNMENT
    ===================================================== */

    protected $fillable = [
        'nombre_usuario',
        'email',
        'telefono',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /* =====================================================
       RELACIONES
    ===================================================== */

    public function movimientos(){
        return $this->hasMany(Movimiento::class, 'id_usuario');
    }

    /* =====================================================
       HELPERS
    ===================================================== */

    public function hasRol(string ...$roles): bool
    {
        return in_array($this->rol, $roles, true);
    }

    public function isAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }
}
