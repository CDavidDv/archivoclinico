<?php


namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DerechoHabiente;
use App\Http\Concerns\FiltraConsulta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DerechoHabienteController extends Controller
{
    use FiltraConsulta;

    public function index(Request $request) {
        $query = DerechoHabiente::query()->orderBy('clave_generada');

        $filtros = $this->aplicarFiltros($query, $request, [
            'clave_generada' => 'like',
            'nombre'         => fn ($q, $v) => $q->where(fn ($s) => $s
                ->where('nombre', 'like', "%{$v}%")
                ->orWhere('apellido_paterno', 'like', "%{$v}%")
                ->orWhere('apellido_materno', 'like', "%{$v}%")),
            'rfc'            => 'like',
            'nss'            => 'like',
            'genero'         => 'like',
        ]);

        $derechoHabientes = $query->paginate(20)->withQueryString();

        return Inertia::render('DerechoHabientes/Index', compact('derechoHabientes', 'filtros'));
    }

    public function create() {
        return Inertia::render('DerechoHabientes/Create');
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
        return Inertia::render('DerechoHabientes/Show', compact('dh'));
    }

    public function edit($id) {
        $dh = DerechoHabiente::findOrFail($id);
        return Inertia::render('DerechoHabientes/Edit', compact('dh'));
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
