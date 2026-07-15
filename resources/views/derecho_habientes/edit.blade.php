@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Editar Derecho Habiente</h1>

    {{-- Errores --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Actualización de Datos
        </div>

        <div class="card-body">

            <form action="{{ route('derecho_habientes.update',$dh->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre"
                               value="{{ old('nombre', $dh->nombre) }}"
                               class="form-control border-success @error('nombre') is-invalid @enderror"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno"
                               value="{{ old('apellido_paterno', $dh->apellido_paterno) }}"
                               class="form-control border-success @error('apellido_paterno') is-invalid @enderror"
                               required>
                        @error('apellido_paterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Apellido Materno</label>
                        <input type="text" name="apellido_materno"
                               value="{{ old('apellido_materno', $dh->apellido_materno) }}"
                               class="form-control border-success @error('apellido_materno') is-invalid @enderror"
                               required>
                        @error('apellido_materno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">RFC</label>
                        <input type="text" name="rfc"
                               value="{{ old('rfc', $dh->rfc) }}"
                               class="form-control border-success @error('rfc') is-invalid @enderror"
                               required>
                        @error('rfc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">NSS</label>
                        <input type="text" name="nss"
                               value="{{ old('nss', $dh->nss) }}"
                               class="form-control border-success @error('nss') is-invalid @enderror"
                               required>
                        @error('nss')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', $dh->fecha_nacimiento) }}"
                               class="form-control border-success"
                               required>
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Clave Identificación</label>
                    <select name="clave_identificacion"
                            class="form-select border-success @error('clave_identificacion') is-invalid @enderror"
                            required>

                        @foreach([
                            '10'=>'Trabajador','20'=>'Trabajadora','30'=>'Esposa',
                            '31'=>'Concubina','32'=>'Mujer','40'=>'Esposo',
                            '41'=>'Concubino','50'=>'Padre','51'=>'Abuelo',
                            '60'=>'Madre','61'=>'Abuela','70'=>'Hijo',
                            '71'=>'Hijo de Cónyuge','80'=>'Hija',
                            '81'=>'Hija de Cónyuge','90'=>'Pensionado',
                            '91'=>'Pensionada','92'=>'Familiar de pensionado',
                            '95'=>'IMSS Bienestar Hombre','96'=>'IMSS Bienestar Mujer',
                            '97'=>'IMSS Hombre','98'=>'IMSS Mujer',
                            '99'=>'No Derechohabiente'
                        ] as $clave => $texto)

                            <option value="{{ $clave }}"
                                {{ old('clave_identificacion', $dh->clave_identificacion) == $clave ? 'selected' : '' }}>
                                {{ $clave }} - {{ $texto }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Género</label>
                        <select name="genero" class="form-select border-success">
                            <option value="Masculino"
                                {{ old('genero', $dh->genero) == 'Masculino' ? 'selected' : '' }}>
                                Masculino
                            </option>
                            <option value="Femenino"
                                {{ old('genero', $dh->genero) == 'Femenino' ? 'selected' : '' }}>
                                Femenino
                            </option>
                        </select>
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Síntomas o Motivo
                    </label>
                    <input type="text" name="sintomas"
                           value="{{ old('sintomas', $dh->sintomas) }}"
                           class="form-control border-success"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Tratamiento / Resumen Médico
                    </label>
                    <input type="text" name="tratamiento"
                           value="{{ old('tratamiento', $dh->tratamiento) }}"
                           class="form-control border-success"
                           required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('derecho_habientes.index') }}" 
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" 
                            class="btn btn-success shadow-sm">
                        Actualizar Derecho Habiente
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection