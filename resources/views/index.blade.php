@extends('layouts.app')

@section('content')
<div class="container">

    <div class="text-center mb-3">
        <h4 class="text-secondary fw-light">
            Módulo Administrativo
        </h4>
        <h1 class="mb-4 text-success fw-bold">
        Sistema de Archivo Clínico
        </h1>
    </div>

    

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Panel Principal
        </div>

        <div class="card-body">

            <p class="lead text-muted mb-4">
                Administra expedientes, documentos y préstamos de manera rápida y segura.
            </p>

            <div class="row g-4">

                {{-- Derecho Habientes --}}
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success fw-bold">
                                Derecho Habientes
                            </h5>
                            <p class="card-text text-muted">
                                Registro y gestión de pacientes.
                            </p>
                            <a href="{{ route('derecho_habientes.index') }}"
                               class="btn btn-success btn-sm">
                                Acceder
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Expedientes --}}
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success fw-bold">
                                Expedientes
                            </h5>
                            <p class="card-text text-muted">
                                Consulta y administración de expedientes clínicos.
                            </p>
                            <a href="{{ route('expedientes.index') }}"
                               class="btn btn-success btn-sm">
                                Acceder
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Préstamos --}}
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success fw-bold">
                                Préstamos
                            </h5>
                            <p class="card-text text-muted">
                                Control de salida y devolución de expedientes.
                            </p>
                            <a href="{{ route('prestamos.index') }}"
                               class="btn btn-success btn-sm">
                                Acceder
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection