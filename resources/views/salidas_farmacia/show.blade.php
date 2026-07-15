@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Salida de Farmacia #{{ $salida->id }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Datos Generales</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $salida->fecha->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Tipo</dt>
                <dd class="col-sm-9">{{ ucfirst($salida->tipo) }}</dd>

                <dt class="col-sm-3">Registró</dt>
                <dd class="col-sm-9">{{ $salida->usuario?->nombre_usuario }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $salida->observaciones ?? '—' }}</dd>
            </dl>
        </div>
        <div class="card-footer bg-white">
            <a href="{{ route('salidas_farmacia.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Renglones</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Medicamento</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salida->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->medicamento->clave }} — {{ $detalle->medicamento->nombre }}</td>
                                <td>{{ $detalle->lote->numero_lote }}</td>
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
