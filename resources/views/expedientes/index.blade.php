@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Expedientes</h1>

    {{-- Mensaje éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Gestión de Expedientes
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                

                {{-- Buscador --}}
                <input type="text"
                       id="buscador"
                       class="form-control w-25 border-success"
                       placeholder="Buscar Expediente...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaExpedientes">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>No. Expediente</th>
                            <th>Clave Expediente</th>
                            <th>Derecho Habiente</th>
                            <th>Localización</th>
                            <th>Tipo</th>
                            <th width="250">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($expedientes as $expediente)
                            <tr>

                                <td class="fw-semibold">
                                    {{ $expediente->codigo }}
                                </td>

                                <td>
                                    @if($expediente->derechoHabiente)
                                        {{ $expediente->derechoHabiente->rfc }}
                                        /
                                        <span class="badge bg-success">
                                            {{ $expediente->derechoHabiente->clave_identificacion }}
                                        </span>
                                    @else
                                        <span class="text-muted">Sin relación</span>
                                    @endif
                                </td>

                                <td>
                                    {{ optional($expediente->derechoHabiente)->nombre }}
                                    {{ optional($expediente->derechoHabiente)->apellido_paterno }}
                                    {{ optional($expediente->derechoHabiente)->apellido_materno }}
                                </td>

                                <td>{{ $expediente->localizacion }}</td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $expediente->tipo }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('expedientes.show',$expediente->id) }}"
                                           class="btn btn-outline-success btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('expedientes.edit',$expediente->id) }}"
                                           class="btn btn-success btn-sm">
                                            Editar
                                        </a>

                                        <form action="{{ route('expedientes.destroy',$expediente->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Eliminar este expediente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay expedientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

{{-- Script Buscador --}}
<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaExpedientes tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

@endsection