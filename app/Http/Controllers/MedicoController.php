<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index() {
        $medicos = Medico::all();
        return view('medicos.index', compact('medicos'));
    }

    public function create() {
        return view('medicos.create');
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
        return view('medicos.show', compact('medico'));
    }

    public function edit($id) {
        $medico = Medico::findOrFail($id);
        return view('medicos.edit', compact('medico'));
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