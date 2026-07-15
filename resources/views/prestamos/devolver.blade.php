@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 fw-bold text-success">
        Registrar Devolución
    </h1>

    {{-- ERRORES --}}
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

    @php
        $estatus = $prestamo->estatus_automatico;

        $badgeColors = [
            \App\Models\Prestamo::ESTATUS_PENDIENTE => 'primary',
            \App\Models\Prestamo::ESTATUS_VENCIDO   => 'danger',
            \App\Models\Prestamo::ESTATUS_DEVUELTO  => 'success',
        ];

        $color = $badgeColors[$estatus] ?? 'secondary';
    @endphp

    <div class="card shadow border-0">

        {{-- HEADER --}}
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Confirmación de Devolución</span>

            <span class="badge bg-{{ $color }} px-3 py-2">
                {{ ucfirst($estatus) }}
            </span>
        </div>

        <div class="card-body">

            {{-- SI YA FUE DEVUELTO --}}
            @if($prestamo->isDevuelto())

                <div class="alert alert-success">
                    Este préstamo fue devuelto el
                    <strong>{{ $prestamo->fecha_devolucion_real?->format('d/m/Y H:i') }}</strong>.
                </div>

                <div class="text-end">
                    <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                        Volver
                    </a>
                </div>

            @else

                {{-- SI ESTÁ VENCIDO --}}
                @if($prestamo->isVencido())
                    <div class="alert alert-warning">
                        ⚠ Este préstamo está vencido. Registre la devolución lo antes posible.
                    </div>
                @endif

                <form action="{{ route('prestamos.procesarDevolucion', $prestamo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Expediente --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expediente</label>
                            <input type="text" class="form-control"
                                   value="{{ $prestamo->expediente?->derechoHabiente?->clave_completa ?? 'Sin clave' }}"
                                   disabled>
                        </div>

                        {{-- Médico --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Médico</label>
                            <input type="text" class="form-control"
                                   value="{{ trim(
                                        ($prestamo->medico?->nombre ?? '') . ' ' .
                                        ($prestamo->medico?->apellido_paterno ?? '') . ' ' .
                                        ($prestamo->medico?->apellido_materno ?? '')
                                   ) }}"
                                   disabled>
                        </div>

                        {{-- Área --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Área Destino</label>
                            <input type="text" class="form-control"
                                   value="{{ $prestamo->area_destino }}"
                                   disabled>
                        </div>

                        {{-- Fecha salida --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Fecha Salida</label>
                            <input type="text" class="form-control"
                                   value="{{ $prestamo->fecha_salida?->format('d/m/Y') }}"
                                   disabled>
                        </div>

                        {{-- Fecha comprometida --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Fecha Comprometida</label>
                            <input type="text" class="form-control"
                                   value="{{ $prestamo->fecha_regreso?->format('d/m/Y') }}"
                                   disabled>
                        </div>

                        {{-- Recibido por --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Recibido por</label>
                            <select name="recibido_por"
                                    class="form-select @error('recibido_por') is-invalid @enderror"
                                    required>

                                <option value="" disabled {{ old('recibido_por') ? '' : 'selected' }}>
                                    Seleccione personal
                                </option>

                                @foreach($personalArchivos as $personal)
                                    <option value="{{ $personal->id }}"
                                        {{ old('recibido_por') == $personal->id ? 'selected' : '' }}>
                                        {{ trim(
                                            $personal->nombre . ' ' .
                                            $personal->apellido_paterno . ' ' .
                                            $personal->apellido_materno
                                        ) }}
                                    </option>
                                @endforeach
                            </select>

                            @error('recibido_por')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Fecha automática --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha de Devolución</label>
                            <input type="text" class="form-control bg-light"
                                   value="{{ now()->format('d/m/Y H:i') }}"
                                   disabled>
                            <small class="text-muted">
                                Se registrará automáticamente al confirmar.
                            </small>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('prestamos.index') }}"
                           class="btn btn-secondary">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn btn-success shadow-sm"
                                onclick="return confirm('¿Confirmar devolución del préstamo? Esta acción no se puede deshacer.')">
                            Confirmar Devolución
                        </button>

                    </div>

                </form>

            @endif

        </div>
    </div>

</div>
@endsection