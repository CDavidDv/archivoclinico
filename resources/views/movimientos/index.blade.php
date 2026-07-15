@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Auditoría del Sistema
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Movimientos del Sistema
        </div>

        <div class="card-body">

            {{-- Filtros --}}
            <form method="GET" class="row g-3 mb-4">

                <div class="col-md-3">
                    <select name="usuario"
                            class="form-select border-success">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}"
                                {{ request('usuario') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre_usuario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="accion"
                            class="form-select border-success">
                        <option value="">Todas las acciones</option>
                        <option value="crear" {{ request('accion') == 'crear' ? 'selected' : '' }}>Crear</option>
                        <option value="editar" {{ request('accion') == 'editar' ? 'selected' : '' }}>Editar</option>
                        <option value="eliminar" {{ request('accion') == 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date"
                           name="desde"
                           class="form-control border-success"
                           value="{{ request('desde') }}">
                </div>

                <div class="col-md-2">
                    <input type="date"
                           name="hasta"
                           class="form-control border-success"
                           value="{{ request('hasta') }}">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-success w-50 shadow-sm">
                        Filtrar
                    </button>

                    <a href="{{ route('movimientos.index') }}"
                       class="btn btn-secondary w-50">
                        Limpiar
                    </a>
                </div>

            </form>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="bg-success text-white">
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acción</th>
                            <th>Tabla Afectada</th>
                            <th>ID Registro</th>
                            <th>Fecha</th>
                            <th width="100">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($movimientos as $mov)
                            <tr>

                                <td>{{ $mov->usuario->nombre_usuario ?? 'N/A' }}</td>

                                <td>
                                    <span class="badge bg-dark">
                                        {{ ucfirst($mov->usuario->rol ?? 'N/A') }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $badge = match($mov->accion) {
                                            'crear' => 'success',
                                            'editar' => 'warning',
                                            'eliminar' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($mov->accion) }}
                                    </span>
                                </td>

                                <td>{{ $mov->tabla_afectada }}</td>

                                <td>{{ $mov->id_registro_afectado ?? '-' }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($mov->fecha_accion)->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <a href="{{ route('movimientos.show', $mov->id) }}"
                                       class="btn btn-outline-success btn-sm">
                                        Ver
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay movimientos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3 d-flex justify-content-end">
                {{ $movimientos->appends(request()->query())->links() }}
            </div>

        </div>
    </div>

</div>
@endsection