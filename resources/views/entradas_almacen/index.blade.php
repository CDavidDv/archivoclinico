@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Entradas de Almacén</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Historial de Entradas</span>
            <a href="{{ route('entradas_almacen.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nueva Entrada
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Proveedor</th>
                            <th>Renglones</th>
                            <th>Registró</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entradas as $entrada)
                            <tr>
                                <td class="fw-semibold">#{{ $entrada->id }}</td>
                                <td>{{ $entrada->fecha->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($entrada->tipo) }}</td>
                                <td>{{ $entrada->proveedor?->nombre ?? '—' }}</td>
                                <td>{{ $entrada->detalles_count }}</td>
                                <td>{{ $entrada->usuario?->nombre_usuario }}</td>
                                <td>
                                    <a href="{{ route('entradas_almacen.show', $entrada) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay entradas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $entradas->links() }}
        </div>
    </div>
</div>
@endsection
