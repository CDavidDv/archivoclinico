<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AlertaFarmaciaController;
use App\Http\Controllers\DerechoHabienteController;
use App\Http\Controllers\DispensacionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EntradaAlmacenController;
use App\Http\Controllers\EntradaFarmaciaController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PersonalArchivoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\ReporteAlmacenController;
use App\Http\Controllers\SalidaAlmacenController;
use App\Http\Controllers\SalidaFarmaciaController;
use App\Http\Controllers\SolicitudAbastecimientoController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
| Login/logout/reset gestionado por Fortify (Jetstream). El campo de
| usuario es `nombre_usuario` (ver config/fortify.php).
*/

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {

    Route::redirect('/', '/dashboard');

    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | ARCHIVO CLÍNICO — gestión (archivo)
    | Se registra ANTES que el grupo de consulta para que las rutas
    | estáticas (create) ganen a las rutas comodín ({id}).
    |----------------------------------------------------------------------
    */
    Route::middleware('role:archivo')->group(function () {
        Route::resources([
            'derecho_habientes' => DerechoHabienteController::class,
            'expedientes'       => ExpedienteController::class,
            'documentos'        => DocumentoController::class,
        ], ['except' => ['index', 'show']]);

        Route::resources([
            'medicos'           => MedicoController::class,
            'personal_archivos' => PersonalArchivoController::class,
            'prestamos'         => PrestamoController::class,
        ]);

        Route::get('prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])
            ->name('prestamos.devolver');
        Route::put('prestamos/{prestamo}/devolver', [PrestamoController::class, 'procesarDevolucion'])
            ->name('prestamos.procesarDevolucion');
    });

    /*
    |----------------------------------------------------------------------
    | ARCHIVO CLÍNICO — consulta (archivo, medico)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:archivo,medico')->group(function () {
        Route::resources([
            'derecho_habientes' => DerechoHabienteController::class,
            'expedientes'       => ExpedienteController::class,
            'documentos'        => DocumentoController::class,
        ], ['only' => ['index', 'show']]);
    });

    /*
    |----------------------------------------------------------------------
    | RECETAS — médico crea/cancela, farmacia ve la cola
    |----------------------------------------------------------------------
    */
    Route::middleware('role:medico')->group(function () {
        Route::resource('recetas', RecetaController::class)
            ->only(['create', 'store']);
        Route::put('recetas/{receta}/cancelar', [RecetaController::class, 'cancelar'])
            ->name('recetas.cancelar');
    });

    Route::middleware('role:medico,farmacia')->group(function () {
        Route::resource('recetas', RecetaController::class)
            ->only(['index', 'show']);
    });

    /*
    |----------------------------------------------------------------------
    | FARMACIA
    |----------------------------------------------------------------------
    */
    Route::middleware('role:farmacia')->group(function () {
        Route::resource('medicamentos', MedicamentoController::class);

        Route::resources([
            'entradas_farmacia' => EntradaFarmaciaController::class,
            'salidas_farmacia'  => SalidaFarmaciaController::class,
        ], ['only' => ['index', 'create', 'store', 'show']]);

        Route::resource('dispensaciones', DispensacionController::class)
            ->only(['index', 'show'])
            ->parameters(['dispensaciones' => 'dispensacion']);

        Route::get('recetas/{receta}/dispensar', [DispensacionController::class, 'create'])
            ->name('dispensaciones.create');
        Route::post('recetas/{receta}/dispensar', [DispensacionController::class, 'store'])
            ->name('dispensaciones.store');

        Route::get('farmacia/alertas', [AlertaFarmaciaController::class, 'index'])
            ->name('farmacia.alertas');
    });

    /*
    |----------------------------------------------------------------------
    | SOLICITUDES DE ABASTECIMIENTO
    |----------------------------------------------------------------------
    */
    Route::middleware('role:farmacia,archivo')->group(function () {
        Route::resource('solicitudes', SolicitudAbastecimientoController::class)
            ->only(['create', 'store'])
            ->parameters(['solicitudes' => 'solicitud']);
    });

    Route::middleware('role:farmacia,archivo,almacen')->group(function () {
        Route::resource('solicitudes', SolicitudAbastecimientoController::class)
            ->only(['index', 'show'])
            ->parameters(['solicitudes' => 'solicitud']);
    });

    Route::middleware('role:almacen')->group(function () {
        Route::put('solicitudes/{solicitud}/aprobar', [SolicitudAbastecimientoController::class, 'aprobar'])
            ->name('solicitudes.aprobar');
        Route::put('solicitudes/{solicitud}/rechazar', [SolicitudAbastecimientoController::class, 'rechazar'])
            ->name('solicitudes.rechazar');
    });

    /*
    |----------------------------------------------------------------------
    | ALMACÉN
    |----------------------------------------------------------------------
    */
    Route::middleware('role:almacen')->group(function () {
        Route::resources([
            'proveedores' => ProveedorController::class,
            'productos'   => ProductoController::class,
        ]);

        Route::resources([
            'entradas_almacen' => EntradaAlmacenController::class,
            'salidas_almacen'  => SalidaAlmacenController::class,
            'transferencias'   => TransferenciaController::class,
        ], ['only' => ['index', 'create', 'store', 'show']]);

        Route::get('almacen/reportes/existencias', [ReporteAlmacenController::class, 'existencias'])
            ->name('almacen.existencias');
    });

    /*
    |----------------------------------------------------------------------
    | ADMINISTRACIÓN
    |----------------------------------------------------------------------
    */
    Route::middleware('role:administrador')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('movimientos', MovimientoController::class)
            ->only(['index', 'show']);
    });

});
