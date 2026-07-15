@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Salidas de Almacén</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Historial de Salidas</span>
            <a href="{{ route('salidas_almacen.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nueva Salida
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
                            <th>Área destino</th>
                            <th>Renglones</th>
                            <th>Registró</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salidas as $salida)
                            <tr>
                                <td class="fw-semibold">#{{ $salida->id }}</td>
                                <td>{{ $salida->fecha->format('d/m/Y') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $salida->tipo)) }}</td>
                                <td>{{ $salida->area_destino ?? '—' }}</td>
                                <td>{{ $salida->detalles_count }}</td>
                                <td>{{ $salida->usuario?->nombre_usuario }}</td>
                                <td>
                                    <a href="{{ route('salidas_almacen.show', $salida) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay salidas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $salidas->links() }}
        </div>
    </div>
</div>
@endsection
