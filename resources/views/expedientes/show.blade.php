@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Expediente
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Información del Expediente
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <h4 class="fw-bold text-success">
                        Código: {{ $expediente->codigo }}
                    </h4>
                    <h4 class="fw-bold text-success">
                        Clave:
                        <span class="badge bg-success">
                            {{ $expediente->derechoHabiente->clave_completa ?? 'N/A' }}
                        </span>
                    </h4>
                </div>

                <div class="col-md-6 text-md-end">
                    @php
                        $badgeColor = match($expediente->tipo) {
                            'normal' => 'success',
                            'gordo' => 'warning',
                            'confidencial' => 'danger',
                            default => 'secondary'
                        };
                    @endphp

                    <span class="badge bg-{{ $badgeColor }} fs-6">
                        {{ ucfirst($expediente->tipo) }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Derecho Habiente:</strong><br>
                    {{ $expediente->derechoHabiente->nombre }}
                    {{ $expediente->derechoHabiente->apellido_paterno }}
                    {{ $expediente->derechoHabiente->apellido_materno }}
                </div>

                <div class="col-md-6">
                    <strong>Localización:</strong><br>
                    {{ $expediente->localizacion ?? 'No especificada' }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <strong>Fecha de Creación:</strong><br>
                    {{ \Carbon\Carbon::parse($expediente->fecha_creacion)->format('d/m/Y') }}
                </div>

                <div class="col-md-6">
                    <strong>Fecha de Eliminación:</strong><br>
                    @if($expediente->fecha_eliminacion)
                        <span class="text-danger fw-semibold">
                            {{ \Carbon\Carbon::parse($expediente->fecha_eliminacion)->format('d/m/Y') }}
                        </span>
                    @else
                        <span class="text-success">
                            Activo
                        </span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('expedientes.index') }}"
           class="btn btn-secondary">
            Volver
        </a>

        <a href="{{ route('expedientes.edit',$expediente->id) }}" 
           class="btn btn-success shadow-sm">
            Editar Expediente
        </a>
    </div>

</div>
@endsection