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
            'identificador' => $p->identificador,
            'presion_kgcm2' => $p->presion_kgcm2,
            'presion_psi' => $p->presion_psi,
            'temp_C' => $p->temp_C,
            'temp_F' => $p->temp_F,
            'volumen_cm3' => $p->volumen_cm3,
            'volumen_lts' => $p->volumen_lts,
            'observaciones' => $p->observaciones,
            'nombre_pozo' => $p->nombre_pozo,
            'deleted_at' => $p->deleted_at,
        ]);

        return Inertia::render('Pozos/Index', compact('pozos', 'can', 'filters'));
    }

    /**
     * Show the form for creating a new well.
     */
    public function create()
    {
        //
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
    public function show(Request $request, Pozo $pozo): Response
    {        
        $user = Auth::user();              
        $can = [
            // Pozo Policy
            'editPozo' => $user->can('update', Pozo::class),
            'restorePozo' => $user->can('restore', Pozo::class),
            'deletePozo' => $user->can('delete', Pozo::class),
            // Doc Pozo Policy
            'createDocPozo' => $user->can('create', DocPozo::class),
            'editDocPozo' => $user->can('update', DocPozo::class),
            // Componente Pozo Policy
            'createComponentePozo' => $user->can('create', ComponentePozo::class),
            'editComponentePozo' => $user->can('update', ComponentePozo::class),
            'deleteComponentePozo' => $user->can('delete', ComponentePozo::class),
            'restoreComponentePozo' => $user->can('restore', ComponentePozo::class),
        ];        

        $pozoData = optional($pozo)->only(
            'id', 'punto_muestreo', 'fecha_hora', 'identificador', 'presion_kgcm2',
            'presion_psi', 'temp_C', 'temp_F', 'volumen_cm3', 'volumen_lts',
            'observaciones', 'nombre_pozo', 'deleted_at'
        );

        $pozoData['docPozos'] = $pozo->docPozos()
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($docPozo) => [
                'id' => $docPozo->id, 
                'documento' => json_decode($docPozo->documento), 
                'fecha_hora' => $docPozo->fecha_hora, 
                'deleted_at' => $docPozo->deleted_at,
            ]);

        $pozoData['componentePozos'] = $pozo->componentePozos()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cp) => [
                'id' => $cp->id,
                'dioxido_carbono' => $cp->dioxido_carbono,
                'pe_dioxido_carbono' => $cp->pe_dioxido_carbono,
                'mo_dioxido_carbono' => $cp->mo_dioxido_carbono,
                'den_dioxido_carbono' => $cp->den_dioxido_carbono,
                'acido_sulfidrico' => $cp->acido_sulfidrico,
                'pe_acido_sulfidrico' => $cp->pe_acido_sulfidrico,
                'mo_acido_sulfidrico' => $cp->mo_acido_sulfidrico,
                'den_acido_sulfidrico' => $cp->den_acido_sulfidrico,
                'nitrogeno' => $cp->nitrogeno,
                'pe_nitrogeno' => $cp->pe_nitrogeno,
                'mo_nitrogeno' => $cp->mo_nitrogeno,
                'den_nitrogeno' => $cp->den_nitrogeno,
                'metano' => $cp->metano,
                'pe_metano' => $cp->pe_metano,
                'mo_metano' => $cp->mo_metano,
                'den_metano' => $cp->den_metano,
                'etano' => $cp->etano,
                'pe_etano' => $cp->pe_etano,
                'mo_etano' => $cp->mo_etano,
                'den_etano' => $cp->den_etano,
                'propano' => $cp->propano,
                'pe_propano' => $cp->pe_propano,
                'mo_propano' => $cp->mo_propano,
                'den_propano' => $cp->den_propano,
                'iso_butano' => $cp->iso_butano,
                'pe_iso_butano' => $cp->pe_iso_butano,
                'mo_iso_butano' => $cp->mo_iso_butano,
                'den_iso_butano' => $cp->den_iso_butano,
                'n_butano' => $cp->n_butano,
                'pe_n_butano' => $cp->pe_n_butano,
                'mo_n_butano' => $cp->mo_n_butano,
                'den_n_butano' => $cp->den_n_butano,
                'iso_pentano' => $cp->iso_pentano,
                'pe_iso_pentano' => $cp->pe_iso_pentano,
                'mo_iso_pentano' => $cp->mo_iso_pentano,
                'den_iso_pentano' => $cp->den_iso_pentano,
                'n_pentano' => $cp->n_pentano,
                'pe_n_pentano' => $cp->pe_n_pentano,
                'mo_n_pentano' => $cp->mo_n_pentano,
                'den_n_pentano' => $cp->den_n_pentano,
                'n_exano' => $cp->n_exano,
                'pe_n_exano' => $cp->pe_n_exano,
                'mo_n_exano' => $cp->mo_n_exano,
                'den_n_exano' => $cp->den_n_exano,
                'pozo_id' => $cp->pozo_id,                
                'fecha_recep' => $cp->fecha_recep,
                'fecha_analisis' => $cp->fecha_analisis,
                'no_determinacion' => $cp->no_determinacion,
                'equipo_utilizado' => $cp->equipo_utilizado,
                'met_laboratorio' => $cp->met_laboratorio,
                'observaciones' => $cp->observaciones,
                'nombre_componente' => $cp->nombre_componente,
                'fecha_muestreo' => $cp->fecha_muestreo,
                'deleted_at' => $cp->deleted_at,
                'quimicosData' => DB::table('graf_lineas_mo')
                    ->where('idComPozo', $cp->id)
                    ->get(),
            ]);

        $pozoData['cromatografiaGases'] = $pozo->cromatografiaGases()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cg) => [
                'id' => $cg->id, 
                'documento' => json_decode($cg->documento), 
                'fecha_hora' => $cg->fecha_hora, 
                'deleted_at' => $cg->deleted_at,
            ]);

        $pozoData['cromatografiaLiquidas'] = $pozo->cromatografiaLiquidas()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cl) => [
                'id' => $cl->id, 
                'documento' => json_decode($cl->documento), 
                'fecha_hora' => $cl->fecha_hora, 
                'deleted_at' => $cl->deleted_at,
            ]);

        return Inertia::render('Pozos/Show', [
            'can' => $can,
            'pozo' => $pozoData,
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
            'observaciones' => ['nullable', 'max:150'],
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
