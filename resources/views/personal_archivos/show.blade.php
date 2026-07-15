@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Personal de Archivo Clínico
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Información del Personal
        </div>

        <div class="card-body">

            {{-- Nombre completo --}}
            <div class="mb-4">
                <h4 class="fw-bold text-success">
                    {{ $personalArchivo->nombre }}
                    {{ $personalArchivo->apellido_paterno }}
                    {{ $personalArchivo->apellido_materno }}
                </h4>
            </div>

            @php
                $badgeColor = match(strtolower($personalArchivo->tipo)) {
                    'base' => 'success',
                    'suplente' => 'warning',
                    'externo' => 'info',
                    default => 'secondary'
                };
            @endphp

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>RFC:</strong>
                    <div>{{ $personalArchivo->rfc }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Número de Empleado:</strong>
                    <div>{{ $personalArchivo->numero_empleado }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Cargo:</strong>
                    <div>{{ $personalArchivo->cargo ?? 'No especificado' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Área:</strong>
                    <div>{{ $personalArchivo->area }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Tipo:</strong>
                    <div>
                        <span class="badge bg-{{ $badgeColor }}">
                            {{ ucfirst($personalArchivo->tipo) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

            <div class="mt-4">
                        <a href="{{ route('personal_archivos.index') }}"
                        class="btn btn-secondary">
                            Volver
                        </a>

                        <a href="{{ route('personal_archivos.edit',$personalArchivo->id) }}" 
                            class="btn btn-success shadow-sm">
                            Editar Registro
                        </a>
            </div>

</div>
@endsection