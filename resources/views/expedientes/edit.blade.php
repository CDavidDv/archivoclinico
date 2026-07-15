@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Editar Expediente
    </h1>

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
            Actualización de Expediente
        </div>

        <div class="card-body">

            <form action="{{ route('expedientes.update', $expediente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Código --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Código
                        </label>
                        <input type="text"
                               name="codigo"
                               value="{{ old('codigo', $expediente->codigo) }}"
                               class="form-control border-success @error('codigo') is-invalid @enderror"
                               required>

                        @error('codigo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Derecho Habiente --}}
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">
                            Derecho Habiente
                        </label>

                        <select name="id_derecho_habiente"
                                class="form-select border-success @error('id_derecho_habiente') is-invalid @enderror"
                                required>

                            <option value="">Seleccione un derecho habiente</option>

                            @foreach($derechoHabientes as $dh)
                                <option value="{{ $dh->id }}"
                                    {{ old('id_derecho_habiente', $expediente->id_derecho_habiente) == $dh->id ? 'selected' : '' }}>
                                    {{ $dh->nombre }}
                                    {{ $dh->apellido_paterno }}
                                    {{ $dh->apellido_materno }}
                                    - {{ $dh->rfc }}
                                </option>
                            @endforeach

                        </select>

                        @error('id_derecho_habiente')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    {{-- Localización --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Localización
                        </label>
                        <input type="text"
                               name="localizacion"
                               value="{{ old('localizacion', $expediente->localizacion) }}"
                               class="form-control border-success">
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Tipo
                        </label>

                        <select name="tipo"
                                class="form-select border-success">

                            @foreach([
                                'normal' => 'Normal',
                                'gordo' => 'Gordo',
                                'confidencial' => 'Confidencial'
                            ] as $valor => $texto)

                                <option value="{{ $valor }}"
                                    {{ old('tipo', $expediente->tipo) == $valor ? 'selected' : '' }}>
                                    {{ $texto }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Fecha --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Fecha de Creación
                        </label>
                        <input type="date"
                               name="fecha_creacion"
                               value="{{ old('fecha_creacion', $expediente->fecha_creacion) }}"
                               class="form-control border-success"
                               required>
                    </div>

                </div>

                <div class="d-flex justify-content-between mt-4">

                    <a href="{{ route('expedientes.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">
                        Actualizar Expediente
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection