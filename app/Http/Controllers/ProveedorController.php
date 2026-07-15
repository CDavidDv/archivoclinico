<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
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

        return view('proveedores.show', ['proveedor' => $proveedore]);
    }

    public function edit(Proveedor $proveedore)
    {
        return view('proveedores.edit', ['proveedor' => $proveedore]);
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
