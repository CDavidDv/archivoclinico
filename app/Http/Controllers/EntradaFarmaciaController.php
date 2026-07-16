<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\EntradaFarmacia;
use App\Models\Medicamento;
use App\Services\InventarioFarmaciaService;
use Illuminate\Http\Request;

class EntradaFarmaciaController extends Controller
{
    public function __construct(
        private readonly InventarioFarmaciaService $inventario
    ) {}

    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = EntradaFarmacia::with(['usuario', 'transferencia'])
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id');

        $filtros = $this->aplicarFiltros($query, $request, [
            'id'      => 'exact',
            'fecha'   => 'date',
            'usuario' => fn ($q, $v) => $q->whereHas('usuario', fn ($u) => $u->where('nombre_usuario', 'like', "%{$v}%")),
        ]);

        $orden = $this->aplicarOrden($query, $request, ['items' => 'detalles_count']);

        $entradas = $query->paginate(20)->withQueryString();

        return Inertia::render('EntradasFarmacia/Index', compact('entradas', 'filtros', 'orden'));
    }

    public function create()
    {
        return Inertia::render('EntradasFarmacia/Create', [
            'medicamentos' => Medicamento::activos()->orderBy('clave')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'                     => 'required|date',
            'observaciones'             => 'nullable|string',
            'detalles'                  => 'required|array|min:1',
            'detalles.*.id_medicamento' => 'required|exists:medicamentos,id',
            'detalles.*.numero_lote'    => 'required|string|max:50',
            'detalles.*.caducidad'      => 'required|date|after:today',
            'detalles.*.cantidad'       => 'required|integer|min:1',
        ]);

        $entrada = $this->inventario->registrarEntrada(
            $validated,
            $validated['detalles'],
            $request->user()
        );

        return redirect()
            ->route('entradas_farmacia.show', $entrada)
            ->with('success', 'Entrada registrada correctamente.');
    }

    public function show(EntradaFarmacia $entradas_farmacium)
    {
        $entradas_farmacium->load(['usuario', 'transferencia', 'detalles.medicamento', 'detalles.lote']);

        return Inertia::render('EntradasFarmacia/Show', ['entrada' => $entradas_farmacium]);
    }
}
