<div class="row g-3">

    <div class="col-md-4">
        <label for="clave" class="form-label">Clave</label>
        <input type="text" name="clave" id="clave" maxlength="30"
               value="{{ old('clave', $medicamento?->clave) }}"
               class="form-control @error('clave') is-invalid @enderror" required>
        @error('clave')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre"
               value="{{ old('nombre', $medicamento?->nombre) }}"
               class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="sustancia_activa" class="form-label">Sustancia activa</label>
        <input type="text" name="sustancia_activa" id="sustancia_activa"
               value="{{ old('sustancia_activa', $medicamento?->sustancia_activa) }}"
               class="form-control @error('sustancia_activa') is-invalid @enderror" required>
        @error('sustancia_activa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="presentacion" class="form-label">Presentación</label>
        <input type="text" name="presentacion" id="presentacion" maxlength="100"
               value="{{ old('presentacion', $medicamento?->presentacion) }}"
               placeholder="Tabletas 500 mg, caja c/20"
               class="form-control @error('presentacion') is-invalid @enderror" required>
        @error('presentacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="unidad_medida" class="form-label">Unidad de medida</label>
        <input type="text" name="unidad_medida" id="unidad_medida" maxlength="30"
               value="{{ old('unidad_medida', $medicamento?->unidad_medida ?? 'pieza') }}"
               class="form-control @error('unidad_medida') is-invalid @enderror" required>
        @error('unidad_medida')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="stock_minimo" class="form-label">Stock mínimo</label>
        <input type="number" name="stock_minimo" id="stock_minimo" min="0"
               value="{{ old('stock_minimo', $medicamento?->stock_minimo ?? 0) }}"
               class="form-control @error('stock_minimo') is-invalid @enderror" required>
        @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="id_producto" class="form-label">Producto de almacén vinculado</label>
        <select name="id_producto" id="id_producto"
                class="form-select @error('id_producto') is-invalid @enderror">
            <option value="">Sin vínculo</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->id }}"
                    @selected(old('id_producto', $medicamento?->id_producto) == $producto->id)>
                    {{ $producto->clave }} — {{ $producto->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_producto')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Necesario para recibir transferencias del almacén.</div>
    </div>

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" id="activo" value="1"
                   class="form-check-input"
                   @checked(old('activo', $medicamento?->activo ?? true))>
            <label for="activo" class="form-check-label">Activo</label>
        </div>
    </div>

</div>
