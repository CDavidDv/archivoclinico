@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Editar Usuario</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            {{ $usuario->nombre_usuario }}
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                @include('usuarios._form')

                <p class="text-muted small">
                    Deja la contraseña en blanco para mantener la actual.
                </p>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
