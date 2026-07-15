@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Detalle de Medicamento</h1>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-success text-white fw-semibold">
            {{ $medicamento->clave }} — {{ $medicamento->nombre }}
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Sustancia activa</dt>
                <dd class="col-sm-9">{{ $medicamento->sustancia_activa }}</dd>

                <dt class="col-sm-3">Presentación</dt>
                <dd class="col-sm-9">{{ $medicamento->presentacion }}</dd>

                <dt class="col-sm-3">Unidad de medida</dt>
                <dd class="col-sm-9">{{ $medicamento->unidad_medida }}</dd>

                <dt class="col-sm-3">Stock total</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $medicamento->stock_total <= $medicamento->stock_minimo ? 'bg-danger' : 'bg-success' }}">
                        {{ $medicamento->stock_total }}
                    </span>
                    (mínimo: {{ $medicamento->stock_minimo }})
                </dd>

                <dt class="col-sm-3">Producto de almacén</dt>
                <dd class="col-sm-9">
                    {{ $medicamento->producto
                        ? $medicamento->producto->clave . ' — ' . $medicamento->producto->nombre
                        : 'Sin vínculo' }}
                </dd>
            </dl>
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <a href="{{ route('medicamentos.edit', $medicamento) }}" class="btn btn-success btn-sm">Editar</a>
            <a href="{{ route('medicamentos.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Lotes</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Número de lote</th>
                            <th>Caducidad</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicamento->lotes as $lote)
                            <tr class="{{ $lote->isCaducado() && $lote->cantidad_actual > 0 ? 'table-danger' : '' }}">
                                <td>{{ $lote->numero_lote }}</td>
                                <td>
                                    {{ $lote->caducidad->format('d/m/Y') }}
                                    @if($lote->isCaducado() && $lote->cantidad_actual > 0)
                                        <span class="badge bg-danger">Caducado</span>
                                    @endif
                                </td>
                                <td>{{ $lote->cantidad_actual }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Sin lotes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
