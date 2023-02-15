<?php

namespace App\Http\Controllers;

use App\Models\Pozo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class PozoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Pozos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'pozos' => Pozo::query()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($pozo) => [
                    'idPozo' => $pozo->idPozo,
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
                ]),
        ]);
    }
}
