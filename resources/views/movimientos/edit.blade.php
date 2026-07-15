@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Movimiento</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('movimientos.update', $movimiento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <select name="id_usuario" class="form-select" required>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}"
                        {{ old('id_usuario', $movimiento->id_usuario) == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Acción</label>
            <input type="text" name="accion" class="form-control"
                value="{{ old('accion', $movimiento->accion) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tabla Afectada</label>
            <input type="text" name="tabla_afectada" class="form-control"
                value="{{ old('tabla_afectada', $movimiento->tabla_afectada) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">ID Registro Afectado</label>
            <input type="number" name="id_registro_afectado" class="form-control"
                value="{{ old('id_registro_afectado', $movimiento->id_registro_afectado) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Acción</label>
            <input type="datetime-local" name="fecha_accion" class="form-control"
                value="{{ old('fecha_accion', \Carbon\Carbon::parse($movimiento->fecha_accion)->format('Y-m-d\TH:i')) }}"
                required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success"
                onclick="return confirm('¿Actualizar movimiento?')">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection