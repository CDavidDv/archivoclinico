<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia la cache de permisos de Spatie.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Crea los 5 roles del sistema (guard web) — solo roles, sin permisos finos.
        foreach (Usuario::ROLES as $rol) {
            Role::firstOrCreate(['name' => $rol, 'guard_name' => 'web']);
        }

        $usuarios = [
            ['nombre_usuario' => 'admin',     'email' => 'admin@archivo.local',     'rol' => Usuario::ROL_ADMINISTRADOR],
            ['nombre_usuario' => 'medico1',   'email' => 'medico1@archivo.local',   'rol' => Usuario::ROL_MEDICO],
            ['nombre_usuario' => 'archivo1',  'email' => 'archivo1@archivo.local',  'rol' => Usuario::ROL_ARCHIVO],
            ['nombre_usuario' => 'farmacia1', 'email' => 'farmacia1@archivo.local', 'rol' => Usuario::ROL_FARMACIA],
            ['nombre_usuario' => 'almacen1',  'email' => 'almacen1@archivo.local',  'rol' => Usuario::ROL_ALMACEN],
        ];

        foreach ($usuarios as $datos) {
            $usuario = Usuario::firstOrCreate(
                ['nombre_usuario' => $datos['nombre_usuario']],
                $datos + ['password' => 'password']
            );

            // Mantiene el rol Spatie sincronizado con la columna enum.
            $usuario->syncRoles([$datos['rol']]);
        }
    }
}
