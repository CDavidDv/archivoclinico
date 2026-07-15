<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ReporteAlmacenController extends Controller
{
    public function existencias(Request $request)
    {
        $categoria = $request->query('categoria');

        $productos = Producto::conStockTotal()
            ->when($categoria, fn ($q) => $q->where('categoria', $categoria))
            ->orderBy('clave')
            ->get();

        return view('almacen.existencias', [
            'productos'  => $productos,
            'categoria'  => $categoria,
            'categorias' => Producto::CATEGORIAS,
        ]);
    }
}
