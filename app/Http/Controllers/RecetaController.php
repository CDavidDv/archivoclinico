<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DerechoHabiente;
use App\Models\DetalleReceta;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RecetaController extends Controller
{
    use \App\Http\Concerns\FiltraConsulta;

    public function index(Request $request)
    {
        $query = Receta::with(['derechoHabiente', 'medico'])
            ->withCount('detalles')
            ->latest('fecha_receta')
            ->latest('id');

        $filtros = $this->aplicarFiltros($query, $request, [
            'folio'           => 'like',
            'derechohabiente' => fn ($q, $v) => $q->whereHas('derechoHabiente', fn ($dh) => $dh
                ->where('nombre', 'like', "%{$v}%")
                ->orWhere('apellido_paterno', 'like', "%{$v}%")
                ->orWhere('apellido_materno', 'like', "%{$v}%")),
            'medico'          => fn ($q, $v) => $q->whereHas('medico', fn ($m) => $m
                ->where('nombre', 'like', "%{$v}%")
                ->orWhere('apellido_paterno', 'like', "%{$v}%")),
            'fecha_receta'    => 'date',
            'estatus'         => 'exact',
        ]);

        $orden = $this->aplicarOrden($query, $request, ['items' => 'detalles_count']);

        $recetas = $query->paginate(20)->withQueryString();

        return Inertia::render('Recetas/Index', compact('recetas', 'filtros', 'orden'));
    }

    public function create()
    {
        return Inertia::render('Recetas/Create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_derecho_habiente'       => 'required|exists:derecho_habientes,id',
            'id_medico'                 => 'required|exists:medicos,id',
            'fecha_receta'              => 'required|date',
            'diagnostico'               => 'nullable|string|max:255',
            'indicaciones'              => 'nullable|string',
            'detalles'                  => 'required|array|min:1',
            'detalles.*.id_medicamento' => [
                'required',
                'exists:medicamentos,id',
                'distinct',
            ],
            'detalles.*.cantidad_prescrita' => 'required|integer|min:1',
            'detalles.*.dosis'              => 'nullable|string|max:150',
        ]);

        $receta = DB::transaction(function () use ($validated, $request) {
            $receta = Receta::create([
                'id_derecho_habiente' => $validated['id_derecho_habiente'],
                'id_medico'           => $validated['id_medico'],
                'id_usuario'          => $request->user()->id,
                'fecha_receta'        => $validated['fecha_receta'],
                'diagnostico'         => $validated['diagnostico'] ?? null,
                'indicaciones'        => $validated['indicaciones'] ?? null,
                'estatus'             => Receta::ESTATUS_PENDIENTE,
            ]);

            foreach ($validated['detalles'] as $detalle) {
                DetalleReceta::create([
                    'id_receta'          => $receta->id,
                    'id_medicamento'     => $detalle['id_medicamento'],
                    'cantidad_prescrita' => $detalle['cantidad_prescrita'],
                    'dosis'              => $detalle['dosis'] ?? null,
                ]);
            }

            return $receta;
        });

        return redirect()
            ->route('recetas.show', $receta)
            ->with('success', "Receta {$receta->folio} registrada correctamente.");
    }

    public function show(Receta $receta)
    {
        $receta->load([
            'derechoHabiente',
            'medico',
            'usuario',
            'detalles.medicamento',
            'dispensaciones.usuario',
        ]);

        return Inertia::render('Recetas/Show', compact('receta'));
    }

    public function cancelar(Receta $receta)
    {
        if (!$receta->puedeCancelarse()) {
            return back()->withErrors([
                'error' => 'Solo se pueden cancelar recetas pendientes sin surtir.'
            ]);
        }

        $receta->update(['estatus' => Receta::ESTATUS_CANCELADA]);

        return redirect()
            ->route('recetas.index')
            ->with('success', "Receta {$receta->folio} cancelada.");
    }

    private function formData(): array
    {
        return [
            'derechoHabientes' => DerechoHabiente::orderBy('nombre')->get(),
            'medicos'          => Medico::orderBy('nombre')->get(),
            'medicamentos'     => Medicamento::activos()->orderBy('clave')->get(),
        ];
    }
}
