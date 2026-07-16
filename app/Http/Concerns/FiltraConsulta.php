<?php

namespace App\Http\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FiltraConsulta
{
    /**
     * Aplica filtros por columna a una consulta y devuelve los valores activos
     * para reenviarlos a Inertia (y mantener las cajas de búsqueda llenas).
     *
     * $defs mapea "campo de request" => especificación:
     *   'like'          → where campo like %valor%
     *   'like:columna'  → where columna like %valor%
     *   'exact'         → where campo = valor
     *   'exact:columna' → where columna = valor
     *   'date'          → whereDate campo = valor
     *   'date:columna'  → whereDate columna = valor
     *   Closure($query, $valor) → filtro personalizado (relaciones, etc.)
     *
     * @return array<string,string> filtros activos
     */
    protected function aplicarFiltros(Builder $query, Request $request, array $defs): array
    {
        $activos = [];

        foreach ($defs as $campo => $spec) {
            $valor = $request->query($campo);

            if ($valor === null || $valor === '') {
                continue;
            }

            $activos[$campo] = $valor;

            if ($spec instanceof Closure) {
                $spec($query, $valor);
                continue;
            }

            [$tipo, $columna] = array_pad(explode(':', $spec, 2), 2, null);
            $columna ??= $campo;

            match ($tipo) {
                'like'  => $query->where($columna, 'like', "%{$valor}%"),
                'exact' => $query->where($columna, $valor),
                'date'  => $query->whereDate($columna, $valor),
                default => null,
            };
        }

        return $activos;
    }
}
