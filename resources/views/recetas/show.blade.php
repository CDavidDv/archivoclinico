@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Receta {{ $receta->folio }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Datos Generales</span>
            @php
                $badge = match($receta->estatus) {
                    'pendiente' => 'bg-warning text-dark',
                    'parcial'   => 'bg-info text-dark',
                    'surtida'   => 'bg-light text-success',
                    'cancelada' => 'bg-secondary',
                    default     => 'bg-secondary',
                };
            @endphp
            <span class="badge {{ $badge }}">{{ ucfirst($receta->estatus) }}</span>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Derechohabiente</dt>
                <dd class="col-sm-9">
                    {{ $receta->derechoHabiente->nombre }}
                    {{ $receta->derechoHabiente->apellido_paterno }}
                    {{ $receta->derechoHabiente->apellido_materno }}
                    (NSS: {{ $receta->derechoHabiente->nss }})
                </dd>

                <dt class="col-sm-3">Médico</dt>
                <dd class="col-sm-9">{{ $receta->medico->nombre }} {{ $receta->medico->apellido_paterno }}</dd>

                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $receta->fecha_receta->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Diagnóstico</dt>
                <dd class="col-sm-9">{{ $receta->diagnostico ?? '—' }}</dd>

                <dt class="col-sm-3">Indicaciones</dt>
                <dd class="col-sm-9">{{ $receta->indicaciones ?? '—' }}</dd>

                <dt class="col-sm-3">Capturó</dt>
                <dd class="col-sm-9">{{ $receta->usuario?->nombre_usuario }}</dd>
            </dl>
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            @if(auth()->user()->hasRol('administrador', 'farmacia') && $receta->puedeDispensarse())
                <a href="{{ route('dispensaciones.create', $receta) }}" class="btn btn-success btn-sm">
                    Dispensar
                </a>
            @endif

            @if(auth()->user()->hasRol('administrador', 'medico') && $receta->puedeCancelarse())
                <form method="POST" action="{{ route('recetas.cancelar', $receta) }}"
                      onsubmit="return confirm('¿Cancelar esta receta?');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Cancelar Receta</button>
                </form>
            @endif

            <a href="{{ route('recetas.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Medicamentos Prescritos</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Medicamento</th>
                            <th>Dosis</th>
                            <th>Prescrito</th>
                            <th>Surtido</th>
                            <th>Pendiente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->medicamento->clave }} — {{ $detalle->medicamento->nombre }}</td>
                                <td>{{ $detalle->dosis ?? '—' }}</td>
                                <td>{{ $detalle->cantidad_prescrita }}</td>
                                <td>{{ $detalle->cantidad_surtida }}</td>
                                <td>{{ $detalle->cantidadPendiente() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($receta->dispensaciones->isNotEmpty())
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white fw-semibold">Dispensaciones</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Dispensó</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receta->dispensaciones as $dispensacion)
                                <tr>
                                    <td>
                                        @if(auth()->user()->hasRol('administrador', 'farmacia'))
                                            <a href="{{ route('dispensaciones.show', $dispensacion) }}">
                                                #{{ $dispensacion->id }}
                                            </a>
                                        @else
                                            #{{ $dispensacion->id }}
                                        @endif
                                    </td>
                                    <td>{{ $dispensacion->fecha->format('d/m/Y H:i') }}</td>
                                    <td>{{ $dispensacion->usuario?->nombre_usuario }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
