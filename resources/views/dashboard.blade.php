@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-1 text-success fw-bold">Bienvenido, {{ auth()->user()->nombre_usuario }}</h1>
    <p class="text-muted mb-4">Sistema Integral de Gestión — Farmacia, Almacén y Archivo Clínico</p>

    <div class="row g-4">

        @if(auth()->user()->hasRol('administrador', 'archivo', 'medico'))
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="bi bi-folder2-open me-1"></i> Archivo Clínico
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Expedientes, documentos y préstamos.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('expedientes.index') }}" class="btn btn-outline-success btn-sm">Expedientes</a>
                            <a href="{{ route('documentos.index') }}" class="btn btn-outline-success btn-sm">Documentos</a>
                            @if(auth()->user()->hasRol('administrador', 'medico'))
                                <a href="{{ route('recetas.index') }}" class="btn btn-outline-success btn-sm">Recetas</a>
                            @endif
                            @if(auth()->user()->hasRol('administrador', 'archivo'))
                                <a href="{{ route('prestamos.index') }}" class="btn btn-outline-success btn-sm">Préstamos</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->hasRol('administrador', 'farmacia'))
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="bi bi-capsule me-1"></i> Farmacia
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Medicamentos, recetas y dispensación.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-outline-success btn-sm">Medicamentos</a>
                            <a href="{{ route('recetas.index', ['estatus' => 'pendiente']) }}" class="btn btn-outline-success btn-sm">Recetas Pendientes</a>
                            <a href="{{ route('farmacia.alertas') }}" class="btn btn-outline-danger btn-sm">Alertas</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->hasRol('administrador', 'almacen'))
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="bi bi-box-seam me-1"></i> Almacén
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Inventario, proveedores y transferencias.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-success btn-sm">Productos</a>
                            <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-success btn-sm">Solicitudes</a>
                            <a href="{{ route('almacen.existencias') }}" class="btn btn-outline-success btn-sm">Existencias</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
