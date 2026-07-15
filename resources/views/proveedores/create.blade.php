@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Nuevo Proveedor</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">Registrar Proveedor</div>
        <div class="card-body">
            <form method="POST" action="{{ route('proveedores.store') }}">
                @csrf
                @include('proveedores._form', ['proveedor' => null])
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
