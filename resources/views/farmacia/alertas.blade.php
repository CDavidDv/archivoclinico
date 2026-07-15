@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Alertas de Farmacia</h1>

    {{-- STOCK MÍNIMO --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Medicamentos en o bajo stock mínimo
            </span>
            <a href="{{ route('solicitudes.create') }}" class="btn btn-light btn-sm shadow-sm">
                Solicitar abastecimiento
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th>Clave</th>
                            <th>Medicamento</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bajoMinimo as $medicamento)
                            <tr>
                                <td class="fw-semibold">{{ $medicamento->clave }}</td>
                                <td>{{ $medicamento->nombre }}</td>
                                <td><span class="badge bg-danger">{{ $medicamento->stock_total ?? 0 }}</span></td>
                                <td>{{ $medicamento->stock_minimo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Sin medicamentos bajo mínimo. ✔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- POR CADUCAR --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-warning text-dark fw-semibold">
            <i class="bi bi-clock-history me-1"></i>
            Lotes por caducar (30 días)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th>Medicamento</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Existencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($porCaducar as $lote)
                            <tr>
                                <td>{{ $lote->medicamento->clave }} — {{ $lote->medicamento->nombre }}</td>
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->caducidad->format('d/m/Y') }}</td>
                                <td>{{ $lote->cantidad_actual }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Sin lotes por caducar. ✔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CADUCADOS --}}
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white fw-semibold">
            <i class="bi bi-x-octagon me-1"></i>
            Lotes caducados con existencia
        </div>
        <div class="card-body">
            <p class="text-muted small">
                Retira estos lotes con una salida de tipo <strong>caducidad</strong>.
            </p>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Medicamento</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Existencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($caducados as $lote)
                            <tr class="table-danger">
                                <td>{{ $lote->medicamento->clave }} — {{ $lote->medicamento->nombre }}</td>
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->caducidad->format('d/m/Y') }}</td>
                                <td>{{ $lote->cantidad_actual }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Sin lotes caducados. ✔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
