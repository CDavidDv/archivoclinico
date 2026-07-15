@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Detalle de Usuario</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            {{ $usuario->nombre_usuario }}
        </div>

        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nombre de usuario</dt>
                <dd class="col-sm-9">{{ $usuario->nombre_usuario }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $usuario->email }}</dd>

                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $usuario->telefono ?? '—' }}</dd>

                <dt class="col-sm-3">Rol</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-dark">{{ ucfirst($usuario->rol) }}</span>
                </dd>

                <dt class="col-sm-3">Registrado</dt>
                <dd class="col-sm-9">{{ $usuario->created_at?->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>

        <div class="card-footer bg-white d-flex gap-2">
            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-success btn-sm">Editar</a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>
</div>
@endsection
