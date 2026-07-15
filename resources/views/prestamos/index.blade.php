@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 fw-bold text-success">
        Préstamos de Expedientes
    </h1>

    {{-- Mensaje éxito --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Gestión de Préstamos
        </div>

        <div class="card-body">

            {{-- Barra superior --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

                <a href="{{ route('prestamos.create') }}"
                   class="btn btn-success shadow-sm">
                    + Nuevo Préstamo
                </a>

                <input type="text"
                       id="buscador"
                       class="form-control w-auto border-success"
                       placeholder="Buscar préstamo...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaPrestamos">

                    <thead class="table-success">
                        <tr>
                            <th>Expediente</th>
                            <th>Médico</th>
                            <th>Área</th>
                            <th>Salida</th>
                            <th>Regreso</th>
                            <th>Estatus</th>
                            <th width="260">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($prestamos as $prestamo)

                            @php
                                $estatus = $prestamo->estatus_automatico;
                                $esDevuelto = $prestamo->isDevuelto();

                                $badgeColors = [
                                    \App\Models\Prestamo::ESTATUS_PENDIENTE => 'primary',
                                    \App\Models\Prestamo::ESTATUS_VENCIDO   => 'danger',
                                    \App\Models\Prestamo::ESTATUS_DEVUELTO  => 'success',
                                ];

                                $badge = $badgeColors[$estatus] ?? 'secondary';
                            @endphp

                            <tr>

                                {{-- Expediente --}}
                                <td>
                                    {{ optional($prestamo->expediente?->derechoHabiente)->clave_completa ?? 'N/A' }}
                                </td>

                                {{-- Médico --}}
                                <td>
                                    {{ trim(($prestamo->medico?->nombre ?? '') . ' ' . ($prestamo->medico?->apellido_paterno ?? '')) ?: 'N/A' }}
                                </td>

                                {{-- Área --}}
                                <td>
                                    {{ $prestamo->area_destino ?: 'N/A' }}
                                </td>

                                {{-- Salida --}}
                                <td>
                                    {{ optional($prestamo->fecha_salida)->format('d/m/Y') }}
                                </td>

                                {{-- Regreso --}}
                                <td>
                                    {{ optional($prestamo->fecha_regreso)->format('d/m/Y') }}
                                </td>

                                {{-- Estatus --}}
                                <td>
                                    <span class="badge bg-{{ $badge }} px-3 py-2">
                                        {{ ucfirst($estatus) }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">

                                        <a href="{{ route('prestamos.show', $prestamo) }}"
                                           class="btn btn-outline-success btn-sm">
                                            Ver
                                        </a>

                                        @unless($esDevuelto)
                                            <a href="{{ route('prestamos.edit', $prestamo) }}"
                                               class="btn btn-success btn-sm">
                                                Editar
                                            </a>

                                            <a href="{{ route('prestamos.devolver', $prestamo) }}"
                                               class="btn btn-warning btn-sm">
                                                Devolver
                                            </a>

                                            <form action="{{ route('prestamos.destroy', $prestamo) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Eliminar préstamo?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endunless

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay préstamos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

{{-- Buscador simple --}}
<script>
document.getElementById("buscador")?.addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaPrestamos tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

@endsection