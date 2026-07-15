@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4 text-success fw-bold">
        Nuevo Documento
    </h1>

    {{-- Errores generales --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Se encontraron errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-header bg-success text-white fw-semibold">
            Registro de Documento
        </div>

        <div class="card-body">

            <form action="{{ route('documentos.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">

                    {{-- Expediente --}}
                    <div class="col-md-6 mb-3">

                        <label for="id_expediente"
                               class="form-label fw-semibold text-success">
                            Expediente
                        </label>

                        <select id="id_expediente"
                                name="id_expediente"
                                class="form-select border-success @error('id_expediente') is-invalid @enderror"
                                required>

                            <option value="">Seleccione expediente</option>

                            @foreach($expedientes as $exp)
                                <option value="{{ $exp->id }}"
                                    {{ old('id_expediente') == $exp->id ? 'selected' : '' }}>

                                    {{ $exp->codigo }}
                                    @if($exp->derechoHabiente)
                                        - {{ $exp->derechoHabiente->clave_completa }}
                                    @endif

                                </option>
                            @endforeach

                        </select>

                        @error('id_expediente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Fecha --}}
                    <div class="col-md-6 mb-3">

                        <label for="fecha_anexo"
                               class="form-label fw-semibold text-success">
                            Fecha de Anexo
                        </label>

                        <input id="fecha_anexo"
                               type="date"
                               name="fecha_anexo"
                               class="form-control border-success @error('fecha_anexo') is-invalid @enderror"
                               value="{{ old('fecha_anexo', date('Y-m-d')) }}"
                               required>

                        @error('fecha_anexo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                {{-- Nombre Documento --}}
                <div class="mb-3">

                    <label for="nombre_documento"
                           class="form-label fw-semibold text-success">
                        Nombre del Documento
                    </label>

                    <input id="nombre_documento"
                           type="text"
                           name="nombre_documento"
                           class="form-control border-success @error('nombre_documento') is-invalid @enderror"
                           value="{{ old('nombre_documento') }}"
                           placeholder="Ej. INE, Acta de nacimiento, Receta médica..."
                           required>

                    @error('nombre_documento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Archivo --}}
                <div class="mb-3">

                    <label for="ruta_archivo"
                           class="form-label fw-semibold text-success">
                        Archivo
                    </label>

                    <input id="ruta_archivo"
                           type="file"
                           name="ruta_archivo"
                           class="form-control border-success @error('ruta_archivo') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.webp,.mp4,.avi,.mov,.mkv,.txt,.csv,.zip,.rar"
                           required>

                    <div class="form-text">
                        Se permiten documentos, imágenes, videos y archivos comprimidos (máx. 50MB).
                    </div>

                    @error('ruta_archivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Vista previa --}}
                <div class="mb-4">

                    <strong class="text-success">
                        Vista previa
                    </strong>

                    <div class="mt-2 border rounded p-3 text-center">

                        <p class="text-muted mb-0" id="textoPreview">
                            Seleccione un archivo para ver vista previa
                        </p>

                        <img id="previewImagen"
                             class="img-fluid d-none"
                             style="max-height:300px">

                    </div>

                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">

                    <a href="{{ route('documentos.index') }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-success shadow-sm">
                        Guardar Documento
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

{{-- Script Vista previa --}}
<script>

document.getElementById('ruta_archivo').addEventListener('change', function(e) {

    const archivo = e.target.files[0];

    if (!archivo) return;

    const previewImg = document.getElementById('previewImagen');
    const texto = document.getElementById('textoPreview');

    const tipo = archivo.type;

    if (tipo.includes('image')) {

        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('d-none');
            texto.classList.add('d-none');
        }

        reader.readAsDataURL(archivo);

    } else {

        previewImg.classList.add('d-none');
        texto.classList.remove('d-none');

        texto.innerText = "Archivo seleccionado: " + archivo.name;

    }

});

</script>

@endsection