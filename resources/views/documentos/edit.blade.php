@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Editar Documento
    </h1>

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Actualización de Documento
        </div>

        <div class="card-body">

            {{-- Información actual --}}
            <div class="alert alert-light border mb-4">
                <strong>Documento actual:</strong> {{ $documento->nombre_documento }} <br>
                <strong>Fecha de anexo:</strong> 
                {{ $documento->fecha_anexo?->format('d/m/Y') ?? 'N/A' }}
            </div>

            <form action="{{ route('documentos.update', $documento->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Expediente --}}
                <div class="mb-3">
                    <label for="id_expediente" class="form-label fw-semibold text-success">
                        Expediente
                    </label>

                    <select id="id_expediente"
                            name="id_expediente"
                            class="form-select border-success @error('id_expediente') is-invalid @enderror"
                            required>

                        <option value="">Seleccione expediente</option>

                        @foreach($expedientes as $exp)
                            <option value="{{ $exp->id }}"
                                {{ old('id_expediente', $documento->id_expediente) == $exp->id ? 'selected' : '' }}>
                                {{ $exp->codigo }}
                            </option>
                        @endforeach

                    </select>

                    @error('id_expediente')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nombre Documento --}}
                <div class="mb-3">
                    <label for="nombre_documento" class="form-label fw-semibold text-success">
                        Nombre del Documento
                    </label>

                    <input id="nombre_documento"
                           type="text"
                           name="nombre_documento"
                           class="form-control border-success @error('nombre_documento') is-invalid @enderror"
                           value="{{ old('nombre_documento', $documento->nombre_documento) }}"
                           required>

                    @error('nombre_documento')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Archivo --}}
                <div class="mb-3">
                    <label for="ruta_archivo" class="form-label fw-semibold text-success">
                        Reemplazar Archivo (opcional)
                    </label>

                    <input id="ruta_archivo"
                           type="file"
                           name="ruta_archivo"
                           class="form-control border-success @error('ruta_archivo') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.webp,.mp4,.avi,.mov,.mkv,.txt,.csv,.zip,.rar">

                    <div class="form-text">
                        Si subes un archivo nuevo, el archivo anterior será reemplazado.
                    </div>

                    @error('ruta_archivo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Archivo actual --}}
                    <div class="mt-3">

                        @if($documento->existe_archivo)

                            <div class="border rounded p-3 bg-light">

                                <strong class="text-success">Archivo actual:</strong>

                                <div class="mt-2 d-flex gap-2">

                                    <a href="{{ $documento->url }}"
                                       target="_blank"
                                       class="btn btn-outline-success btn-sm">
                                        Ver {{ strtoupper($documento->tipo_archivo ?? 'archivo') }}
                                    </a>

                                    <a href="{{ $documento->url }}"
                                       download
                                       class="btn btn-success btn-sm">
                                        Descargar
                                    </a>

                                </div>

                            </div>

                        @else

                            <div class="alert alert-warning mt-2">
                                No hay archivo adjunto o el archivo no existe físicamente.
                            </div>

                        @endif

                    </div>
                </div>

                {{-- Fecha --}}
                <div class="mb-4">
                    <label for="fecha_anexo" class="form-label fw-semibold text-success">
                        Fecha de Anexo
                    </label>

                    <input id="fecha_anexo"
                           type="date"
                           name="fecha_anexo"
                           class="form-control border-success @error('fecha_anexo') is-invalid @enderror"
                           value="{{ old('fecha_anexo', $documento->fecha_anexo?->format('Y-m-d')) }}"
                           required>

                    @error('fecha_anexo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">

                    <a href="{{ route('documentos.show', $documento->id) }}"
                       class="btn btn-outline-secondary">
                        Volver
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">
                        Actualizar Documento
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@endsection