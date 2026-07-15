@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Recetas Médicas</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Listado de Recetas</span>
            @if(auth()->user()->hasRol('administrador', 'medico'))
                <a href="{{ route('recetas.create') }}" class="btn btn-light btn-sm shadow-sm">
                    + Nueva Receta
                </a>
            @endif
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('recetas.index') }}" class="mb-3 d-flex gap-2">
                <select name="estatus" class="form-select w-auto">
                    <option value="">Todas</option>
                    @foreach(['pendiente', 'parcial', 'surtida', 'cancelada'] as $e)
                        <option value="{{ $e }}" @selected($estatus === $e)>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-success">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Derechohabiente</th>
                            <th>Médico</th>
                            <th>Renglones</th>
                            <th>Estatus</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td class="fw-semibold">{{ $receta->folio }}</td>
                                <td>{{ $receta->fecha_receta->format('d/m/Y') }}</td>
                                <td>
                                    {{ $receta->derechoHabiente->nombre }}
                                    {{ $receta->derechoHabiente->apellido_paterno }}
                                </td>
                                <td>{{ $receta->medico->nombre }}</td>
                                <td>{{ $receta->detalles_count }}</td>
                                <td>
                                    @php
                                        $badge = match($receta->estatus) {
                                            'pendiente' => 'bg-warning text-dark',
                                            'parcial'   => 'bg-info text-dark',
                                            'surtida'   => 'bg-success',
                                            'cancelada' => 'bg-secondary',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($receta->estatus) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('recetas.show', $receta) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay recetas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $recetas->links() }}
        </div>
    </div>
</div>
@endsection
