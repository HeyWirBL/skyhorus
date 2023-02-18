<?php

namespace App\Http\Controllers;

use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PozoController extends Controller
{
    /**
     * Display a listing of wells.
     */
    public function index(Request $request, Pozo $pozo): Response
    {
        return Inertia::render('Pozos/Index', [
            'filters' => $request->all('search','trashed'),
            'pozos' => $pozo->query()
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($p) => [
                    'id' => $p->id,
                    'fecha_hora' => $p->fecha_hora,
                    'identificador' => $p->identificador,
                    'nombre_pozo' => $p->nombre_pozo,
                    'deleted_at' => $p->deleted_at,
                ]),
        ]);
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
            'nombre_pozo' => 'required|max:150|unique:'.Pozo::class,
        ]);
        
        $pozo->create($validated);

        return redirect(route('pozos'))->with('success', 'Pozo creado.');
    }

    /**
     * Display the information for specific well.
     */
    public function show(Pozo $pozo): Response
    {
        return Inertia::render('Pozos/Show', [
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
                'docPozos' => $pozo->docPozos()
                    ->get()
                    ->map
                    ->only('id', 'documento', 'fecha_hora'),
                'componentePozos' => $pozo->componentePozos()
                    ->get()
                    ->map
                    ->only('id', 'equipo_utilizado', 'nombre_componente', 'fecha_recep'),
            ],
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
            'identificador' => ['required', 'max:150'],
            'presion_kgcm2' => ['required', 'max:150'],
            'presion_psi' => ['required', 'max:150'],
            'temp_C' => ['required', 'max:150'],
            'temp_F' => 'required|max:150',
            'volumen_cm3' => ['required', 'max:150'],
            'volumen_lts' => ['required', 'max:150'],
            'observaciones' => ['nullable'],
            'nombre_pozo' => ['required', 'max:150', Rule::unique('pozos')->ignore($pozo->id)],
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
     * Restore the well.
     */
    public function restore(Pozo $pozo): RedirectResponse
    {
        $pozo->restore();

        return Redirect::back()->with('success', 'Pozo restablecido.');
    }
}
