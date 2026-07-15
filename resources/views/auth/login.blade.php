<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Sistema Integral</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="card shadow border-0" style="width: 100%; max-width: 420px;">

        <div class="card-header bg-success text-white text-center py-3">
            <h4 class="mb-0 fw-bold">Sistema Integral</h4>
            <small>Farmacia · Almacén · Archivo Clínico</small>
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Usuario</label>
                    <input type="text"
                           name="nombre_usuario"
                           id="nombre_usuario"
                           value="{{ old('nombre_usuario') }}"
                           class="form-control @error('nombre_usuario') is-invalid @enderror"
                           required
                           autofocus>
                    @error('nombre_usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="recordar" id="recordar" class="form-check-input">
                    <label for="recordar" class="form-check-label">Recordarme</label>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Iniciar Sesión
                </button>
            </form>

        </div>
    </div>

</body>
</html>
