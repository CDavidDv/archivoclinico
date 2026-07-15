<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /* =====================================================
       FORMULARIO DE LOGIN
    ===================================================== */

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /* =====================================================
       INICIAR SESIÓN
    ===================================================== */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nombre_usuario' => 'required|string',
            'password'       => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('recordar'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['nombre_usuario' => 'Usuario o contraseña incorrectos.'])
            ->onlyInput('nombre_usuario');
    }

    /* =====================================================
       CERRAR SESIÓN
    ===================================================== */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
