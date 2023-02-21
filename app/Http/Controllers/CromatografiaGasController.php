<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaGas;
use App\Models\Pozo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CromatografiaGasController extends Controller
{
    /**
     * Display a list of well chromatographies.
     */
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
                    'documento' => json_decode($cg->documento, true),
                    'fecha_hora' => $cg->fecha_hora,
                    'deleted_at' => $cg->deleted_at,
                    'pozo' => $cg->pozo ? $cg->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }

    /**
     * Show the form for uploading a new well chromatographies.
     */
    public function create(Pozo $pozo): Response
    {
        return Inertia::render('CromatografiaGases/Create', [
            'pozos' => $pozo->query()
                ->orderBy('id', 'desc')
                ->get()
                ->map
                ->only('id', 'nombre_pozo'),
        ]);
    }
}
