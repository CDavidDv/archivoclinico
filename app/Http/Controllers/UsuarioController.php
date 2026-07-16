<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /* =====================================================
       LISTADO
    ===================================================== */

    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = Usuario::query()->orderBy('nombre_usuario');

        $filtros = $this->aplicarFiltros($query, $request, [
            'nombre_usuario' => 'like',
            'email'          => 'like',
            'telefono'       => 'like',
            'rol'            => 'exact',
        ]);

        $usuarios = $query->paginate(20)->withQueryString();

        return Inertia::render('Usuarios/Index', compact('usuarios', 'filtros'));
    }

    /* =====================================================
       CREAR
    ===================================================== */

    public function create()
    {
        return Inertia::render('Usuarios/Create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $usuario = Usuario::create($validated);
        $usuario->syncRoles([$validated['rol']]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    /* =====================================================
       MOSTRAR
    ===================================================== */

    public function show(Usuario $usuario)
    {
        return Inertia::render('Usuarios/Show', compact('usuario'));
    }

    /* =====================================================
       EDITAR
    ===================================================== */

    public function edit(Usuario $usuario)
    {
        return Inertia::render('Usuarios/Edit', array_merge(
            compact('usuario'),
            $this->formData()
        ));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $this->validateData($request, $usuario);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $usuario->update($validated);
        $usuario->syncRoles([$validated['rol']]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /* =====================================================
       ELIMINAR
    ===================================================== */

    public function destroy(Usuario $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->withErrors([
                'error' => 'No puedes eliminar tu propio usuario.'
            ]);
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    /* =====================================================
       MÉTODOS PRIVADOS
    ===================================================== */

    private function validateData(Request $request, ?Usuario $usuario = null): array
    {
        return $request->validate([
            'nombre_usuario' => [
                'required', 'string', 'max:255',
                Rule::unique('usuarios', 'nombre_usuario')->ignore($usuario?->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario?->id),
            ],
            'telefono' => 'nullable|string|max:20',
            'password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'rol'      => ['required', Rule::in(Usuario::ROLES)],
        ]);
    }

    private function formData(): array
    {
        return [
            'roles' => Usuario::ROLES,
        ];
    }
}
