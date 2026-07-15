@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nueva Salida de Almacén</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Registrar Salida</div>
        <div class="card-body">

            @if($errors->has('detalles'))
                <div class="alert alert-danger">{{ $errors->first('detalles') }}</div>
            @endif

            <form method="POST" action="{{ route('salidas_almacen.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select name="tipo" id="tipo"
                                class="form-select @error('tipo') is-invalid @enderror" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" @selected(old('tipo') === $tipo)>
                                    {{ ucfirst(str_replace('_', ' ', $tipo)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha"
                               value="{{ old('fecha', now()->toDateString()) }}"
                               class="form-control @error('fecha') is-invalid @enderror" required>
                        @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="area_destino" class="form-label">Área destino</label>
                        <input type="text" name="area_destino" id="area_destino" maxlength="150"
                               value="{{ old('area_destino') }}"
                               class="form-control @error('area_destino') is-invalid @enderror">
                        @error('area_destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="2"
                                  class="form-control">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                <h5 class="text-success fw-semibold">Renglones</h5>
                <p class="text-muted small">El descuento de lotes se hace automáticamente (FEFO: primero lo que caduca antes).</p>

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
                    + Agregar renglón
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Registrar Salida</button>
                    <a href="{{ route('salidas_almacen.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const productos = @json($productos->map(fn ($p) => [
        'id' => $p->id,
        'texto' => $p->clave . ' — ' . $p->nombre . ' (' . ($p->stock_total ?? 0) . ')'
    ]));
    let renglon = 0;

    function agregarRenglon() {
        const tbody = document.querySelector('#tablaDetalles tbody');
        const tr = document.createElement('tr');

        const opciones = productos
            .map(p => `<option value="${p.id}">${p.texto}</option>`)
            .join('');

        tr.innerHTML = `
            <td>
                <select name="detalles[${renglon}][id_producto]" class="form-select form-select-sm" required>
                    <option value="">Selecciona...</option>
                    ${opciones}
                </select>
            </td>
            <td><input type="number" name="detalles[${renglon}][cantidad]" class="form-control form-control-sm" min="1" required></td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">×</button>
            </td>
        `;

        tbody.appendChild(tr);
        renglon++;
    }

    document.getElementById('agregarRenglon').addEventListener('click', agregarRenglon);
    agregarRenglon();
</script>
@endpush
@endsection
