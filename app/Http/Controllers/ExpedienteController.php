<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Expediente;
use App\Models\DerechoHabiente;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    public function index()
    {
        $expedientes = Expediente::with('derechoHabiente')->get();
        return Inertia::render('Expedientes/Index', compact('expedientes'));
    }

    public function create()
    {
        $derechoHabientes = DerechoHabiente::all();
        return Inertia::render('Expedientes/Create', compact('derechoHabientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required',
            'id_derecho_habiente' => 'required',
            'fecha_creacion' => 'required|date'
        ]);

        Expediente::create($request->all());

        return redirect()->route('expedientes.index')
                         ->with('success', 'Expediente creado correctamente');
    }

    public function show($id)
    {
        $expediente = Expediente::with(['derechoHabiente','documentos','prestamos'])
                                ->findOrFail($id);

        return Inertia::render('Expedientes/Show', compact('expediente'));
    }

    public function edit($id)
    {
        $expediente = Expediente::findOrFail($id);
        $derechoHabientes = DerechoHabiente::all();

        return Inertia::render('Expedientes/Edit', compact('expediente','derechoHabientes'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required',
            'id_derecho_habiente' => 'required',
            'fecha_creacion' => 'required|date'
        ]);

        $expedientes = Expediente::findOrFail($id);
        $expedientes->update($request->all());

        return redirect()->route('expedientes.index')
                         ->with('success', 'Expediente actualizado correctamente');
    }

    public function destroy($id)
    {
        Expediente::destroy($id);

        return redirect()->route('expedientes.index')
                         ->with('success', 'Expediente eliminado correctamente');
    }
}
