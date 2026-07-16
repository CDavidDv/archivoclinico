<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Dispensacion;
use App\Models\LoteFarmacia;
use App\Models\Receta;
use App\Services\AutorizacionService;
use App\Services\DispensacionService;
use Illuminate\Http\Request;

class DispensacionController extends Controller
{
    use \App\Http\Concerns\FiltraConsulta;

    public function __construct(
        private readonly DispensacionService $servicio,
        private readonly AutorizacionService $autorizaciones
    ) {}

    public function index(Request $request)
    {
        $query = Dispensacion::with(['receta.derechoHabiente', 'usuario'])
            ->latest('fecha');

        $filtros = $this->aplicarFiltros($query, $request, [
            'id'       => 'exact',
            'receta'   => fn ($q, $v) => $q->whereHas('receta', fn ($r) => $r->where('folio', 'like', "%{$v}%")),
            'derechohabiente' => fn ($q, $v) => $q->whereHas('receta.derechoHabiente', fn ($dh) => $dh
                ->where('nombre', 'like', "%{$v}%")
                ->orWhere('apellido_paterno', 'like', "%{$v}%")
                ->orWhere('apellido_materno', 'like', "%{$v}%")),
            'fecha'    => 'date',
            'usuario'  => fn ($q, $v) => $q->whereHas('usuario', fn ($u) => $u->where('nombre_usuario', 'like', "%{$v}%")),
        ]);

        $dispensaciones = $query->paginate(20)->withQueryString();

        return Inertia::render('Dispensaciones/Index', compact('dispensaciones', 'filtros'));
    }

    public function create(Receta $receta)
    {
        if (!$receta->puedeDispensarse()) {
            return redirect()
                ->route('recetas.show', $receta)
                ->withErrors(['error' => 'Esta receta no puede dispensarse (' . $receta->estatus . ').']);
        }

        $receta->load(['derechoHabiente', 'medico', 'detalles.medicamento']);

        $stockDisponible = $receta->detalles
            ->mapWithKeys(fn ($detalle) => [
                $detalle->id => LoteFarmacia::where('id_medicamento', $detalle->id_medicamento)
                    ->disponiblesFefo()
                    ->sum('cantidad_actual'),
            ]);

        // Estado de restricción de controlados por renglón.
        $restricciones = $receta->detalles
            ->mapWithKeys(fn ($detalle) => [
                $detalle->id => $this->servicio->estadoControlado(
                    $detalle->medicamento,
                    $receta->id_derecho_habiente
                ),
            ]);

        return Inertia::render('Dispensaciones/Create', compact('receta', 'stockDisponible', 'restricciones'));
    }

    public function store(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'cantidades'              => 'required|array',
            'cantidades.*'            => 'nullable|integer|min:0',
            'observaciones'           => 'nullable|string',
            'autorizacion'            => 'nullable|array',
            'autorizacion.nombre_usuario' => 'nullable|string',
            'autorizacion.password'   => 'nullable|string',
            'autorizacion.motivo'     => 'nullable|string',
        ]);

        // Si el operador aportó credenciales de autorización, se validan aquí.
        $autorizador = null;
        $motivo      = null;

        $cred = $validated['autorizacion'] ?? [];
        if (!empty($cred['nombre_usuario']) || !empty($cred['password'])) {
            $request->validate([
                'autorizacion.nombre_usuario' => 'required|string',
                'autorizacion.password'       => 'required|string',
                'autorizacion.motivo'         => 'required|string|min:5',
            ], [], ['autorizacion.motivo' => 'motivo de autorización']);

            $autorizador = $this->autorizaciones->validarAutorizador(
                $cred['nombre_usuario'],
                $cred['password'],
                $request->user()
            );
            $motivo = $cred['motivo'];
        }

        $dispensacion = $this->servicio->dispensar(
            $receta,
            $validated['cantidades'],
            $request->user(),
            $validated['observaciones'] ?? null,
            $autorizador,
            $motivo
        );

        return redirect()
            ->route('dispensaciones.show', $dispensacion)
            ->with('success', "Dispensación registrada. Receta {$receta->fresh()->estatus}.");
    }

    public function show(Dispensacion $dispensacion)
    {
        $dispensacion->load([
            'receta.derechoHabiente',
            'usuario',
            'detalles.detalleReceta.medicamento',
            'detalles.lote',
            'autorizaciones.autorizador',
            'autorizaciones.medicamento',
        ]);

        return Inertia::render('Dispensaciones/Show', compact('dispensacion'));
    }
}
