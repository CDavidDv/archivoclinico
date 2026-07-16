<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductoController extends Controller
{
    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = Producto::conStockTotal()->orderBy('clave');

        $filtros = $this->aplicarFiltros($query, $request, [
            'clave'         => 'like',
            'nombre'        => 'like',
            'categoria'     => 'like',
            'unidad_medida' => 'like',
        ]);

        $productos = $query->paginate(20)->withQueryString();

        return Inertia::render('Productos/Index', compact('productos', 'filtros'));
    }

    public function create()
    {
        return Inertia::render('Productos/Create', $this->formData());
    }

    public function store(Request $request)
    {
        Producto::create($this->validateData($request));

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load(['lotes' => fn ($q) => $q->orderBy('caducidad'), 'medicamento']);

        return Inertia::render('Productos/Show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return Inertia::render('Productos/Edit', array_merge(
            compact('producto'),
            $this->formData()
        ));
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($this->validateData($request, $producto));

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->lotes()->where('cantidad_actual', '>', 0)->exists()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar un producto con existencias. Puedes desactivarlo.'
            ]);
        }

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    private function validateData(Request $request, ?Producto $producto = null): array
    {
        return $request->validate([
            'clave'         => [
                'required', 'string', 'max:30',
                Rule::unique('productos', 'clave')->ignore($producto?->id),
            ],
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'categoria'     => ['required', Rule::in(Producto::CATEGORIAS)],
            'unidad_medida' => 'required|string|max:30',
            'stock_minimo'  => 'required|integer|min:0',
            'activo'        => 'boolean',
        ]);
    }

    private function formData(): array
    {
        return [
            'categorias' => Producto::CATEGORIAS,
        ];
    }
}
