<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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

    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = EntradaAlmacen::with(['proveedor', 'usuario'])
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id');

        $filtros = $this->aplicarFiltros($query, $request, [
            'id'        => 'exact',
            'fecha'     => 'date',
            'tipo'      => 'like',
            'proveedor' => fn ($q, $v) => $q->whereHas('proveedor', fn ($p) => $p->where('nombre', 'like', "%{$v}%")),
            'usuario'   => fn ($q, $v) => $q->whereHas('usuario', fn ($u) => $u->where('nombre_usuario', 'like', "%{$v}%")),
        ]);

        $entradas = $query->paginate(20)->withQueryString();

        return Inertia::render('EntradasAlmacen/Index', compact('entradas', 'filtros'));
    }

    public function create()
    {
        return Inertia::render('EntradasAlmacen/Create', $this->formData());
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

        return Inertia::render('EntradasAlmacen/Show', ['entrada' => $entradas_almacen]);
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
