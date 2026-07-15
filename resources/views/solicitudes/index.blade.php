@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Solicitudes de Abastecimiento</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Listado de Solicitudes</span>
            @if(auth()->user()->hasRol('administrador', 'farmacia', 'archivo'))
                <a href="{{ route('solicitudes.create') }}" class="btn btn-light btn-sm shadow-sm">
                    + Nueva Solicitud
                </a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Módulo</th>
                            <th>Solicitó</th>
                            <th>Renglones</th>
                            <th>Estatus</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td class="fw-semibold">{{ $solicitud->folio }}</td>
                                <td>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($solicitud->modulo_solicitante) }}</td>
                                <td>{{ $solicitud->usuarioSolicita?->nombre_usuario }}</td>
                                <td>{{ $solicitud->detalles_count }}</td>
                                <td>
                                    @php
                                        $badge = match($solicitud->estatus) {
                                            'pendiente' => 'bg-warning text-dark',
                                            'aprobada'  => 'bg-info text-dark',
                                            'surtida'   => 'bg-success',
                                            'rechazada' => 'bg-secondary',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($solicitud->estatus) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('solicitudes.show', $solicitud) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $solicitudes->links() }}
        </div>
    </div>
</div>
@endsection
