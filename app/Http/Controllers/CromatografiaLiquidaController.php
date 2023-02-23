<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaLiquida;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'can' => [
                'restoreCromatografiaLiquida' => Auth::user()->can('restore', CromatografiaLiquida::class),
            ],
            'filters' => $request->all('search', 'trashed'),
            'cromatografiaLiquidas' => $cromatografiaLiquida->query()
                ->orderBy('id', 'desc')
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cl) => [
                    'id' => $cl->id,
                    'documento' => json_decode($cl->documento, true),
                    'fecha_hora' => $cl->fecha_hora,
                    'deleted_at' => $cl->deleted_at,
                    'pozo' => $cl->pozo ? $cl->pozo->only('nombre_pozo', 'deleted_at') : null,
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

    /**
     * Delete temporary an specific well cromatography.
     */
    public function destroy(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }
}
