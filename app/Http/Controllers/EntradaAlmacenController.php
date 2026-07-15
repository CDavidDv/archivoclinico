<?php

namespace App\Http\Controllers;

use App\Models\EntradaAlmacen;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Services\InventarioAlmacenService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EntradaAlmacenController extends Controller
{
    public function __construct(
        private readonly InventarioAlmacenService $inventario
    ) {}

    public function index()
    {
        $entradas = EntradaAlmacen::with(['proveedor', 'usuario'])
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id')
            ->paginate(20);

        return view('entradas_almacen.index', compact('entradas'));
    }

    public function create()
    {
        return view('entradas_almacen.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $entrada = $this->inventario->registrarEntrada(
            $validated,
            $validated['detalles'],
            $request->user()
        );

        return redirect()
            ->route('entradas_almacen.show', $entrada)
            ->with('success', 'Entrada registrada correctamente.');
    }

    public function show(EntradaAlmacen $entradas_almacen)
    {
        $entradas_almacen->load(['proveedor', 'usuario', 'detalles.producto', 'detalles.lote']);

        return view('entradas_almacen.show', ['entrada' => $entradas_almacen]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'id_proveedor'               => 'nullable|exists:proveedores,id',
            'tipo'                       => ['required', Rule::in(EntradaAlmacen::TIPOS)],
            'fecha'                      => 'required|date',
            'observaciones'              => 'nullable|string',
            'detalles'                   => 'required|array|min:1',
            'detalles.*.id_producto'     => 'required|exists:productos,id',
            'detalles.*.numero_lote'     => 'required|string|max:50',
            'detalles.*.caducidad'       => 'nullable|date',
            'detalles.*.cantidad'        => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'nullable|numeric|min:0',
        ]);
    }

    private function formData(): array
    {
        return [
            'proveedores' => Proveedor::where('activo', true)->orderBy('nombre')->get(),
            'productos'   => Producto::activos()->orderBy('clave')->get(),
            'tipos'       => EntradaAlmacen::TIPOS,
        ];
    }
}
