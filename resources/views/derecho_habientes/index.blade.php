@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-success fw-bold">Derecho Habientes</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Gestión de Derecho Habientes
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('derecho_habientes.create') }}" 
                   class="btn btn-success shadow-sm">
                    + Nuevo Derecho Habiente
                </a>

                {{-- Buscador --}}
                <input type="text" 
                       id="buscador" 
                       class="form-control w-25 border-success"
                       placeholder="Buscar Derecho Habiente...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaDerechoHabientes">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>NSS</th>
                            <th>Clave</th>
                            <th width="250">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($derechoHabientes as $dh)
                            <tr>
                                <td>
                                    {{ $dh->nombre }} 
                                    {{ $dh->apellido_paterno }} 
                                    {{ $dh->apellido_materno }}
                                </td>

                                <td>{{ $dh->rfc }}</td>

                                <td>{{ $dh->nss }}</td>

                                <td>{{ $dh->clave_identificacion }}</td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('derecho_habientes.show',$dh->id) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('derecho_habientes.edit',$dh->id) }}" 
                                           class="btn btn-success btn-sm">
                                            Editar
                                        </a>

                                        <form action="{{ route('derecho_habientes.destroy',$dh->id) }}" 
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar derecho habiente?')">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay derechohabientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- Script buscador --}}
<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaDerechoHabientes tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

@endsection