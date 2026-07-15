@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Derecho Habiente
    </h1>

    {{-- Mensaje éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Información General
        </div>

        <div class="card-body">

            <h4 class="fw-bold text-success">
                {{ $dh->nombre }}
                {{ $dh->apellido_paterno }}
                {{ $dh->apellido_materno }}
            </h4>

            <hr>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">RFC</label>
                    <div>{{ $dh->rfc }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">NSS</label>
                    <div>{{ $dh->nss }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">Clave Identificación</label>
                    <div>
                        <span class="badge bg-success">
                            {{ $dh->clave_identificacion }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">
                        Clave de Expediente
                    </label>
                    <div class="text-success fw-bold fs-5">
                        {{ $dh->clave_generada }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">
                        Fecha de Nacimiento
                    </label>
                    <div>
                        {{ \Carbon\Carbon::parse($dh->fecha_nacimiento)->format('d/m/Y') }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="fw-semibold text-muted">
                        Género
                    </label>
                    <div>{{ $dh->genero }}</div>
                </div>

            </div>

            <hr>

            <div class="mb-3">
                <label class="fw-semibold text-muted">
                    Síntomas / Motivo
                </label>
                <div>
                    {{ $dh->sintomas ?? 'No registrados' }}
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold text-muted">
                    Tratamiento / Resumen Médico
                </label>
                <div>
                    {{ $dh->tratamiento ?? 'No registrados' }}
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">

        <a href="{{ route('derecho_habientes.index') }}" 
           class="btn btn-secondary">
            Volver
        </a>

        <a href="{{ route('derecho_habientes.edit',$dh->id) }}" 
           class="btn btn-success shadow-sm">
            Editar Registro
        </a>

    </div>

</div>
@endsection