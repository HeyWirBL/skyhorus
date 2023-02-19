<?php

namespace App\Http\Controllers;

use App\Exports\ComponentePozosExport;
use App\Imports\ComponentePozosImport;
use App\Models\ComponentePozo;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

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
     * Show the form for creating a new well components.
     */
    public function create(Pozo $pozo): Response
    {
        return Inertia::render('ComponentePozos/Create', [
            'pozos' => $pozo->query()
                ->get()
                ->map
                ->only('id', 'nombre_pozo')
        ]);
    }

    /**
     * Display the information for specific component well.
     */
    public function show(ComponentePozo $componentePozo): Response
    {
        return Inertia::render('ComponentePozos/Show', [
            'componentePozo' => [
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
                'fecha_analisis' => $componentePozo->fecha_analisis,
                'no_determinacion' => $componentePozo->no_determinacion,
                'equipo_utilizado' => $componentePozo->equipo_utilizado,
                'met_laboratorio' => $componentePozo->met_laboratorio,
                'observaciones' => $componentePozo->observaciones,
                'nombre_componente' => $componentePozo->nombre_componente,
                'fecha_muestreo' => $componentePozo->fecha_muestreo,
                'pozo' => $componentePozo->pozo->only('id', 'nombre_pozo'),
            ]
        ]);
    }

    /**
     * Import data for componente wells.
     */
    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('file');

        Excel::import(new ComponentePozosImport, $file);

        return Redirect::back()->with('success', 'Archivo importado!');
    }

    /**
     * Export data for component well as xlsx.
     */
    public function export(Request $request)
    {
        $export = new ComponentePozosExport([
            [1, 2, 3],
            [4, 5, 6]
        ]);
        
        return Excel::download($export, 'componente_pozos.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
