<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DetalleSolicitudAbastecimiento;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudAbastecimientoController extends Controller
{
    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $usuario = $request->user();

        $query = SolicitudAbastecimiento::with(['usuarioSolicita', 'usuarioAtiende'])
            ->withCount('detalles')
            ->when(
                !$usuario->hasRol(Usuario::ROL_ADMINISTRADOR, Usuario::ROL_ALMACEN),
                fn ($q) => $q->delModulo($this->moduloDelUsuario($usuario))
            )
            ->latest('fecha_solicitud')
            ->latest('id');

        $filtros = $this->aplicarFiltros($query, $request, [
            'folio'              => 'like',
            'modulo_solicitante' => 'like',
            'solicita'           => fn ($q, $v) => $q->whereHas('usuarioSolicita', fn ($u) => $u->where('nombre_usuario', 'like', "%{$v}%")),
            'fecha_solicitud'    => 'date',
            'estatus'            => 'exact',
        ]);

        $solicitudes = $query->paginate(20)->withQueryString();

        return Inertia::render('Solicitudes/Index', compact('solicitudes', 'filtros'));
    }

    public function create(Request $request)
    {
        return Inertia::render('Solicitudes/Create', [
            'productos' => Producto::activos()->orderBy('clave')->get(),
            'modulo'    => $this->moduloDelUsuario($request->user()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'observaciones'                  => 'nullable|string',
            'detalles'                       => 'required|array|min:1',
            'detalles.*.id_producto'         => 'required|exists:productos,id|distinct',
            'detalles.*.cantidad_solicitada' => 'required|integer|min:1',
        ]);

        // El módulo se deriva del rol del usuario, nunca del formulario
        $modulo = $this->moduloDelUsuario($request->user());

        $solicitud = DB::transaction(function () use ($validated, $request, $modulo) {
            $solicitud = SolicitudAbastecimiento::create([
                'modulo_solicitante'  => $modulo,
                'id_usuario_solicita' => $request->user()->id,
                'estatus'             => SolicitudAbastecimiento::ESTATUS_PENDIENTE,
                'fecha_solicitud'     => now()->toDateString(),
                'observaciones'       => $validated['observaciones'] ?? null,
            ]);

            foreach ($validated['detalles'] as $detalle) {
                DetalleSolicitudAbastecimiento::create([
                    'id_solicitud'        => $solicitud->id,
                    'id_producto'         => $detalle['id_producto'],
                    'cantidad_solicitada' => $detalle['cantidad_solicitada'],
                ]);
            }

            return $solicitud;
        });

        return redirect()
            ->route('solicitudes.show', $solicitud)
            ->with('success', "Solicitud {$solicitud->folio} registrada correctamente.");
    }

    public function show(SolicitudAbastecimiento $solicitud)
    {
        $solicitud->load([
            'usuarioSolicita',
            'usuarioAtiende',
            'detalles.producto',
            'transferencias',
        ]);

        return Inertia::render('Solicitudes/Show', compact('solicitud'));
    }

    public function aprobar(Request $request, SolicitudAbastecimiento $solicitud)
    {
        if (!$solicitud->puedeAprobarse()) {
            return back()->withErrors([
                'error' => 'Solo se pueden aprobar solicitudes pendientes.'
            ]);
        }

        $solicitud->update([
            'estatus'            => SolicitudAbastecimiento::ESTATUS_APROBADA,
            'id_usuario_atiende' => $request->user()->id,
        ]);

        return back()->with('success', "Solicitud {$solicitud->folio} aprobada.");
    }

    public function rechazar(Request $request, SolicitudAbastecimiento $solicitud)
    {
        if (!$solicitud->puedeRechazarse()) {
            return back()->withErrors([
                'error' => 'Solo se pueden rechazar solicitudes pendientes.'
            ]);
        }

        $validated = $request->validate([
            'motivo_rechazo' => 'required|string|max:255',
        ]);

        $solicitud->update([
            'estatus'            => SolicitudAbastecimiento::ESTATUS_RECHAZADA,
            'id_usuario_atiende' => $request->user()->id,
            'motivo_rechazo'     => $validated['motivo_rechazo'],
        ]);

        return back()->with('success', "Solicitud {$solicitud->folio} rechazada.");
    }

    private function moduloDelUsuario(Usuario $usuario): string
    {
        return $usuario->hasRol(Usuario::ROL_ARCHIVO)
            ? SolicitudAbastecimiento::MODULO_ARCHIVO
            : SolicitudAbastecimiento::MODULO_FARMACIA;
    }
}
