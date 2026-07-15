@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Transferencia {{ $transferencia->folio }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Datos Generales</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $transferencia->fecha->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Destino</dt>
                <dd class="col-sm-9">
                    {{ $transferencia->destino === 'farmacia'
                        ? 'Farmacia'
                        : $transferencia->area_destino }}
                </dd>

                <dt class="col-sm-3">Solicitud</dt>
                <dd class="col-sm-9">
                    @if($transferencia->solicitud)
                        <a href="{{ route('solicitudes.show', $transferencia->solicitud) }}">
                            {{ $transferencia->solicitud->folio }}
                        </a>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Registró</dt>
                <dd class="col-sm-9">{{ $transferencia->usuario?->nombre_usuario }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $transferencia->observaciones ?? '—' }}</dd>

                @if($transferencia->entradaFarmacia)
                    <dt class="col-sm-3">Entrada en farmacia</dt>
                    <dd class="col-sm-9">#{{ $transferencia->entradaFarmacia->id }} (automática)</dd>
                @endif
            </dl>
        </div>
        <div class="card-footer bg-white">
            <a href="{{ route('transferencias.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Renglones (lotes descontados)</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transferencia->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->clave }} — {{ $detalle->producto->nombre }}</td>
                                <td>{{ $detalle->lote->numero_lote }}</td>
                                <td>{{ $detalle->lote->caducidad?->format('d/m/Y') ?? 'No caduca' }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
