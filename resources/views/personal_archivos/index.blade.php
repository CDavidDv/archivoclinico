@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Personal de Archivo Clínico
    </h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Gestión de Personal de Archivo Clínico
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <a href="{{ route('personal_archivos.create') }}" 
                   class="btn btn-success shadow-sm">
                    + Nuevo Personal
                </a>

                {{-- Buscador --}}
                <input type="text" 
                       id="buscador" 
                       class="form-control w-25 border-success"
                       placeholder="Buscar personal...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaPersonalArchivos">

                    <thead class="bg-success text-white">
                        <tr>
                            <th>Nombre Completo</th>
                            <th>RFC</th>
                            <th>No. Empleado</th>
                            <th>Área</th>
                            <th>Cargo</th>
                            <th>Tipo</th>
                            <th width="250">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($personalArchivos as $personalArchivo)
                            <tr>

                                <td>
                                    {{ $personalArchivo->nombre }}
                                    {{ $personalArchivo->apellido_paterno }}
                                    {{ $personalArchivo->apellido_materno }}
                                </td>

                                <td>{{ $personalArchivo->rfc }}</td>

                                <td>{{ $personalArchivo->numero_empleado }}</td>

                                <td>{{ $personalArchivo->area }}</td>

                                <td>{{ $personalArchivo->cargo }}</td>

                                <td>
                                    @php
                                        $badgeColor = match($personalArchivo->tipo) {
                                            'base' => 'success',
                                            'suplente' => 'warning',
                                            'externo' => 'info',
                                            default => 'secondary'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ ucfirst($personalArchivo->tipo) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('personal_archivos.show',$personalArchivo->id) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('personal_archivos.edit',$personalArchivo->id) }}" 
                                           class="btn btn-success btn-sm">
                                            Editar
                                        </a>

                                        <form action="{{ route('personal_archivos.destroy',$personalArchivo->id) }}" 
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar registro?')">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay personal registrado.
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
    let filas = document.querySelectorAll("#tablaPersonalArchivos tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

@endsection