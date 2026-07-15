<div class="row g-3">

    <div class="col-md-6">
        <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
        <input type="text"
               name="nombre_usuario"
               id="nombre_usuario"
               value="{{ old('nombre_usuario', $usuario?->nombre_usuario) }}"
               class="form-control @error('nombre_usuario') is-invalid @enderror"
               required>
        @error('nombre_usuario')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email"
               name="email"
               id="email"
               value="{{ old('email', $usuario?->email) }}"
               class="form-control @error('email') is-invalid @enderror"
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text"
               name="telefono"
               id="telefono"
               value="{{ old('telefono', $usuario?->telefono) }}"
               class="form-control @error('telefono') is-invalid @enderror">
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="rol" class="form-label">Rol</label>
        <select name="rol"
                id="rol"
                class="form-select @error('rol') is-invalid @enderror"
                required>
            <option value="">Selecciona un rol...</option>
            @foreach($roles as $rol)
                <option value="{{ $rol }}"
                    @selected(old('rol', $usuario?->rol) === $rol)>
                    {{ ucfirst($rol) }}
                </option>
            @endforeach
        </select>
        @error('rol')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password"
               name="password"
               id="password"
               class="form-control @error('password') is-invalid @enderror"
               @if(!$usuario) required @endif>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input type="password"
               name="password_confirmation"
               id="password_confirmation"
               class="form-control"
               @if(!$usuario) required @endif>
    </div>

</div>
