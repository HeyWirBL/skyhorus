<?php

namespace App\Http\Controllers;

use App\Models\Pozo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PozosController extends Controller
{
    public function index()
    {
        return Inertia::render('Pozos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'pozos' => Pozo::query()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($pozo) => [
                    'id' => $pozo->id,
                    'fecha_hora' => $pozo->fecha_hora,
                    'identificador' => $pozo->identificador,
                    'nombre_pozo' => $pozo->nombre_pozo,
                    'deleted_at' => $pozo->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Pozos/Create');
    }

    public function store()
    {
        Request::validate([
            'punto_muestreo' => 'required|string|max:150',
            'fecha_hora' => 'required|date',
            'identificador' => 'required|max:150',
            'presion_kgcm2' => 'required|max:150',
            'presion_psi' => 'required|max:150',
            'temp_C' => 'required|max:150',
            'temp_F' => 'required|max:150',
            'volumen_cm3' => 'required|max:150',
            'volumen_lts' => 'required|max:150',
            'observaciones' => 'nullable',
            'nombre_pozo' => ['required', 'max:150', Rule::unique('pozos')]
        ]);

        Pozo::create([
            'punto_muestreo' => Request::get('punto_muestreo'),
            'fecha_hora' => Request::get('fecha_hora'),
            'identificador' => Request::get('identificador'),
            'presion_kgcm2' => Request::get('presion_kgcm2'),
            'presion_psi' => Request::get('presion_psi'),
            'temp_C' => Request::get('temp_C'),
            'temp_F' => Request::get('temp_F'),
            'volumen_cm3' => Request::get('volumen_cm3'),
            'volumen_lts' => Request::get('volumen_lts'),
            'observaciones' => Request::get('observaciones'),
            'nombre_pozo' => Request::get('nombre_pozo'),
        ]);

        return redirect(route('pozos.index'))->with('success', 'Pozo creado.');
    }

    public function show(Pozo $pozo)
    {
        return Inertia::render('Pozos/Show', [
            'pozo' => $pozo,
        ]);
    }

    public function edit(Pozo $pozo)
    {
        return Inertia::render('Pozos/Edit', [
            'pozo' => $pozo,    
        ]);
    }

    public function update(Pozo $pozo)
    {
        Request::validate([
            'punto_muestreo' => 'required|string|max:150',
            'fecha_hora' => 'required|date',
            'identificador' => 'required|max:150',
            'presion_kgcm2' => 'required|max:150',
            'presion_psi' => 'required|max:150',
            'temp_C' => 'required|max:150',
            'temp_F' => 'required|max:150',
            'volumen_cm3' => 'required|max:150',
            'volumen_lts' => 'required|max:150',
            'observaciones' => 'nullable',
            'nombre_pozo' => ['required', 'max:150', Rule::unique('pozos')->ignore($pozo->id)],
        ]);

        $pozo->update(Request::only(
            'punto_muestreo', 
            'fecha_hora', 
            'identificador', 
            'presion_kgcm2', 
            'presion_psi',
            'temp_C',
            'temp_F',
            'volumen_cm3',
            'volumen_lts',
            'nombre_pozo',
        ));

        if (Request::get('observaciones')) {
            $pozo->update(['observaciones' => Request::get('observaciones')]);
        }

        return Redirect::back()->with('success', 'Pozo actualizado.');
    }

    public function destroy(Pozo $pozo)
    {
        $pozo->delete();

        return Redirect::back()->with('success', 'Pozo eliminado.');
    }

    public function restore(Pozo $pozo)
    {
        $pozo->restore();

        return Redirect::back()->with('success', 'Pozo restablecido.');
    }
}
