<div class="row g-3">

    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre"
               value="{{ old('nombre', $proveedor?->nombre) }}"
               class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="rfc" class="form-label">RFC</label>
        <input type="text" name="rfc" id="rfc" maxlength="13"
               value="{{ old('rfc', $proveedor?->rfc) }}"
               class="form-control @error('rfc') is-invalid @enderror">
        @error('rfc')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" name="telefono" id="telefono" maxlength="20"
               value="{{ old('telefono', $proveedor?->telefono) }}"
               class="form-control @error('telefono') is-invalid @enderror">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email"
               value="{{ old('email', $proveedor?->email) }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" name="direccion" id="direccion"
               value="{{ old('direccion', $proveedor?->direccion) }}"
               class="form-control @error('direccion') is-invalid @enderror">
        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" id="activo" value="1"
                   class="form-check-input"
                   @checked(old('activo', $proveedor?->activo ?? true))>
            <label for="activo" class="form-check-label">Activo</label>
        </div>
    </div>

</div>
