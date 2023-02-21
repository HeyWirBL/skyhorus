<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaLiquida;
use App\Models\Pozo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CromatografiaLiquidaController extends Controller
{
    /**
     * Display a list of well chromatographies.
     */
    public function index(Request $request, CromatografiaLiquida $cromatografiaLiquida): Response
    {
        return Inertia::render('CromatografiaLiquidas/Index', [
            'filters' => $request->all('search', 'trashed'),
            'cromatografiaLiquidas' => $cromatografiaLiquida->query()
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cl) => [
                    'id' => $cl->id,
                    'documento' => json_decode($cl->documento, true),
                    'fecha_hora' => $cl->fecha_hora,
                    'deleted_at' => $cl->deleted_at,
                    'pozo' => $cl->pozo ? $cl->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }

     /**
     * Show the form for uploading a new well chromatographies.
     */
    public function create(Pozo $pozo): Response
    {
        return Inertia::render('CromatografiaLiquidas/Create', [
            'pozos' => $pozo->query()
                ->orderBy('id', 'desc')
                ->get()
                ->map
                ->only('id', 'nombre_pozo'),
        ]);
    }
}
