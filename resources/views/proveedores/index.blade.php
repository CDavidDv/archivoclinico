@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Proveedores</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Listado de Proveedores</span>
            <a href="{{ route('proveedores.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nuevo Proveedor
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th width="220">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $proveedor)
                            <tr>
                                <td class="fw-semibold">{{ $proveedor->nombre }}</td>
                                <td>{{ $proveedor->rfc ?? '—' }}</td>
                                <td>{{ $proveedor->telefono ?? '—' }}</td>
                                <td>{{ $proveedor->email ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $proveedor->activo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('proveedores.show', $proveedor) }}" class="btn btn-outline-success btn-sm">Ver</a>
                                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-outline-warning btn-sm">Editar</a>
                                    <form action="{{ route('proveedores.destroy', $proveedor) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este proveedor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay proveedores registrados.
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
