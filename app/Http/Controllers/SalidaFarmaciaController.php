<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LoteFarmacia;
use App\Models\SalidaFarmacia;
use App\Services\InventarioFarmaciaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalidaFarmaciaController extends Controller
{
    public function __construct(
        private readonly InventarioFarmaciaService $inventario
    ) {}

    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = SalidaFarmacia::with('usuario')
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id');

        $filtros = $this->aplicarFiltros($query, $request, [
            'id'      => 'exact',
            'fecha'   => 'date',
            'tipo'    => 'like',
            'usuario' => fn ($q, $v) => $q->whereHas('usuario', fn ($u) => $u->where('nombre_usuario', 'like', "%{$v}%")),
        ]);

        $salidas = $query->paginate(20)->withQueryString();

        return Inertia::render('SalidasFarmacia/Index', compact('salidas', 'filtros'));
    }

    public function create()
    {
        $lotes = LoteFarmacia::with('medicamento')
            ->where('cantidad_actual', '>', 0)
            ->orderBy('caducidad')
            ->get();

        return Inertia::render('SalidasFarmacia/Create', [
            'lotes' => $lotes,
            'tipos' => SalidaFarmacia::TIPOS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'                        => ['required', Rule::in(SalidaFarmacia::TIPOS)],
            'fecha'                       => 'required|date',
            'observaciones'               => 'nullable|string',
            'detalles'                    => 'required|array|min:1',
            'detalles.*.id_lote_farmacia' => 'required|exists:lotes_farmacia,id',
            'detalles.*.cantidad'         => 'required|integer|min:1',
        ]);

        $salida = $this->inventario->registrarSalida(
            $validated,
            $validated['detalles'],
            $request->user()
        );

        return redirect()
            ->route('salidas_farmacia.show', $salida)
            ->with('success', 'Salida registrada correctamente.');
    }

    public function show(SalidaFarmacia $salidas_farmacium)
    {
        $salidas_farmacium->load(['usuario', 'detalles.medicamento', 'detalles.lote']);

        return Inertia::render('SalidasFarmacia/Show', ['salida' => $salidas_farmacium]);
    }
}
