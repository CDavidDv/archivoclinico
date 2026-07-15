@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Préstamo
    </h1>

    @php
        $estatus = $prestamo->estatus_automatico ?? $prestamo->estatus;

        $color = match($estatus) {
            'pendiente' => 'primary',
            'devuelto'  => 'success',
            'vencido'   => 'danger',
            default     => 'secondary',
        };
    @endphp

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Información del Préstamo</span>

            <span class="badge bg-{{ $color }} px-3 py-2">
                {{ ucfirst($estatus) }}
            </span>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Expediente --}}
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Número de Expediente</label>
                    <div>
                        {{ $prestamo->expediente?->codigo ?? 'Sin expediente' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Clave de Expediente</label>
                    <div>
                        {{ $prestamo->expediente?->derechoHabiente?->clave_completa ?? 'N/A' }}
                    </div>
                </div>

                {{-- Médico --}}
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Médico</label>
                    <div>
                        {{ trim(
                            ($prestamo->medico?->nombre ?? '') . ' ' .
                            ($prestamo->medico?->apellido_paterno ?? '') . ' ' .
                            ($prestamo->medico?->apellido_materno ?? '')
                        ) ?: 'No asignado' }}
                    </div>
                </div>

                {{-- Entregado por --}}
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Entregado por</label>
                    <div>
                        {{ trim(
                            ($prestamo->entregadoPor?->nombre ?? '') . ' ' .
                            ($prestamo->entregadoPor?->apellido_paterno ?? '') . ' ' .
                            ($prestamo->entregadoPor?->apellido_materno ?? '')
                        ) ?: 'No registrado' }}
                    </div>
                </div>

                {{-- Recibido por --}}
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Recibido por</label>
                    <div>
                        {{ trim(
                            ($prestamo->recibidoPor?->nombre ?? '') . ' ' .
                            ($prestamo->recibidoPor?->apellido_paterno ?? '') . ' ' .
                            ($prestamo->recibidoPor?->apellido_materno ?? '')
                        ) ?: 'Pendiente de devolución' }}
                    </div>
                </div>

                {{-- Área --}}
                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Área Destino</label>
                    <div>
                        {{ $prestamo->area_destino }}
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="col-md-3">
                    <label class="fw-semibold text-muted">Fecha Salida</label>
                    <div>
                        {{ $prestamo->fecha_salida?->format('d/m/Y') }}
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="fw-semibold text-muted">Fecha Comprometida</label>
                    <div>
                        {{ $prestamo->fecha_regreso?->format('d/m/Y') ?? 'Pendiente' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="fw-semibold text-muted">Fecha Devolución</label>
                    <div>
                        {{ $prestamo->fecha_devolucion_real?->format('d/m/Y H:i') ?? 'No devuelto' }}
                    </div>
                </div>

                {{-- Días --}}
                <div class="col-md-3">
                    <label class="fw-semibold text-muted">Días Asignados</label>
                    <div>
                        {{ $prestamo->dias_asignados }}
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('prestamos.index') }}"
                   class="btn btn-secondary">
                    Volver
                </a>

                @if(!$prestamo->isDevuelto())
                    <a href="{{ route('prestamos.edit', $prestamo) }}"
                       class="btn btn-success shadow-sm">
                        Editar
                    </a>
                @endif

            </div>

        </div>
    </div>

</div>
@endsection