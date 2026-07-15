@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nueva Salida de Farmacia</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Registrar Salida</div>
        <div class="card-body">

            @if($errors->has('detalles'))
                <div class="alert alert-danger">{{ $errors->first('detalles') }}</div>
            @endif

            <form method="POST" action="{{ route('salidas_farmacia.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select name="tipo" id="tipo"
                                class="form-select @error('tipo') is-invalid @enderror" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" @selected(old('tipo') === $tipo)>
                                    {{ ucfirst($tipo) }}
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
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <input type="text" name="observaciones" id="observaciones"
                               value="{{ old('observaciones') }}" class="form-control">
                    </div>
                </div>

                <h5 class="text-success fw-semibold">Renglones (por lote)</h5>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaDetalles">
                        <thead class="bg-success text-white">
                            <tr>
                                <th style="min-width: 320px;">Lote (medicamento — lote — caducidad — existencia)</th>
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
                    <a href="{{ route('salidas_farmacia.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const lotes = @json($lotes->map(fn ($l) => [
        'id' => $l->id,
        'texto' => $l->medicamento->clave . ' — ' . $l->numero_lote
            . ' — cad. ' . $l->caducidad->format('d/m/Y')
            . ' — ' . $l->cantidad_actual . ' pzas'
    ]));
    let renglon = 0;

    function agregarRenglon() {
        const tbody = document.querySelector('#tablaDetalles tbody');
        const tr = document.createElement('tr');

        const opciones = lotes
            .map(l => `<option value="${l.id}">${l.texto}</option>`)
            .join('');

        tr.innerHTML = `
            <td>
                <select name="detalles[${renglon}][id_lote_farmacia]" class="form-select form-select-sm" required>
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
