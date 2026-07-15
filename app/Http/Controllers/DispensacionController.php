<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Dispensacion;
use App\Models\LoteFarmacia;
use App\Models\Receta;
use App\Services\DispensacionService;
use Illuminate\Http\Request;

class DispensacionController extends Controller
{
    public function __construct(
        private readonly DispensacionService $servicio
    ) {}

    public function index()
    {
        $dispensaciones = Dispensacion::with(['receta.derechoHabiente', 'usuario'])
            ->latest('fecha')
            ->paginate(20);

        return Inertia::render('Dispensaciones/Index', compact('dispensaciones'));
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

        return Inertia::render('Dispensaciones/Create', compact('receta', 'stockDisponible'));
    }

    public function store(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'cantidades'    => 'required|array',
            'cantidades.*'  => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $dispensacion = $this->servicio->dispensar(
            $receta,
            $validated['cantidades'],
            $request->user(),
            $validated['observaciones'] ?? null
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
        ]);

        return Inertia::render('Dispensaciones/Show', compact('dispensacion'));
    }
}
