@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Reporte de Existencias</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Existencias por Producto
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('almacen.existencias') }}" class="mb-3 d-flex gap-2">
                <select name="categoria" class="form-select w-auto">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}" @selected($categoria === $cat)>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-success">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Unidad</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            @php $stock = $producto->stock_total ?? 0; @endphp
                            <tr>
                                <td class="fw-semibold">{{ $producto->clave }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ ucfirst($producto->categoria) }}</td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td>{{ $stock }}</td>
                                <td>{{ $producto->stock_minimo }}</td>
                                <td>
                                    @if($stock == 0)
                                        <span class="badge bg-danger">Sin existencia</span>
                                    @elseif($stock <= $producto->stock_minimo)
                                        <span class="badge bg-warning text-dark">Bajo mínimo</span>
                                    @else
                                        <span class="badge bg-success">Suficiente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Sin productos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
