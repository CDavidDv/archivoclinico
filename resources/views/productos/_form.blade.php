<div class="row g-3">

    <div class="col-md-4">
        <label for="clave" class="form-label">Clave</label>
        <input type="text" name="clave" id="clave" maxlength="30"
               value="{{ old('clave', $producto?->clave) }}"
               class="form-control @error('clave') is-invalid @enderror" required>
        @error('clave')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre"
               value="{{ old('nombre', $producto?->nombre) }}"
               class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="categoria" class="form-label">Categoría</label>
        <select name="categoria" id="categoria"
                class="form-select @error('categoria') is-invalid @enderror" required>
            <option value="">Selecciona...</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria }}"
                    @selected(old('categoria', $producto?->categoria) === $categoria)>
                    {{ ucfirst($categoria) }}
                </option>
            @endforeach
        </select>
        @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="unidad_medida" class="form-label">Unidad de medida</label>
        <input type="text" name="unidad_medida" id="unidad_medida" maxlength="30"
               value="{{ old('unidad_medida', $producto?->unidad_medida ?? 'pieza') }}"
               class="form-control @error('unidad_medida') is-invalid @enderror" required>
        @error('unidad_medida')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="stock_minimo" class="form-label">Stock mínimo</label>
        <input type="number" name="stock_minimo" id="stock_minimo" min="0"
               value="{{ old('stock_minimo', $producto?->stock_minimo ?? 0) }}"
               class="form-control @error('stock_minimo') is-invalid @enderror" required>
        @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="2"
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto?->descripcion) }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" id="activo" value="1"
                   class="form-check-input"
                   @checked(old('activo', $producto?->activo ?? true))>
            <label for="activo" class="form-check-label">Activo</label>
        </div>
    </div>

</div>
