<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Http\Concerns\FiltraConsulta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    use FiltraConsulta;

    public function index(Request $request)
    {
        $query = Proveedor::query()->orderBy('nombre');

        $filtros = $this->aplicarFiltros($query, $request, [
            'nombre'   => 'like',
            'rfc'      => 'like',
            'telefono' => 'like',
            'email'    => 'like',
            'activo'   => 'exact',
        ]);

        $proveedores = $query->paginate(20)->withQueryString();

        return Inertia::render('Proveedores/Index', compact('proveedores', 'filtros'));
    }

    public function create()
    {
        return Inertia::render('Proveedores/Create');
    }

    public function store(Request $request)
    {
        Proveedor::create($this->validateData($request));

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function show(Proveedor $proveedore)
    {
        $proveedore->load('entradas');

        return Inertia::render('Proveedores/Show', ['proveedor' => $proveedore]);
    }

    public function edit(Proveedor $proveedore)
    {
        return Inertia::render('Proveedores/Edit', ['proveedor' => $proveedore]);
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $proveedore->update($this->validateData($request, $proveedore));

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedore)
    {
        if ($proveedore->entradas()->exists()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar un proveedor con entradas registradas. Puedes desactivarlo.'
            ]);
        }

        $proveedore->delete();

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }

    private function validateData(Request $request, ?Proveedor $proveedor = null): array
    {
        return $request->validate([
            'nombre'    => 'required|string|max:255',
            'rfc'       => [
                'nullable', 'string', 'max:13',
                Rule::unique('proveedores', 'rfc')->ignore($proveedor?->id),
            ],
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'activo'    => 'boolean',
        ]);
    }
}
