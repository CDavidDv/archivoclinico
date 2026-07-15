@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">Dispensar Receta {{ $receta->folio }}</h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            {{ $receta->derechoHabiente->nombre }}
            {{ $receta->derechoHabiente->apellido_paterno }}
            — Dr(a). {{ $receta->medico->nombre }}
        </div>

        <div class="card-body">

            @if($errors->has('detalles') || $errors->has('receta'))
                <div class="alert alert-danger">
                    {{ $errors->first('detalles') ?: $errors->first('receta') }}
                </div>
            @endif

            <p class="text-muted small">
                El descuento se hace automáticamente por lote (FEFO: primero lo que caduca antes).
                Los lotes caducados no se consideran.
            </p>

            <form method="POST" action="{{ route('dispensaciones.store', $receta) }}">
                @csrf

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>Medicamento</th>
                                <th>Dosis</th>
                                <th>Pendiente</th>
                                <th>Stock disponible</th>
                                <th style="max-width: 140px;">Surtir ahora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receta->detalles as $detalle)
                                @php
                                    $pendiente  = $detalle->cantidadPendiente();
                                    $disponible = $stockDisponible[$detalle->id] ?? 0;
                                    $maximo     = min($pendiente, $disponible);
                                @endphp
                                <tr>
                                    <td>{{ $detalle->medicamento->clave }} — {{ $detalle->medicamento->nombre }}</td>
                                    <td>{{ $detalle->dosis ?? '—' }}</td>
                                    <td>{{ $pendiente }}</td>
                                    <td>
                                        <span class="badge {{ $disponible < $pendiente ? 'bg-warning text-dark' : 'bg-success' }}">
                                            {{ $disponible }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="cantidades[{{ $detalle->id }}]"
                                               class="form-control form-control-sm"
                                               min="0"
                                               max="{{ $maximo }}"
                                               value="{{ old('cantidades.' . $detalle->id, $maximo) }}"
                                               @disabled($pendiente === 0)>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2"
                              class="form-control">{{ old('observaciones') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Registrar Dispensación</button>
                    <a href="{{ route('recetas.show', $receta) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
