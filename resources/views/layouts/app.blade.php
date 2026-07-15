<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Integral — Farmacia, Almacén y Archivo Clínico</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-link {
            font-weight: 500;
        }

        .nav-link:hover {
            opacity: 0.85;
        }

        footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Navbar Institucional -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">

            <a class="navbar-brand" href="{{ route('dashboard') }}">
                Sistema Integral
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                @auth
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    {{-- ARCHIVO CLÍNICO --}}
                    @if(auth()->user()->hasRol('administrador', 'archivo', 'medico'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Archivo Clínico
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('derecho_habientes.index') }}">Derechohabientes</a></li>
                                <li><a class="dropdown-item" href="{{ route('expedientes.index') }}">Expedientes</a></li>
                                <li><a class="dropdown-item" href="{{ route('documentos.index') }}">Documentos</a></li>
                                @if(auth()->user()->hasRol('administrador', 'archivo'))
                                    <li><a class="dropdown-item" href="{{ route('prestamos.index') }}">Préstamos</a></li>
                                    <li><a class="dropdown-item" href="{{ route('medicos.index') }}">Médicos</a></li>
                                    <li><a class="dropdown-item" href="{{ route('personal_archivos.index') }}">Personal Archivo</a></li>
                                @endif
                                @if(auth()->user()->hasRol('administrador', 'medico'))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('recetas.index') }}">Recetas</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- FARMACIA --}}
                    @if(auth()->user()->hasRol('administrador', 'farmacia'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Farmacia
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('medicamentos.index') }}">Medicamentos</a></li>
                                <li><a class="dropdown-item" href="{{ route('recetas.index') }}">Recetas</a></li>
                                <li><a class="dropdown-item" href="{{ route('dispensaciones.index') }}">Dispensaciones</a></li>
                                <li><a class="dropdown-item" href="{{ route('entradas_farmacia.index') }}">Entradas</a></li>
                                <li><a class="dropdown-item" href="{{ route('salidas_farmacia.index') }}">Salidas</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('farmacia.alertas') }}">Alertas</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- ALMACÉN --}}
                    @if(auth()->user()->hasRol('administrador', 'almacen'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Almacén
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('productos.index') }}">Productos</a></li>
                                <li><a class="dropdown-item" href="{{ route('proveedores.index') }}">Proveedores</a></li>
                                <li><a class="dropdown-item" href="{{ route('entradas_almacen.index') }}">Entradas</a></li>
                                <li><a class="dropdown-item" href="{{ route('salidas_almacen.index') }}">Salidas</a></li>
                                <li><a class="dropdown-item" href="{{ route('transferencias.index') }}">Transferencias</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('almacen.existencias') }}">Reporte de Existencias</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- SOLICITUDES (integración) --}}
                    @if(auth()->user()->hasRol('administrador', 'archivo', 'farmacia', 'almacen'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('solicitudes.index') }}">
                                Solicitudes
                            </a>
                        </li>
                    @endif

                    {{-- ADMINISTRACIÓN --}}
                    @if(auth()->user()->hasRol('administrador'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Administración
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                                <li><a class="dropdown-item" href="{{ route('movimientos.index') }}">Auditoría</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- USUARIO --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->user()->nombre_usuario }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text text-muted">
                                    Rol: {{ ucfirst(auth()->user()->rol) }}
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="container py-4">

        {{-- Alertas Globales --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger shadow-sm">
                {{ $errors->first('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <!-- Footer Institucional -->
    <footer class="bg-success text-white text-center py-3 mt-5 shadow-sm">
        <div class="container">
            <small>
                &copy; {{ date('Y') }} Sistema Integral |
                Farmacia · Almacén · Archivo Clínico
            </small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
