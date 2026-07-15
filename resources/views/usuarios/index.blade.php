@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Gestión de Usuarios
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Listado de Usuarios</span>

            <a href="{{ route('usuarios.create') }}"
               class="btn btn-light btn-sm shadow-sm">
                + Nuevo Usuario
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="bg-success text-white">
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th width="220">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>

                                <td class="fw-semibold">
                                    {{ $usuario->nombre_usuario }}
                                </td>

                                <td>
                                    {{ $usuario->email }}
                                </td>

                                <td>
                                    <span class="badge bg-dark">
                                        {{ ucfirst($usuario->rol) }}
                                    </span>
                                </td>

                                <td class="d-flex gap-2">

                                    <a href="{{ route('usuarios.show',$usuario->id) }}"
                                       class="btn btn-outline-success btn-sm">
                                        Ver
                                    </a>

                                    <a href="{{ route('usuarios.edit',$usuario->id) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('usuarios.destroy',$usuario->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Deseas eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection