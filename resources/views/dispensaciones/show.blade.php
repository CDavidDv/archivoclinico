@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Dispensación #{{ $dispensacion->id }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Datos Generales</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Receta</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('recetas.show', $dispensacion->receta) }}">
                        {{ $dispensacion->receta->folio }}
                    </a>
                </dd>

                <dt class="col-sm-3">Derechohabiente</dt>
                <dd class="col-sm-9">
                    {{ $dispensacion->receta->derechoHabiente->nombre }}
                    {{ $dispensacion->receta->derechoHabiente->apellido_paterno }}
                </dd>

                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $dispensacion->fecha->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Dispensó</dt>
                <dd class="col-sm-9">{{ $dispensacion->usuario?->nombre_usuario }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $dispensacion->observaciones ?? '—' }}</dd>
            </dl>
        </div>
        <div class="card-footer bg-white">
            <a href="{{ route('dispensaciones.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Medicamentos Dispensados</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Medicamento</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dispensacion->detalles as $detalle)
                            <tr>
                                <td>
                                    {{ $detalle->detalleReceta->medicamento->clave }}
                                    — {{ $detalle->detalleReceta->medicamento->nombre }}
                                </td>
                                <td>{{ $detalle->lote->numero_lote }}</td>
                                <td>{{ $detalle->lote->caducidad->format('d/m/Y') }}</td>
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
