<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaGas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CromatografiaGasController extends Controller
{
    public function index(Request $request, CromatografiaGas $cromatografiaGas): Response
    {
        return Inertia::render('CromatografiaGases/Index', [
            'filters' => $request->all('search', 'trashed'),
            'cromatografiaGases' => $cromatografiaGas->query()
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cg) => [
                    'id' => $cg->id,
                    'documento' => $cg->documento,
                    'fecha_hora' => $cg->fecha_hora,
                    'deleted_at' => $cg->deleted_at,
                    'pozo' => $cg->pozo ? $cg->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }
}
