<?php


namespace App\Http\Controllers;

use App\Models\DerechoHabiente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DerechoHabienteController extends Controller
{
    public function index() {
        $derechoHabientes = DerechoHabiente::all();
        return view('derecho_habientes.index', compact('derechoHabientes'));
    }

    public function create() {
        return view('derecho_habientes.create');
    }

   

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',

            'rfc' => [
                'required',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
            ],

            'nss' => 'required|numeric|digits_between:8,12|unique:derecho_habientes,nss',

            'fecha_nacimiento' => 'required|date|before:today',

            'genero' => ['required', Rule::in(['Masculino','Femenino'])],

            'clave_identificacion' => 'required|digits:2',
            'sintomas' => 'nullable|string',
            'tratamiento' => 'nullable|string',
        ]);

        // Normalizar RFC
        $rfc = strtoupper($request->rfc);

        // 🔴 Validación única SOLO por persona
        $existe = DerechoHabiente::where([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ])->exists();

        if ($existe) {
            return back()
                ->withErrors(['nombre' => 'Esta persona ya está registrada en el sistema'])
                ->withInput();
        }

        // Generar clave (aunque pueda repetirse)
        $claveCompleta = DerechoHabiente::generarClaveCompleta(
            $rfc,
            $request->clave_identificacion
        );

        // Crear registro
        $derechoHabiente = DerechoHabiente::create([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'rfc' => $rfc,
            'nss' => $request->nss,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'clave_identificacion' => $request->clave_identificacion,
            'clave_generada' => $claveCompleta,
            'sintomas' =>$request->sintomas,
            'tratamiento' =>$request->tratamiento,
        ]);

        return redirect()
            ->route('derecho_habientes.show', $derechoHabiente->id)
            ->with('success', 'Derechohabiente registrado correctamente');
    }



    public function show($id) {
        $dh = DerechoHabiente::findOrFail($id);
        return view('derecho_habientes.show', compact('dh'));
    }

    public function edit($id) {
        $dh = DerechoHabiente::findOrFail($id);
        return view('derecho_habientes.edit', compact('dh'));
    }



    public function update(Request $request, $id)
    {
        $dh = DerechoHabiente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',

            'rfc' => [
                'required',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
            ],

            'nss' => 'required|numeric|digits_between:8,12|unique:derecho_habientes,nss',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => ['required', Rule::in(['Masculino','Femenino'])],
            'clave_identificacion' => 'required|digits:2',
            'sintomas' => 'nullable|string',
            'tratamiento' => 'nullable|string',
        ]);

        // Normalizar RFC
        $rfc = strtoupper($request->rfc);

        // 🔒 Validar duplicado EXCLUYENDO el registro actual
        $existe = DerechoHabiente::where('nombre', $request->nombre)
            ->where('apellido_paterno', $request->apellido_paterno)
            ->where('apellido_materno', $request->apellido_materno)
            ->where('fecha_nacimiento', $request->fecha_nacimiento)
            ->where('id', '!=', $dh->id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['nombre' => 'Ya existe un derechohabiente con esos datos'])
                ->withInput();
        }

        $existenss =DerechoHabiente::where('nss', $request->nss)
            ->where('id', '!=', $dh->id)
            ->exists();

        if ($existenss){
            return back()
                ->withErrors(['nss' => 'Ya existe un derechohabiente con nss'])
                ->withInput();
        }

        // Regenerar clave automáticamente
        $claveCompleta = DerechoHabiente::generarClaveCompleta(
            $rfc,
            $request->clave_identificacion
        );

        // Actualizar registro
        $dh->update([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'rfc' => $rfc,
            'nss' => $request->nss,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'clave_identificacion' => $request->clave_identificacion,
            'clave_generada' => $claveCompleta,
            'sintomas' =>$request->sintomas,
            'tratamiento' =>$request->tratamiento,
        ]);

        return redirect()
            ->route('derecho_habientes.show', $dh->id)
            ->with('success', 'Derechohabiente actualizado correctamente');
    }


    public function destroy($id) {
        DerechoHabiente::destroy($id);
        return redirect()->route('derecho_habientes.index');
    }
}
