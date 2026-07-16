<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Medico;
use App\Http\Concerns\FiltraConsulta;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    use FiltraConsulta;

    public function index(Request $request) {
        $query = Medico::query()->orderBy('numero_empleado');

        $filtros = $this->aplicarFiltros($query, $request, [
            'numero_empleado' => 'like',
            'nombre'          => fn ($q, $v) => $q->where(fn ($s) => $s
                ->where('nombre', 'like', "%{$v}%")
                ->orWhere('apellido_paterno', 'like', "%{$v}%")
                ->orWhere('apellido_materno', 'like', "%{$v}%")),
            'rfc'             => 'like',
            'cargo'           => 'like',
            'area'            => 'like',
            'tipo'            => 'like',
        ]);

        $medicos = $query->paginate(20)->withQueryString();

        return Inertia::render('Medicos/Index', compact('medicos', 'filtros'));
    }

    public function create() {
        return Inertia::render('Medicos/Create');
    }

    public function store(Request $request) {

        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'rfc' => 'required|unique:medicos,rfc',
            'numero_empleado' => 'required|unique:medicos,numero_empleado',
            'cargo' => 'required',
            'area' => 'required',
            'tipo' => 'required',
        ]);

        Medico::create($request->all());

        return redirect()->route('medicos.index')
            ->with('success', 'Médico registrado correctamente.');
    }

    public function show($id) {
        $medico = Medico::with('prestamos')->findOrFail($id);
        return Inertia::render('Medicos/Show', compact('medico'));
    }

    public function edit($id) {
        $medico = Medico::findOrFail($id);
        return Inertia::render('Medicos/Edit', compact('medico'));
    }

    public function update(Request $request, $id) {

        $medico = Medico::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'rfc' => 'required|unique:medicos,rfc,' . $medico->id,
            'numero_empleado' => 'required|unique:medicos,numero_empleado,' . $medico->id,
            'cargo' => 'required',
            'area' => 'required',
            'tipo' => 'required',
        ]);

        $medico->update($request->all());

        return redirect()->route('medicos.index')
            ->with('success', 'Médico actualizado correctamente.');
    }

    public function destroy($id) {
        Medico::destroy($id);
        return redirect()->route('medicos.index')
            ->with('success', 'Médico eliminado correctamente.');
    }
}