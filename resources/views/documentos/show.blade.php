@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Detalle del Documento
    </h1>

    @php
        $expediente = $documento->expediente;
        $derecho = $documento->derechoHabiente;
        $ruta = $documento->url;
        $tipo = strtolower($documento->tipo_archivo ?? '');
    @endphp

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Información del Documento
        </div>

        <div class="card-body">

            {{-- Nombre --}}
            <h4 class="fw-bold text-success mb-3">
                {{ $documento->nombre_documento }}
            </h4>

            <hr>

            <div class="row">

                {{-- Expediente --}}
                <div class="col-md-6 mb-3">
                    <strong class="text-success d-block">Expediente:</strong>

                    {{ $expediente->codigo 
                        ?? $derecho->clave_completa 
                        ?? 'N/A' }}
                </div>

                {{-- Fecha --}}
                <div class="col-md-6 mb-3">
                    <strong class="text-success d-block">Fecha Anexo:</strong>

                    {{ $documento->fecha_anexo?->format('d/m/Y') ?? '-' }}
                </div>

            </div>

            {{-- Archivo --}}
            <div class="mt-4">

                <strong class="text-success d-block mb-3">
                    Documento adjunto
                </strong>

                @if($documento->existe_archivo)

                    {{-- Botones --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">

                        <a href="{{ $ruta }}"
                           target="_blank"
                           class="btn btn-outline-success btn-sm">
                            Abrir {{ strtoupper($tipo) }}
                        </a>

                        <a href="{{ $ruta }}"
                           download
                           class="btn btn-success btn-sm">
                            Descargar
                        </a>

                    </div>

                    {{-- Vista previa dinámica --}}
                    
                    {{-- 🖼️ IMÁGENES --}}
                    @if(in_array($tipo, ['jpg','jpeg','png','gif','webp','bmp']))
                        <div class="text-center">
                            <img src="{{ $ruta }}"
                                 class="img-fluid rounded border border-success"
                                 style="max-height:600px;">
                        </div>

                    {{-- 📄 PDF --}}
                    @elseif($tipo === 'pdf')
                        <iframe
                            src="{{ $ruta }}"
                            width="100%"
                            height="600"
                            class="rounded border border-success">
                        </iframe>

                    {{-- 🎥 VIDEO --}}
                    @elseif(in_array($tipo, ['mp4','avi','mov','mkv']))
                        <video controls
                               class="w-100 rounded border border-success"
                               style="max-height:600px;">
                            <source src="{{ $ruta }}" type="video/{{ $tipo }}">
                            Tu navegador no soporta video.
                        </video>

                    {{-- ❌ OTROS --}}
                    @else
                        <div class="alert alert-warning">
                            Vista previa no disponible para archivos tipo 
                            <strong>{{ strtoupper($tipo) }}</strong>.
                        </div>
                    @endif

                @else

                    <div class="alert alert-danger">
                        El archivo no existe físicamente en el servidor.
                    </div>

                @endif

            </div>

        </div>
    </div>

    {{-- Botones --}}
    <div class="mt-4 d-flex gap-2">

        <a href="{{ route('documentos.index') }}"
           class="btn btn-outline-secondary">
            Volver
        </a>

        <a href="{{ route('documentos.edit', $documento->id) }}"
           class="btn btn-success">
            Editar Documento
        </a>

    </div>

</div>
@endsection