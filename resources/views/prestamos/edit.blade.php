@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Editar Préstamo
    </h1>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Se encontraron errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Actualización de Préstamo
        </div>

        <div class="card-body">

            <form action="{{ route('prestamos.update', $prestamo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Expediente --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expediente</label>
                        <select name="id_expediente"
                                class="form-select @error('id_expediente') is-invalid @enderror"
                                required>

                            <option value="" disabled>
                                Seleccione expediente
                            </option>

                            @foreach($expedientes as $exp)
                                <option value="{{ $exp->id }}"
                                    {{ old('id_expediente', $prestamo->id_expediente) == $exp->id ? 'selected' : '' }}>
                                    
                                    {{ optional($exp->derechoHabiente)->clave_completa ?? 'Sin clave' }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_expediente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Médico --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Médico</label>
                        <select name="id_medico"
                                class="form-select @error('id_medico') is-invalid @enderror"
                                required>

                            <option value="" disabled>
                                Seleccione médico
                            </option>

                            @foreach($medicos as $med)
                                <option value="{{ $med->id }}"
                                    {{ old('id_medico', $prestamo->id_medico) == $med->id ? 'selected' : '' }}>
                                    
                                    {{ trim($med->nombre . ' ' . $med->apellido_paterno . ' ' . $med->apellido_materno) }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_medico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Personal que entregó --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Entregado por</label>
                        <select name="entregado_por"
                                class="form-select @error('entregado_por') is-invalid @enderror"
                                required>

                            <option value="" disabled>
                                Seleccione personal
                            </option>

                            @foreach($personalArchivos as $personal)
                                <option value="{{ $personal->id }}"
                                    {{ old('entregado_por', $prestamo->entregado_por) == $personal->id ? 'selected' : '' }}>
                                    
                                    {{ trim($personal->nombre . ' ' . $personal->apellido_paterno . ' ' . $personal->apellido_materno) }}
                                </option>
                            @endforeach
                        </select>

                        @error('entregado_por')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Área Destino --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Área Destino</label>
                        <input type="text"
                               name="area_destino"
                               maxlength="150"
                               class="form-control @error('area_destino') is-invalid @enderror"
                               value="{{ old('area_destino', $prestamo->area_destino) }}"
                               required>

                        @error('area_destino')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha Salida --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fecha Salida</label>
                        <input type="date"
                               name="fecha_salida"
                               class="form-control @error('fecha_salida') is-invalid @enderror"
                               value="{{ old('fecha_salida', $prestamo->fecha_salida?->format('Y-m-d')) }}"
                               required>

                        @error('fecha_salida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha Regreso --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fecha Regreso</label>
                        <input type="date"
                               name="fecha_regreso"
                               class="form-control @error('fecha_regreso') is-invalid @enderror"
                               value="{{ old('fecha_regreso', $prestamo->fecha_regreso?->format('Y-m-d')) }}"
                               required>

                        @error('fecha_regreso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('prestamos.index') }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm"
                            onclick="return confirm('¿Actualizar este préstamo?')">
                        Actualizar Préstamo
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection