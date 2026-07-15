@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Medicamentos</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Catálogo de Medicamentos</span>
            <a href="{{ route('medicamentos.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nuevo Medicamento
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Sustancia activa</th>
                            <th>Presentación</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th width="220">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicamentos as $medicamento)
                            <tr>
                                <td class="fw-semibold">{{ $medicamento->clave }}</td>
                                <td>{{ $medicamento->nombre }}</td>
                                <td>{{ $medicamento->sustancia_activa }}</td>
                                <td>{{ $medicamento->presentacion }}</td>
                                <td>
                                    <span class="badge {{ ($medicamento->stock_total ?? 0) <= $medicamento->stock_minimo ? 'bg-danger' : 'bg-success' }}">
                                        {{ $medicamento->stock_total ?? 0 }}
                                    </span>
                                </td>
                                <td>{{ $medicamento->stock_minimo }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('medicamentos.show', $medicamento) }}" class="btn btn-outline-success btn-sm">Ver</a>
                                    <a href="{{ route('medicamentos.edit', $medicamento) }}" class="btn btn-outline-warning btn-sm">Editar</a>
                                    <form action="{{ route('medicamentos.destroy', $medicamento) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este medicamento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay medicamentos registrados.
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
