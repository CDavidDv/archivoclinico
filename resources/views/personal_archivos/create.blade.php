@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Nuevo Personal de Archivo Clínico
    </h1>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Registro de Personal de Archivo Clínico
        </div>

        <div class="card-body">

            <form action="{{ route('personal_archivos.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text"
                               name="nombre"
                               class="form-control border-success"
                               value="{{ old('nombre') }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text"
                               name="apellido_paterno"
                               class="form-control border-success"
                               value="{{ old('apellido_paterno') }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text"
                               name="apellido_materno"
                               class="form-control border-success"
                               value="{{ old('apellido_materno') }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">RFC</label>
                        <input type="text"
                               name="rfc"
                               class="form-control border-success"
                               value="{{ old('rfc') }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número de Empleado</label>
                        <input type="text"
                               name="numero_empleado"
                               class="form-control border-success"
                               value="{{ old('numero_empleado') }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text"
                               name="cargo"
                               class="form-control border-success"
                               value="{{ old('cargo') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Área</label>
                        <input type="text"
                               name="area"
                               class="form-control border-success"
                               value="{{ old('area') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo"
                                class="form-select border-success"
                                required>
                            <option value="">Seleccione...</option>
                            @foreach(['base','suplente','externo'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ old('tipo') == $tipo ? 'selected' : '' }}>
                                    {{ ucfirst($tipo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('personal_archivos.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">
                        Guardar Personal
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection