<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $salidas = SalidaFarmacia::with('usuario')
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id')
            ->paginate(20);

        return view('salidas_farmacia.index', compact('salidas'));
    }

    public function create()
    {
        $lotes = LoteFarmacia::with('medicamento')
            ->where('cantidad_actual', '>', 0)
            ->orderBy('caducidad')
            ->get();

        return view('salidas_farmacia.create', [
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

        return view('salidas_farmacia.show', ['salida' => $salidas_farmacium]);
    }
}
