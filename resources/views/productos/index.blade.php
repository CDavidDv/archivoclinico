@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Productos</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Catálogo de Productos</span>
            <a href="{{ route('productos.create') }}" class="btn btn-light btn-sm shadow-sm">
                + Nuevo Producto
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Unidad</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th width="220">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td class="fw-semibold">{{ $producto->clave }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($producto->categoria) }}</span></td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td>
                                    <span class="badge {{ $producto->stock_total <= $producto->stock_minimo ? 'bg-danger' : 'bg-success' }}">
                                        {{ $producto->stock_total ?? 0 }}
                                    </span>
                                </td>
                                <td>{{ $producto->stock_minimo }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline-success btn-sm">Ver</a>
                                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-outline-warning btn-sm">Editar</a>
                                    <form action="{{ route('productos.destroy', $producto) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
