<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LoteFarmacia;
use App\Models\Medicamento;

class AlertaFarmaciaController extends Controller
{
    public function index()
    {
        $bajoMinimo = Medicamento::activos()
            ->conStockTotal()
            ->get()
            ->filter(fn ($m) => ($m->stock_total ?? 0) <= $m->stock_minimo)
            ->values();

        $porCaducar = LoteFarmacia::with('medicamento')
            ->porCaducar(30)
            ->orderBy('caducidad')
            ->get();

        $caducados = LoteFarmacia::with('medicamento')
            ->caducados()
            ->orderBy('caducidad')
            ->get();

        return Inertia::render('Farmacia/Alertas', compact('bajoMinimo', 'porCaducar', 'caducados'));
    }
}
