<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Uso en rutas: middleware('role:farmacia,almacen').
     * El administrador siempre tiene acceso.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        // Permisos vía Spatie: el administrador siempre tiene acceso.
        if (!$usuario->hasRole(Usuario::ROL_ADMINISTRADOR) && !$usuario->hasAnyRole($roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
