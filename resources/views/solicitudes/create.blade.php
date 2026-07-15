@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nueva Solicitud de Abastecimiento</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Solicitar al Almacén — módulo {{ ucfirst($modulo) }}
        </div>
        <div class="card-body">

            @if($errors->has('detalles'))
                <div class="alert alert-danger">{{ $errors->first('detalles') }}</div>
            @endif

            <form method="POST" action="{{ route('solicitudes.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2"
                              class="form-control">{{ old('observaciones') }}</textarea>
                </div>

                <h5 class="text-success fw-semibold">Productos Solicitados</h5>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaDetalles">
                        <thead class="bg-success text-white">
                            <tr>
                                <th style="min-width: 280px;">Producto</th>
                                <th>Cantidad solicitada</th>
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
                    <button type="submit" class="btn btn-success">Enviar Solicitud</button>
                    <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const productos = @json($productos->map(fn ($p) => ['id' => $p->id, 'texto' => $p->clave . ' — ' . $p->nombre]));
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
            <td><input type="number" name="detalles[${renglon}][cantidad_solicitada]" class="form-control form-control-sm" min="1" required></td>
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
