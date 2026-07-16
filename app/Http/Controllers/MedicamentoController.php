<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MedicamentoController extends Controller
{
    public function index()
    {
        $medicamentos = Medicamento::conStockTotal()
            ->orderBy('clave')
            ->get();

        return Inertia::render('Medicamentos/Index', compact('medicamentos'));
    }

    public function create()
    {
        return Inertia::render('Medicamentos/Create', $this->formData());
    }

    public function store(Request $request)
    {
        Medicamento::create($this->validateData($request));

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento registrado correctamente.');
    }

    public function show(Medicamento $medicamento)
    {
        $medicamento->load([
            'lotes' => fn ($q) => $q->orderBy('caducidad'),
            'producto',
        ]);

        return Inertia::render('Medicamentos/Show', compact('medicamento'));
    }

    public function edit(Medicamento $medicamento)
    {
        return Inertia::render('Medicamentos/Edit', array_merge(
            compact('medicamento'),
            $this->formData($medicamento)
        ));
    }

    public function update(Request $request, Medicamento $medicamento)
    {
        $medicamento->update($this->validateData($request, $medicamento));

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento actualizado correctamente.');
    }

    public function destroy(Medicamento $medicamento)
    {
        if ($medicamento->lotes()->where('cantidad_actual', '>', 0)->exists()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar un medicamento con existencias. Puedes desactivarlo.'
            ]);
        }

        $medicamento->delete();

        return redirect()
            ->route('medicamentos.index')
            ->with('success', 'Medicamento eliminado correctamente.');
    }

    private function validateData(Request $request, ?Medicamento $medicamento = null): array
    {
        return $request->validate([
            'clave'            => [
                'required', 'string', 'max:30',
                Rule::unique('medicamentos', 'clave')->ignore($medicamento?->id),
            ],
            'nombre'           => 'required|string|max:255',
            'sustancia_activa' => 'required|string|max:255',
            'presentacion'     => 'required|string|max:100',
            'piezas_por_presentacion' => 'required|integer|min:1',
            'unidad_medida'    => 'required|string|max:30',
            'stock_minimo'     => 'required|integer|min:0',
            'controlado'       => 'boolean',
            'dias_restriccion' => 'required|integer|min:1',
            'id_producto'      => [
                'nullable', 'exists:productos,id',
                Rule::unique('medicamentos', 'id_producto')->ignore($medicamento?->id),
            ],
            'activo'           => 'boolean',
        ]);
    }

    private function formData(?Medicamento $medicamento = null): array
    {
        return [
            'productos' => Producto::activos()
                ->where('categoria', Producto::CATEGORIA_MEDICAMENTO)
                ->where(function ($q) use ($medicamento) {
                    $q->whereDoesntHave('medicamento');

                    if ($medicamento) {
                        $q->orWhere('id', $medicamento->id_producto);
                    }
                })
                ->orderBy('clave')
                ->get(),
        ];
    }
}
