@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nueva Receta</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Registrar Receta</div>
        <div class="card-body">

            @if($errors->has('detalles'))
                <div class="alert alert-danger">{{ $errors->first('detalles') }}</div>
            @endif

            <form method="POST" action="{{ route('recetas.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="id_derecho_habiente" class="form-label">Derechohabiente</label>
                        <select name="id_derecho_habiente" id="id_derecho_habiente"
                                class="form-select @error('id_derecho_habiente') is-invalid @enderror" required>
                            <option value="">Selecciona...</option>
                            @foreach($derechoHabientes as $dh)
                                <option value="{{ $dh->id }}" @selected(old('id_derecho_habiente') == $dh->id)>
                                    {{ $dh->nombre }} {{ $dh->apellido_paterno }} {{ $dh->apellido_materno }} ({{ $dh->nss }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_derecho_habiente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="id_medico" class="form-label">Médico</label>
                        <select name="id_medico" id="id_medico"
                                class="form-select @error('id_medico') is-invalid @enderror" required>
                            <option value="">Selecciona...</option>
                            @foreach($medicos as $medico)
                                <option value="{{ $medico->id }}" @selected(old('id_medico') == $medico->id)>
                                    {{ $medico->nombre }} {{ $medico->apellido_paterno }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_medico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_receta" class="form-label">Fecha</label>
                        <input type="date" name="fecha_receta" id="fecha_receta"
                               value="{{ old('fecha_receta', now()->toDateString()) }}"
                               class="form-control @error('fecha_receta') is-invalid @enderror" required>
                        @error('fecha_receta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="diagnostico" class="form-label">Diagnóstico</label>
                        <input type="text" name="diagnostico" id="diagnostico"
                               value="{{ old('diagnostico') }}"
                               class="form-control @error('diagnostico') is-invalid @enderror">
                        @error('diagnostico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="indicaciones" class="form-label">Indicaciones generales</label>
                        <textarea name="indicaciones" id="indicaciones" rows="1"
                                  class="form-control">{{ old('indicaciones') }}</textarea>
                    </div>
                </div>

                <h5 class="text-success fw-semibold">Medicamentos</h5>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaDetalles">
                        <thead class="bg-success text-white">
                            <tr>
                                <th style="min-width: 280px;">Medicamento</th>
                                <th>Cantidad</th>
                                <th>Dosis / indicación</th>
                                <th width="60"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-outline-success btn-sm mb-3" id="agregarRenglon">
                    + Agregar medicamento
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Registrar Receta</button>
                    <a href="{{ route('recetas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    const medicamentos = @json($medicamentos->map(fn ($m) => ['id' => $m->id, 'texto' => $m->clave . ' — ' . $m->nombre]));
    let renglon = 0;

    function agregarRenglon() {
        const tbody = document.querySelector('#tablaDetalles tbody');
        const tr = document.createElement('tr');

        const opciones = medicamentos
            .map(m => `<option value="${m.id}">${m.texto}</option>`)
            .join('');

        tr.innerHTML = `
            <td>
                <select name="detalles[${renglon}][id_medicamento]" class="form-select form-select-sm" required>
                    <option value="">Selecciona...</option>
                    ${opciones}
                </select>
            </td>
            <td><input type="number" name="detalles[${renglon}][cantidad_prescrita]" class="form-control form-control-sm" min="1" required></td>
            <td><input type="text" name="detalles[${renglon}][dosis]" class="form-control form-control-sm" maxlength="150" placeholder="1 cada 8 horas por 7 días"></td>
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
