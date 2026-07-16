<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Movimiento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = Movimiento::with('usuario')
            ->orderBy('fecha_accion', 'desc');

        $filtros = $this->aplicarFiltros($query, $request, [
            'fecha'    => 'date:fecha_accion',
            'usuario'  => 'exact:id_usuario',
            'accion'   => 'like',
            'tabla'    => 'like:tabla_afectada',
            'registro' => 'exact:id_registro_afectado',
        ]);

        $movimientos = $query->paginate(20)->withQueryString();
        $usuarios = Usuario::orderBy('nombre_usuario')->get(['id', 'nombre_usuario']);

        return Inertia::render('Movimientos/Index', compact('movimientos', 'usuarios', 'filtros'));
    }
}