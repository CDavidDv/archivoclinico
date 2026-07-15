<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use App\Models\Transferencia;
use App\Services\TransferenciaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransferenciaController extends Controller
{
    public function __construct(
        private readonly TransferenciaService $servicio
    ) {}

    public function index()
    {
        $transferencias = Transferencia::with(['usuario', 'solicitud'])
            ->withCount('detalles')
            ->latest('fecha')
            ->latest('id')
            ->paginate(20);

        return Inertia::render('Transferencias/Index', compact('transferencias'));
    }

    public function create(Request $request)
    {
        $solicitud = null;

        if ($request->filled('solicitud')) {
            $solicitud = SolicitudAbastecimiento::with('detalles.producto')
                ->find($request->query('solicitud'));

            if ($solicitud && !$solicitud->puedeSurtirse()) {
                return redirect()
                    ->route('solicitudes.show', $solicitud)
                    ->withErrors(['error' => 'La solicitud no está aprobada; no puede surtirse.']);
            }
        }

        return Inertia::render('Transferencias/Create', [
            'productos' => Producto::activos()
                ->conStockTotal()
                ->orderBy('clave')
                ->get(),
            'solicitud' => $solicitud,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destino'                => ['required', Rule::in([Transferencia::DESTINO_FARMACIA, Transferencia::DESTINO_AREA])],
            'area_destino'           => 'required_if:destino,area|nullable|string|max:150',
            'id_solicitud'           => 'nullable|exists:solicitudes_abastecimiento,id',
            'fecha'                  => 'required|date',
            'observaciones'          => 'nullable|string',
            'detalles'               => 'required|array|min:1',
            'detalles.*.id_producto' => 'required|exists:productos,id',
            'detalles.*.cantidad'    => 'required|integer|min:1',
        ]);

        $transferencia = $this->servicio->transferir(
            $validated,
            $validated['detalles'],
            $request->user()
        );

        return redirect()
            ->route('transferencias.show', $transferencia)
            ->with('success', "Transferencia {$transferencia->folio} registrada correctamente.");
    }

    public function show(Transferencia $transferencia)
    {
        $transferencia->load([
            'usuario',
            'solicitud',
            'detalles.producto',
            'detalles.lote',
            'entradaFarmacia',
        ]);

        return Inertia::render('Transferencias/Show', compact('transferencia'));
    }
}
