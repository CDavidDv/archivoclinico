@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Solicitud {{ $solicitud->folio }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Datos Generales</span>
            @php
                $badge = match($solicitud->estatus) {
                    'pendiente' => 'bg-warning text-dark',
                    'aprobada'  => 'bg-info text-dark',
                    'surtida'   => 'bg-light text-success',
                    'rechazada' => 'bg-secondary',
                    default     => 'bg-secondary',
                };
            @endphp
            <span class="badge {{ $badge }}">{{ ucfirst($solicitud->estatus) }}</span>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Módulo solicitante</dt>
                <dd class="col-sm-9">{{ ucfirst($solicitud->modulo_solicitante) }}</dd>

                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Solicitó</dt>
                <dd class="col-sm-9">{{ $solicitud->usuarioSolicita?->nombre_usuario }}</dd>

                <dt class="col-sm-3">Atendió</dt>
                <dd class="col-sm-9">{{ $solicitud->usuarioAtiende?->nombre_usuario ?? '—' }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $solicitud->observaciones ?? '—' }}</dd>

                @if($solicitud->motivo_rechazo)
                    <dt class="col-sm-3">Motivo de rechazo</dt>
                    <dd class="col-sm-9 text-danger">{{ $solicitud->motivo_rechazo }}</dd>
                @endif
            </dl>
        </div>

        <div class="card-footer bg-white d-flex gap-2 flex-wrap">

            @if(auth()->user()->hasRol('administrador', 'almacen'))

                @if($solicitud->puedeAprobarse())
                    <form method="POST" action="{{ route('solicitudes.aprobar', $solicitud) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                    </form>

                    <button type="button" class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="collapse" data-bs-target="#formRechazo">
                        Rechazar
                    </button>
                @endif

                @if($solicitud->puedeSurtirse())
                    <a href="{{ route('transferencias.create', ['solicitud' => $solicitud->id]) }}"
                       class="btn btn-success btn-sm">
                        Surtir (crear transferencia)
                    </a>
                @endif

            @endif

            <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>

        @if(auth()->user()->hasRol('administrador', 'almacen') && $solicitud->puedeRechazarse())
            <div class="collapse" id="formRechazo">
                <div class="card-body border-top">
                    <form method="POST" action="{{ route('solicitudes.rechazar', $solicitud) }}" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="motivo_rechazo" class="form-control @error('motivo_rechazo') is-invalid @enderror"
                               placeholder="Motivo del rechazo" maxlength="255" required>
                        <button type="submit" class="btn btn-danger">Confirmar rechazo</button>
                    </form>
                    @error('motivo_rechazo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Productos Solicitados</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Producto</th>
                            <th>Solicitado</th>
                            <th>Surtido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitud->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->clave }} — {{ $detalle->producto->nombre }}</td>
                                <td>{{ $detalle->cantidad_solicitada }}</td>
                                <td>{{ $detalle->cantidad_surtida }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($solicitud->transferencias->isNotEmpty())
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white fw-semibold">Transferencias Relacionadas</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($solicitud->transferencias as $transferencia)
                        <li>
                            @if(auth()->user()->hasRol('administrador', 'almacen'))
                                <a href="{{ route('transferencias.show', $transferencia) }}">
                                    {{ $transferencia->folio }}
                                </a>
                            @else
                                {{ $transferencia->folio }}
                            @endif
                            — {{ $transferencia->fecha->format('d/m/Y') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
