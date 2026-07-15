@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Gestión de Documentos
    </h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold d-flex justify-content-between align-items-center">
            <span>Listado de Documentos</span>

            <a href="{{ route('documentos.create') }}"
               class="btn btn-light btn-sm shadow-sm">
                + Nuevo Documento
            </a>
        </div>

        <div class="card-body">

            {{-- Buscador --}}
            <div class="row mb-3">
                <div class="col-md-4 ms-auto">
                    <input type="text"
                           id="buscador"
                           class="form-control border-success"
                           placeholder="Buscar documento...">
                </div>
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="tablaDocumentos">

                    <thead class="table-success">
                        <tr>
                            <th>Nombre</th>
                            <th>No. Expediente</th>
                            <th>Clave</th>
                            <th>Fecha</th>
                            <th width="260" class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($documentos as $doc)

                        @php
                            $expediente = $doc->expediente;
                            $derecho = $doc->derechoHabiente;
                        @endphp

                        <tr data-busqueda="{{ strtolower(
                                ($doc->nombre_documento ?? '') . ' ' .
                                ($expediente->codigo ?? '') . ' ' .
                                ($derecho->clave_completa ?? '')
                        ) }}">

                            <td class="fw-semibold">
                                {{ $doc->nombre_documento }}
                            </td>

                            <td>
                                {{ $expediente->codigo ?? 'Sin expediente' }}
                            </td>

                            <td>
                                {{ $derecho->clave_completa ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $doc->fecha_anexo?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1 flex-wrap">

                                    {{-- Ver --}}
                                    <a href="{{ route('documentos.show',$doc->id) }}"
                                       class="btn btn-outline-success btn-sm">
                                        Ver
                                    </a>

                                    {{-- Editar --}}
                                    <a href="{{ route('documentos.edit',$doc->id) }}"
                                       class="btn btn-success btn-sm">
                                        Editar
                                    </a>

                                    {{-- Abrir archivo --}}
                                    @if($doc->existe_archivo)
                                        <a href="{{ $doc->url }}"
                                           target="_blank"
                                           class="btn btn-secondary btn-sm">

                                            {{-- Texto dinámico --}}
                                            {{ strtoupper($doc->tipo_archivo ?? 'VER') }}

                                        </a>
                                    @endif

                                    {{-- Eliminar --}}
                                    <form action="{{ route('documentos.destroy',$doc->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Está seguro de eliminar este documento?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay documentos registrados.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>

{{-- Buscador --}}
<script>

const buscador = document.getElementById("buscador");

buscador.addEventListener("keyup", function(){

    let filtro = this.value.toLowerCase();

    document.querySelectorAll("#tablaDocumentos tbody tr").forEach(function(fila){

        const texto = fila.dataset.busqueda ?? '';

        fila.style.display = texto.includes(filtro) ? "" : "none";

    });

});

</script>

@endsection