@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Médicos
    </h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Gestión de Médicos
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <a href="{{ route('medicos.create') }}" 
                   class="btn btn-success shadow-sm">
                    + Nuevo Médico
                </a>

                {{-- Buscador --}}
                <input type="text" 
                       id="buscador" 
                       class="form-control w-25 border-success"
                       placeholder="Buscar médico...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaMedicos">

                    <thead class="bg-success text-white">
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>No. Empleado</th>
                            <th>Área</th>
                            <th>Denominación</th>
                            <th width="250">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($medicos as $medico)
                            <tr>

                                <td>
                                    {{ $medico->nombre }}
                                    {{ $medico->apellido_paterno }}
                                    {{ $medico->apellido_materno }}
                                </td>

                                <td>{{ $medico->rfc }}</td>

                                <td>{{ $medico->numero_empleado }}</td>

                                <td>{{ $medico->area }}</td>

                                <td>
                                    @php
                                        $badgeColor = match($medico->tipo) {
                                            'base' => 'success',
                                            'suplente' => 'warning',
                                            'externo' => 'info',
                                            default => 'secondary'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ ucfirst($medico->tipo) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="{{ route('medicos.show',$medico->id) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('medicos.edit',$medico->id) }}" 
                                           class="btn btn-success btn-sm">
                                            Editar
                                        </a>

                                        <form action="{{ route('medicos.destroy',$medico->id) }}" 
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar médico?')">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay médicos registrados.
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
    let filas = document.querySelectorAll("#tablaMedicos tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

@endsection