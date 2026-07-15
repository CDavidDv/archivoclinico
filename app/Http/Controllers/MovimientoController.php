<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Movimiento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimiento::with('usuario')
            ->orderBy('fecha_accion', 'desc');

        if ($request->filled('usuario')) {
            $query->where('id_usuario', $request->usuario);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fecha_accion', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha_accion', '<=', $request->hasta);
        }

        $movimientos = $query->paginate(15);
        $usuarios = Usuario::orderBy('nombre_usuario')->get();

        return Inertia::render('Movimientos/Index', compact('movimientos','usuarios'));
    }
}