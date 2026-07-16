<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AutorizacionService
{
    /**
     * Valida las credenciales del usuario superior que autoriza una excepción.
     * El autorizador debe ser un administrador (jefe de área) distinto del
     * usuario que opera la dispensación.
     *
     * @throws ValidationException si las credenciales no son válidas o
     *                             el usuario no tiene permisos suficientes.
     */
    public function validarAutorizador(string $nombreUsuario, string $password, Usuario $solicitante): Usuario
    {
        $autorizador = Usuario::where('nombre_usuario', $nombreUsuario)->first();

        if (!$autorizador || !Hash::check($password, $autorizador->password)) {
            throw ValidationException::withMessages([
                'autorizacion' => 'Credenciales de autorización incorrectas.',
            ]);
        }

        if (!$autorizador->isAdministrador()) {
            throw ValidationException::withMessages([
                'autorizacion' => 'El usuario indicado no tiene permisos para autorizar (se requiere jefe de área).',
            ]);
        }

        if ($autorizador->id === $solicitante->id) {
            throw ValidationException::withMessages([
                'autorizacion' => 'La autorización debe realizarla un usuario distinto al que dispensa.',
            ]);
        }

        return $autorizador;
    }
}
