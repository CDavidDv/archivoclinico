<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\PersonalArchivo;
use Illuminate\Http\Request;

class PersonalArchivoController extends Controller
{
    public function index()
    {
        $personalArchivos = PersonalArchivo::all();
        return Inertia::render('PersonalArchivos/Index', compact('personalArchivos'));
    }

    public function create()
    {
        return Inertia::render('PersonalArchivos/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'rfc' => 'required|unique:personal_archivos,rfc',
            'numero_empleado' => 'required|unique:personal_archivos,numero_empleado',
            'cargo' => 'required|string|max:150',
            'area' => 'required|string|max:150',
            'tipo' => 'required',
        ]);

        PersonalArchivo::create($request->all());

        return redirect()->route('personal_archivos.index')
            ->with('success', 'Personal de Archivo registrado correctamente.');
    }

    public function show($id)
    {
        $personalArchivo = PersonalArchivo::findOrFail($id);
        return Inertia::render('PersonalArchivos/Show', compact('personalArchivo'));
    }

    public function edit($id)
    {
        $personalArchivo = PersonalArchivo::findOrFail($id);
        return Inertia::render('PersonalArchivos/Edit', compact('personalArchivo'));
    }

    public function update(Request $request, $id)
    {
        $personalArchivo = PersonalArchivo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'rfc' => 'required|unique:personal_archivos,rfc,' . $personalArchivo->id,
            'numero_empleado' => 'required|unique:personal_archivos,numero_empleado,' . $personalArchivo->id,
            'cargo' => 'required|string|max:150',
            'area' => 'required|string|max:150',
            'tipo' => 'required',
        ]);

        $personalArchivo->update($request->all());

        return redirect()->route('personal_archivos.index')
            ->with('success', 'Personal de Archivo actualizado correctamente.');
    }

    public function destroy($id)
    {
        PersonalArchivo::destroy($id);

        return redirect()->route('personal_archivos.index')
            ->with('success', 'Personal eliminado correctamente.');
    }
}