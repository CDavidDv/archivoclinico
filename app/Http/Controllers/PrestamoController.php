<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Prestamo;
use App\Models\Expediente;
use App\Models\Medico;
use App\Models\PersonalArchivo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    /* =====================================================
       LISTADO
    ===================================================== */

    public function index()
    {
        $prestamos = Prestamo::with([
                'expediente.derechoHabiente',
                'medico',
                'entregadoPor',
                'recibidoPor'
            ])
            ->latest('fecha_salida')
            ->get();

        return Inertia::render('Prestamos/Index', compact('prestamos'));
    }

    /* =====================================================
       CREAR
    ===================================================== */

    public function create()
    {
        return Inertia::render('Prestamos/Create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($this->tienePrestamoActivo($validated['id_expediente'])) {
            return back()
                ->withErrors(['id_expediente' => 'Este expediente ya está prestado.'])
                ->withInput();
        }

        $validated['dias_asignados'] = $this->calcularDias(
            $validated['fecha_salida'],
            $validated['fecha_regreso']
        );

        $validated['estatus'] = Prestamo::ESTATUS_PENDIENTE;

        Prestamo::create($validated);

        return redirect()
            ->route('prestamos.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    /* =====================================================
       MOSTRAR
    ===================================================== */

    public function show(Prestamo $prestamo)
    {
        $prestamo->load([
            'expediente.derechoHabiente',
            'medico',
            'entregadoPor',
            'recibidoPor'
        ]);

        return Inertia::render('Prestamos/Show', compact('prestamo'));
    }

    /* =====================================================
       EDITAR
    ===================================================== */

    public function edit(Prestamo $prestamo)
    {
        if ($prestamo->isDevuelto()) {
            return redirect()
                ->route('prestamos.index')
                ->withErrors(['error' => 'No se puede editar un préstamo ya devuelto.']);
        }

        return Inertia::render('Prestamos/Edit', array_merge(
            compact('prestamo'),
            $this->formData()
        ));
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        if ($prestamo->isDevuelto()) {
            return back()->withErrors([
                'error' => 'No se puede modificar un préstamo ya devuelto.'
            ]);
        }

        $validated = $this->validateData($request);

        // Validar que no exista otro préstamo activo del mismo expediente
        if (
            $validated['id_expediente'] != $prestamo->id_expediente &&
            $this->tienePrestamoActivo($validated['id_expediente'])
        ) {
            return back()
                ->withErrors(['id_expediente' => 'Este expediente ya está prestado.'])
                ->withInput();
        }

        $validated['dias_asignados'] = $this->calcularDias(
            $validated['fecha_salida'],
            $validated['fecha_regreso']
        );

        $prestamo->update($validated);

        return redirect()
            ->route('prestamos.index')
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    /* =====================================================
       DEVOLVER (GET)
    ===================================================== */

    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->isDevuelto()) {
            return redirect()
                ->route('prestamos.index')
                ->withErrors(['error' => 'Este préstamo ya fue devuelto.']);
        }

        $prestamo->load([
            'expediente.derechoHabiente',
            'medico'
        ]);

        return Inertia::render('Prestamos/Devolver', [
            'prestamo' => $prestamo,
            'personalArchivos' => PersonalArchivo::orderBy('nombre')->get()
        ]);
    }

    /* =====================================================
       PROCESAR DEVOLUCIÓN (PUT)
    ===================================================== */

    public function procesarDevolucion(Request $request, Prestamo $prestamo)
    {
        if ($prestamo->isDevuelto()) {
            return back()->withErrors([
                'error' => 'Este préstamo ya fue devuelto.'
            ]);
        }

        $validated = $request->validate([
            'recibido_por' => 'required|exists:personal_archivos,id',
        ]);

        $prestamo->update([
            'recibido_por' => $validated['recibido_por'],
            'fecha_devolucion_real' => now(),
            'estatus' => Prestamo::ESTATUS_DEVUELTO,
        ]);

        return redirect()
            ->route('prestamos.index')
            ->with('success', 'Préstamo devuelto correctamente.');
    }

    /* =====================================================
       ELIMINAR
    ===================================================== */

    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->isDevuelto()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar un préstamo ya devuelto.'
            ]);
        }

        $prestamo->delete();

        return redirect()
            ->route('prestamos.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }

    /* =====================================================
       MÉTODOS PRIVADOS
    ===================================================== */

    private function validateData(Request $request): array
    {
        return $request->validate([
            'id_expediente'  => 'required|exists:expedientes,id',
            'id_medico'      => 'required|exists:medicos,id',
            'entregado_por'  => 'required|exists:personal_archivos,id',
            'area_destino'   => 'required|string|max:150',
            'fecha_salida'   => 'required|date',
            'fecha_regreso'  => 'required|date|after:fecha_salida',
        ]);
    }

    private function calcularDias($salida, $regreso): int
    {
        return Carbon::parse($salida)
            ->diffInDays(Carbon::parse($regreso));
    }

    private function tienePrestamoActivo(int $expedienteId): bool
    {
        return Prestamo::where('id_expediente', $expedienteId)
            ->where('estatus', Prestamo::ESTATUS_PENDIENTE)
            ->exists();
    }

    private function formData(): array
    {
        return [
            'expedientes'      => Expediente::orderBy('codigo')->get(),
            'medicos'          => Medico::orderBy('nombre')->get(),
            'personalArchivos' => PersonalArchivo::orderBy('nombre')->get(),
        ];
    }
}