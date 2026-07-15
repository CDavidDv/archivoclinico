@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Editar Producto</h1>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">
            {{ $producto->clave }} — {{ $producto->nombre }}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('productos.update', $producto) }}">
                @csrf
                @method('PUT')
                @include('productos._form')
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
