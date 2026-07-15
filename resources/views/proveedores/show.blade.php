@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Detalle de Proveedor</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">{{ $proveedor->nombre }}</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">RFC</dt>
                <dd class="col-sm-9">{{ $proveedor->rfc ?? '—' }}</dd>

                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $proveedor->telefono ?? '—' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $proveedor->email ?? '—' }}</dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9">{{ $proveedor->direccion ?? '—' }}</dd>

                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $proveedor->activo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </dd>
            </dl>
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-success btn-sm">Editar</a>
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Entradas Registradas</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedor->entradas as $entrada)
                            <tr>
                                <td>
                                    <a href="{{ route('entradas_almacen.show', $entrada) }}">
                                        #{{ $entrada->id }}
                                    </a>
                                </td>
                                <td>{{ $entrada->fecha->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($entrada->tipo) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Sin entradas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
