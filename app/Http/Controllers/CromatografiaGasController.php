<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaGas;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'can' => [
                'restoreCromatografiaGas' => Auth::user()->can('restore', CromatografiaGas::class),
            ],
            'filters' => $request->all('search', 'trashed'),
            'cromatografiaGases' => $cromatografiaGas->query()
                ->orderBy('id', 'desc')
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($cg) => [
                    'id' => $cg->id,
                    'documento' => json_decode($cg->documento, true),
                    'fecha_hora' => $cg->fecha_hora,
                    'deleted_at' => $cg->deleted_at,
                    'pozo' => $cg->pozo ? $cg->pozo->only('nombre_pozo', 'deleted_at') : null,
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

    /**
     * Delete temporary an specific well cromatography.
     */
    public function destroy(CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $cromatografiaGas->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $cromatografiaGas->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }
}
