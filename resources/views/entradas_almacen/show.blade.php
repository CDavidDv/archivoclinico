@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Entrada de Almacén #{{ $entrada->id }}</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">Datos Generales</div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $entrada->fecha->format('d/m/Y') }}</dd>

                <dt class="col-sm-3">Tipo</dt>
                <dd class="col-sm-9">{{ ucfirst($entrada->tipo) }}</dd>

                <dt class="col-sm-3">Proveedor</dt>
                <dd class="col-sm-9">{{ $entrada->proveedor?->nombre ?? '—' }}</dd>

                <dt class="col-sm-3">Registró</dt>
                <dd class="col-sm-9">{{ $entrada->usuario?->nombre_usuario }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $entrada->observaciones ?? '—' }}</dd>
            </dl>
        </div>
        <div class="card-footer bg-white">
            <a href="{{ route('entradas_almacen.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Renglones</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrada->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->clave }} — {{ $detalle->producto->nombre }}</td>
                                <td>{{ $detalle->lote->numero_lote }}</td>
                                <td>{{ $detalle->lote->caducidad?->format('d/m/Y') ?? 'No caduca' }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->precio_unitario !== null ? '$' . number_format($detalle->precio_unitario, 2) : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
