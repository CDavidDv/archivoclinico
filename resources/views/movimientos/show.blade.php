@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle del Movimiento</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <p><strong>Usuario:</strong> {{ $movimiento->usuario->nombre ?? 'N/A' }}</p>
            <p><strong>Acción:</strong> {{ ucfirst($movimiento->accion) }}</p>
            <p><strong>Tabla Afectada:</strong> {{ $movimiento->tabla_afectada }}</p>
            <p><strong>ID Registro:</strong> {{ $movimiento->id_registro_afectado ?? '-' }}</p>
            <p><strong>Fecha:</strong>
                {{ \Carbon\Carbon::parse($movimiento->fecha_accion)->format('d/m/Y H:i') }}
            </p>

        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('movimientos.edit', $movimiento->id) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection