<?php

namespace App\Http\Controllers;

use App\Models\ComponentePozo;
use App\Models\Pozo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ComponentePozoController extends Controller
{
    /**
     * Display a listing of wells components.
     */
    public function index(Request $request, ComponentePozo $componentePozo): Response
    {
        return Inertia::render('ComponentePozos/Index', [
            'filters' => $request->all('search', 'trashed'),
            'componentePozos' => $componentePozo->query()
                ->with('pozo')
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cp) => [
                    'id' => $cp->id,
                    'equipo_utilizado' => $cp->equipo_utilizado,
                    'nombre_componente' => $cp->nombre_componente,
                    'fecha_recep' => $cp->fecha_recep,
                    'deleted_at' => $cp->deleted_at,
                    'pozo' => $cp->pozo ? $cp->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }

    /**
     * Display the information for specific component well.
     */
    public function show(ComponentePozo $componentePozo): Response
    {
        return Inertia::render('ComponentePozos/Show', [
            'componentePozo' => $componentePozo
        ]);
    }
}
