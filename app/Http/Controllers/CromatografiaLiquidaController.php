<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaLiquida;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CromatografiaLiquidaController extends Controller
{
    public function index(Request $request, CromatografiaLiquida $cromatografiaLiquida): Response
    {
        return Inertia::render('CromatografiaLiquidas/Index', [
            'filters' => $request->all('search', 'trashed'),
            '
            ' => $cromatografiaLiquida->query()
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cl) => [
                    'id' => $cl->id,
                    'documento' => $cl->documento,
                    'fecha_hora' => $cl->fecha_hora,
                    'deleted_at' => $cl->deleted_at,
                    'pozo' => $cl->pozo ? $cl->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }
}
