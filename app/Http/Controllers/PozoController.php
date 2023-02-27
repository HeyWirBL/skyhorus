<?php

namespace App\Http\Controllers;

use App\Models\ComponentePozo;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PozoController extends Controller
{
    /**
     * Display a listing of wells.
     */
    public function index(Request $request, Pozo $pozo): Response
    {
        $can = [
            'createPozo' => Auth::user()->can('create', Pozo::class),
            'editPozo' => Auth::user()->can('update', Pozo::class),
            'restorePozo' => Auth::user()->can('restore', Pozo::class), 
            'deletePozo' => Auth::user()->can('delete', Pozo::class),
        ];

        $filters = $request->only('search', 'trashed');
        $query = $pozo->query()->orderByDesc('id')->filter($filters);

        $pozos = $query->paginate(10)->through(fn ($p) => [
            'id' => $p->id,
            'punto_muestreo' => $p->punto_muestreo,
            'fecha_hora' => $p->fecha_hora,
            'presion_kgcm2' => $p->presion_kgcm2,
            'presion_psi' => $p->presion_psi,
            'temp_C' => $p->temp_C,
            'temp_F' => $p->temp_F,
            'volumen_cm3' => $p->volumen_cm3,
            'volumen_lts' => $p->volumen_lts,
            'observaciones' => $p->observaciones,
            'identificador' => $p->identificador,
            'nombre_pozo' => $p->nombre_pozo,
            'deleted_at' => $p->deleted_at,
        ]);

        return Inertia::render('Pozos/Index', compact('pozos', 'can', 'filters'));
    }

    /**
     * Show the form for creating a new well.
     */
    public function create(): Response
    {
        return Inertia::render('Pozos/Create');
    }

    /**
     * Store a newly created well in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Pozo $pozo): RedirectResponse
    {
        $validated = $request->validate([
            'punto_muestreo' => 'required|max:150',
            'fecha_hora' => 'required|date',
            'identificador' => 'required|max:150',
            'presion_kgcm2' => 'required|max:150',
            'presion_psi' => 'required|max:150',
            'temp_C' => 'required|max:150',
            'temp_F' => 'required|max:150',
            'volumen_cm3' => 'required|max:150',
            'volumen_lts' => 'required|max:150',
            'observaciones' => 'nullable',
            'nombre_pozo' => 'required|max:150|',
        ]);
        
        $pozo->create($validated);

        return redirect(route('pozos'))->with('success', 'Pozo creado.');
    }

    /**
     * Display the information for specific well.
     */
    public function show(Pozo $pozo): Response
    {
        $user = Auth::user();
        $chartTable = DB::table('graf_lineas_mo');

        $can = [
            // Pozo Policy
            'editPozo' => $user->can('update', $pozo),
            'deletePozo' => $user->can('delete', $pozo),
            'restorePozo' => $user->can('restore', $pozo),
            // Componente Pozo Policy
            'createComponentePozo' => $user->can('create', ComponentePozo::class),
            'editComponentePozo' => $user->can('update', ComponentePozo::class),
            'deleteComponentePozo' => $user->can('delete', ComponentePozo::class),
            'restoreComponentePozo' => $user->can('restore', ComponentePozo::class),
        ];  

        $pozoData = $pozo->only([
            'id',
            'punto_muestreo',
            'fecha_hora',
            'identificador',
            'presion_kgcm2',
            'presion_psi',
            'temp_C',
            'temp_F',
            'volumen_cm3',
            'volumen_lts',
            'observaciones',
            'nombre_pozo', 
            'deleted_at',
        ]);

        $pozoData['docPozos'] = $pozo->docPozos()
            ->get(['id', 'documento', 'fecha_hora']);
        
        $pozoData['componentePozos'] = $pozo->componentePozos()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($componentePozo) => [
                'id' => $componentePozo->id,
                'dioxido_carbono' => $componentePozo->dioxido_carbono,
                'pe_dioxido_carbono' => $componentePozo->pe_dioxido_carbono,
                'mo_dioxido_carbono' => $componentePozo->mo_dioxido_carbono,
                'den_dioxido_carbono' => $componentePozo->den_dioxido_carbono,
                'acido_sulfidrico' => $componentePozo->acido_sulfidrico,
                'pe_acido_sulfidrico' => $componentePozo->pe_acido_sulfidrico,
                'mo_acido_sulfidrico' => $componentePozo->mo_acido_sulfidrico,
                'den_acido_sulfidrico' => $componentePozo->den_acido_sulfidrico,
                'nitrogeno' => $componentePozo->nitrogeno,
                'pe_nitrogeno' => $componentePozo->pe_nitrogeno,
                'mo_nitrogeno' => $componentePozo->mo_nitrogeno,
                'den_nitrogeno' => $componentePozo->den_nitrogeno,
                'metano' => $componentePozo->metano,
                'pe_metano' => $componentePozo->pe_metano,
                'mo_metano' => $componentePozo->mo_metano,
                'den_metano' => $componentePozo->den_metano,
                'etano' => $componentePozo->etano,
                'pe_etano' => $componentePozo->pe_etano,
                'mo_etano' => $componentePozo->mo_etano,
                'den_etano' => $componentePozo->den_etano,
                'propano' => $componentePozo->propano,
                'pe_propano' => $componentePozo->pe_propano,
                'mo_propano' => $componentePozo->mo_propano,
                'den_propano' => $componentePozo->den_propano,
                'iso_butano' => $componentePozo->iso_butano,
                'pe_iso_butano' => $componentePozo->pe_iso_butano,
                'mo_iso_butano' => $componentePozo->mo_iso_butano,
                'den_iso_butano' => $componentePozo->den_iso_butano,
                'n_butano' => $componentePozo->n_butano,
                'pe_n_butano' => $componentePozo->pe_n_butano,
                'mo_n_butano' => $componentePozo->mo_n_butano,
                'den_n_butano' => $componentePozo->den_n_butano,
                'iso_pentano' => $componentePozo->iso_pentano,
                'pe_iso_pentano' => $componentePozo->pe_iso_pentano,
                'mo_iso_pentano' => $componentePozo->mo_iso_pentano,
                'den_iso_pentano' => $componentePozo->den_iso_pentano,
                'n_pentano' => $componentePozo->n_pentano,
                'pe_n_pentano' => $componentePozo->pe_n_pentano,
                'mo_n_pentano' => $componentePozo->mo_n_pentano,
                'den_n_pentano' => $componentePozo->den_n_pentano,
                'n_exano' => $componentePozo->n_exano,
                'pe_n_exano' => $componentePozo->pe_n_exano,
                'mo_n_exano' => $componentePozo->mo_n_exano,
                'den_n_exano' => $componentePozo->den_n_exano,                
                'fecha_recep' => $componentePozo->fecha_recep,
                'pozo_id' => $componentePozo->pozo_id,
                'fecha_analisis' => $componentePozo->fecha_analisis,
                'no_determinacion' => $componentePozo->no_determinacion,
                'equipo_utilizado' => $componentePozo->equipo_utilizado,
                'met_laboratorio' => $componentePozo->met_laboratorio,
                'observaciones' => $componentePozo->observaciones,
                'nombre_componente' => $componentePozo->nombre_componente,
                'fecha_muestreo' => $componentePozo->fecha_muestreo,
                'deleted_at' => $componentePozo->deleted_at,
                'quimicosData' => $chartTable
                    ->where('idComPozo', $componentePozo->id)->get(),
            ]);

        $pozoData['cromatografiaGases'] = $pozo->cromatografiaGases()
            ->get(['id', 'documento', 'fecha_hora']);

        $pozoData['cromatografiaLiquidas'] = $pozo->cromatografiaLiquidas()
            ->get(['id', 'documento', 'fecha_hora']);

        return Inertia::render('Pozos/Show', [            
            'pozo' => array_merge($pozoData, ['can' => $can]),
        ]);
    }

    /**
     * Show the form for editing an specific well.
     */
    public function edit(Pozo $pozo): Response
    {
        return Inertia::render('Pozos/Edit', [
            'pozo' => [
                'id' => $pozo->id,
                'punto_muestreo' => $pozo->punto_muestreo,
                'fecha_hora' => $pozo->fecha_hora,
                'identificador' => $pozo->identificador,
                'presion_kgcm2' => $pozo->presion_kgcm2,
                'presion_psi' => $pozo->presion_psi,
                'temp_C' => $pozo->temp_C,
                'temp_F' => $pozo->temp_F,
                'volumen_cm3' => $pozo->volumen_cm3,
                'volumen_lts' => $pozo->volumen_lts,
                'observaciones' => $pozo->observaciones,
                'nombre_pozo' => $pozo->nombre_pozo,    
                'deleted_at' => $pozo->deleted_at,            
            ],    
        ]);
    }

    /**
     * Update the well's information.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Pozo $pozo): RedirectResponse
    {
        $validated = $request->validate([
            'punto_muestreo' => ['required', 'max:150'],
            'fecha_hora' => ['required', 'date'],
            'identificador' => ['nullable', 'max:150'],
            'presion_kgcm2' => ['required', 'max:150'],
            'presion_psi' => ['required', 'max:150'],
            'temp_C' => ['required', 'max:150'],
            'temp_F' => 'required|max:150',
            'volumen_cm3' => ['required', 'max:150'],
            'volumen_lts' => ['required', 'max:150'],
            'observaciones' => ['nullable'],
            'nombre_pozo' => ['required', 'max:150'],
        ]);

        $pozo->update($validated);

        return Redirect::back()->with('success', 'Pozo actualizado.');
    }

    /**
     * Delete temporary an specific well.
     */
    public function destroy(Pozo $pozo): RedirectResponse
    {
        $pozo->delete();
        return Redirect::back()->with('success', 'Pozo eliminado.');
    }

    /**
     * Delete multiple wells.
     */
    public function destroyAll(Request $request, Pozo $pozo): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $pozo->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Pozos eliminados.');
    }


    /**
     * Restore the well.
     */
    public function restore(Pozo $pozo): RedirectResponse
    {
        $pozo->restore();
        return Redirect::back()->with('success', 'Pozo restablecido.');
    }

    /**
     * Restore mutliple wells.
     */
    public function restoreAll(Request $request, Pozo $pozo): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $pozo->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Pozos restablecidos.');
    }
}
