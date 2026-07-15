@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Transferencias</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Historial de Transferencias</span>
            <a href="{{ route('transferencias.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nueva Transferencia
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Destino</th>
                            <th>Solicitud</th>
                            <th>Renglones</th>
                            <th>Registró</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transferencias as $transferencia)
                            <tr>
                                <td class="fw-semibold">{{ $transferencia->folio }}</td>
                                <td>{{ $transferencia->fecha->format('d/m/Y') }}</td>
                                <td>
                                    {{ $transferencia->destino === 'farmacia'
                                        ? 'Farmacia'
                                        : $transferencia->area_destino }}
                                </td>
                                <td>{{ $transferencia->solicitud?->folio ?? '—' }}</td>
                                <td>{{ $transferencia->detalles_count }}</td>
                                <td>{{ $transferencia->usuario?->nombre_usuario }}</td>
                                <td>
                                    <a href="{{ route('transferencias.show', $transferencia) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay transferencias registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $transferencias->links() }}
        </div>
    </div>
</div>
@endsection
