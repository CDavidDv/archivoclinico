@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nueva Transferencia</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Registrar Transferencia
            @if($solicitud)
                — surtiendo solicitud {{ $solicitud->folio }}
            @endif
        </div>
        <div class="card-body">

            @if($errors->has('detalles') || $errors->has('id_solicitud'))
                <div class="alert alert-danger">
                    {{ $errors->first('detalles') ?: $errors->first('id_solicitud') }}
                </div>
            @endif

            <p class="text-muted small">
                El descuento de lotes del almacén se hace automáticamente (FEFO).
                Si el destino es Farmacia, el stock entra a los lotes de farmacia con el mismo
                número de lote y caducidad; el producto debe tener medicamento vinculado.
            </p>

            <form method="POST" action="{{ route('transferencias.store') }}">
                @csrf

                @if($solicitud)
                    <input type="hidden" name="id_solicitud" value="{{ $solicitud->id }}">
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="destino" class="form-label">Destino</label>
                        <select name="destino" id="destino"
                                class="form-select @error('destino') is-invalid @enderror" required>
                            <option value="farmacia"
                                @selected(old('destino', $solicitud?->modulo_solicitante === 'farmacia' ? 'farmacia' : null) === 'farmacia')>
                                Farmacia
                            </option>
                            <option value="area"
                                @selected(old('destino', $solicitud?->modulo_solicitante === 'archivo' ? 'area' : null) === 'area')>
                                Otra área
                            </option>
                        </select>
                        @error('destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="area_destino" class="form-label">Área destino (si aplica)</label>
                        <input type="text" name="area_destino" id="area_destino" maxlength="150"
                               value="{{ old('area_destino', $solicitud?->modulo_solicitante === 'archivo' ? 'Archivo Clínico' : '') }}"
                               class="form-control @error('area_destino') is-invalid @enderror">
                        @error('area_destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha"
                               value="{{ old('fecha', now()->toDateString()) }}"
                               class="form-control @error('fecha') is-invalid @enderror" required>
                        @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="2"
                                  class="form-control">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <h5 class="text-success fw-semibold">Productos</h5>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaDetalles">
                        <thead class="bg-success text-white">
                            <tr>
                                <th style="min-width: 280px;">Producto (stock disponible)</th>
                                <th>Cantidad</th>
                                <th width="60"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-outline-success btn-sm mb-3" id="agregarRenglon">
                    + Agregar producto
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Registrar Transferencia</button>
                    <a href="{{ route('transferencias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

@php
    $productosJs = $productos->map(fn ($p) => [
        'id' => $p->id,
        'texto' => $p->clave . ' — ' . $p->nombre . ' (' . ($p->stock_total ?? 0) . ')',
    ])->values();

    $precargaJs = $solicitud?->detalles->map(fn ($d) => [
        'id_producto' => $d->id_producto,
        'cantidad'    => max(0, $d->cantidad_solicitada - $d->cantidad_surtida),
    ])->values() ?? [];
@endphp

@push('scripts')
<script>
    const productos = @json($productosJs);

    const precarga = @json($precargaJs);

    let renglon = 0;

    function agregarRenglon(idProducto = '', cantidad = '') {
        const tbody = document.querySelector('#tablaDetalles tbody');
        const tr = document.createElement('tr');

        const opciones = productos
            .map(p => `<option value="${p.id}" ${p.id == idProducto ? 'selected' : ''}>${p.texto}</option>`)
            .join('');

        tr.innerHTML = `
            <td>
                <select name="detalles[${renglon}][id_producto]" class="form-select form-select-sm" required>
                    <option value="">Selecciona...</option>
                    ${opciones}
                </select>
            </td>
            <td><input type="number" name="detalles[${renglon}][cantidad]" class="form-control form-control-sm" min="1" value="${cantidad}" required></td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">×</button>
            </td>
        `;

        tbody.appendChild(tr);
        renglon++;
    }

    document.getElementById('agregarRenglon').addEventListener('click', () => agregarRenglon());

    if (precarga.length > 0) {
        precarga.forEach(d => agregarRenglon(d.id_producto, d.cantidad));
    } else {
        agregarRenglon();
    }
</script>
@endpush
@endsection
