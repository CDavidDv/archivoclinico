<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Producto;
use App\Models\SalidaAlmacen;
use App\Services\InventarioAlmacenService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalidaAlmacenController extends Controller
{
    public function __construct(
        private readonly InventarioAlmacenService $inventario
    ) {}

    public function index()
    {
        $salidas = SalidaAlmacen::with('usuario')
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id')
            ->paginate(20);

        return Inertia::render('SalidasAlmacen/Index', compact('salidas'));
    }

    public function create()
    {
        return Inertia::render('SalidasAlmacen/Create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $salida = $this->inventario->registrarSalida(
            $validated,
            $validated['detalles'],
            $request->user()
        );

        return redirect()
            ->route('salidas_almacen.show', $salida)
            ->with('success', 'Salida registrada correctamente.');
    }

    public function show(SalidaAlmacen $salidas_almacen)
    {
        $salidas_almacen->load(['usuario', 'detalles.producto', 'detalles.lote']);

        return Inertia::render('SalidasAlmacen/Show', ['salida' => $salidas_almacen]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tipo'                   => ['required', Rule::in(SalidaAlmacen::TIPOS)],
            'area_destino'           => 'nullable|string|max:150',
            'fecha'                  => 'required|date',
            'observaciones'          => 'nullable|string',
            'detalles'               => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id',
            'detalles.*.cantidad'    => 'required|integer|min:1',
        ]);
    }

    private function formData(): array
    {
        return [
            'productos' => Producto::activos()
                ->conStockTotal()
                ->orderBy('clave')
                ->get(),
            'tipos'     => SalidaAlmacen::TIPOS,
        ];
    }
}
