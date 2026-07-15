@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Dispensaciones</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Historial de Dispensaciones
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Folio</th>
                            <th>Receta</th>
                            <th>Derechohabiente</th>
                            <th>Fecha</th>
                            <th>Dispensó</th>
                            <th width="100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dispensaciones as $dispensacion)
                            <tr>
                                <td class="fw-semibold">#{{ $dispensacion->id }}</td>
                                <td>
                                    <a href="{{ route('recetas.show', $dispensacion->receta) }}">
                                        {{ $dispensacion->receta->folio }}
                                    </a>
                                </td>
                                <td>
                                    {{ $dispensacion->receta->derechoHabiente->nombre }}
                                    {{ $dispensacion->receta->derechoHabiente->apellido_paterno }}
                                </td>
                                <td>{{ $dispensacion->fecha->format('d/m/Y H:i') }}</td>
                                <td>{{ $dispensacion->usuario?->nombre_usuario }}</td>
                                <td>
                                    <a href="{{ route('dispensaciones.show', $dispensacion) }}"
                                       class="btn btn-outline-success btn-sm">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay dispensaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $dispensaciones->links() }}
        </div>
    </div>
</div>
@endsection
