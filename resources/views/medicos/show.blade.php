@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Médico
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Información del Médico
        </div>

        <div class="card-body">

            <div class="mb-4">
                <h4 class="fw-bold text-success">
                    {{ $medico->nombre }}
                    {{ $medico->apellido_paterno }}
                    {{ $medico->apellido_materno }}
                </h4>
            </div>

            @php
                $badgeColor = match(strtolower($medico->tipo)) {
                    'base' => 'success',
                    'suplente' => 'warning',
                    'externo' => 'info',
                    'confianza' => 'primary',
                    'residente' => 'dark',
                    'pasante' => 'secondary',
                    'interino' => 'danger',
                    'interno' => 'light',
                    default => 'secondary'
                };
            @endphp

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>RFC:</strong>
                    <div>{{ $medico->rfc }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Número de Empleado:</strong>
                    <div>{{ $medico->numero_empleado }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Cargo:</strong>
                    <div>{{ $medico->cargo ?? 'No especificado' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Área:</strong>
                    <div>{{ $medico->area }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Denominación:</strong>
                    <div>
                        <span class="badge bg-{{ $badgeColor }}">
                            {{ ucfirst($medico->tipo) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>
            <div class="mt-4">

                <a href="{{ route('medicos.index') }}"
                   class="btn btn-secondary">
                    Volver
                </a>
                <a></a>

                <a href="{{ route('medicos.edit',$medico->id) }}" 
                    class="btn btn-success shadow-sm">
                     Editar Registro
                </a>
            </div>

</div>
@endsection