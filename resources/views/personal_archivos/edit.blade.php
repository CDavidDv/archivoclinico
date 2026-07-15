@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Editar Personal de Archivo Clínico
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
            Actualización de Personal de Archivo Clínico
        </div>

        <div class="card-body">

            <form action="{{ route('personal_archivos.update', $personalArchivo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text"
                               name="nombre"
                               class="form-control border-success @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $personalArchivo->nombre) }}"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text"
                               name="apellido_paterno"
                               class="form-control border-success @error('apellido_paterno') is-invalid @enderror"
                               value="{{ old('apellido_paterno', $personalArchivo->apellido_paterno) }}"
                               required>
                        @error('apellido_paterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text"
                               name="apellido_materno"
                               class="form-control border-success @error('apellido_materno') is-invalid @enderror"
                               value="{{ old('apellido_materno', $personalArchivo->apellido_materno) }}"
                               required>
                        @error('apellido_materno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">RFC</label>
                        <input type="text"
                               name="rfc"
                               class="form-control border-success @error('rfc') is-invalid @enderror"
                               value="{{ old('rfc', $personalArchivo->rfc) }}"
                               required>
                        @error('rfc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número de Empleado</label>
                        <input type="text"
                               name="numero_empleado"
                               class="form-control border-success @error('numero_empleado') is-invalid @enderror"
                               value="{{ old('numero_empleado', $personalArchivo->numero_empleado) }}"
                               required>
                        @error('numero_empleado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text"
                               name="cargo"
                               class="form-control border-success @error('cargo') is-invalid @enderror"
                               value="{{ old('cargo', $personalArchivo->cargo) }}"
                               required>
                        @error('cargo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Área</label>
                        <input type="text"
                               name="area"
                               class="form-control border-success @error('area') is-invalid @enderror"
                               value="{{ old('area', $personalArchivo->area) }}"
                               required>
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo"
                                class="form-select border-success @error('tipo') is-invalid @enderror"
                                required>
                            <option value="">Seleccione...</option>
                            @foreach(['base','suplente','externo'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ old('tipo', $personalArchivo->tipo) == $tipo ? 'selected' : '' }}>
                                    {{ ucfirst($tipo) }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('personal_archivos.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">
                        Actualizar Personal
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection