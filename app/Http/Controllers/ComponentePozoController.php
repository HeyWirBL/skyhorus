<?php

namespace App\Http\Controllers;

use App\Exports\ComponentePozosExport;
use App\Imports\ComponentePozosImport;
use App\Models\ComponentePozo;
use App\Models\ComponentePozoView;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ComponentePozoController extends Controller
{
    /**
     * Display a listing of wells components.
     */
    public function index(Request $request, ComponentePozo $componentePozo): Response
    {
        $user = Auth::user();
        $can = [
            'createComponentePozo' => $user->can('create', ComponentePozo::class),
            'editComponentePozo' => $user->can('update', ComponentePozo::class),
            'restoreComponentePozo' => $user->can('restore', ComponentePozo::class),
            'deleteComponentePozo' => $user->can('delete', ComponentePozo::class),
        ];

        $filters = $request->all('search', 'trashed');

        $componentePozos = $componentePozo->query()->filter($filters)
            ->orderByDesc('id')
            ->with('pozo')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cp) => [
                'id' => $cp->id,
                'equipo_utilizado' => $cp->equipo_utilizado,
                'nombre_componente' => $cp->nombre_componente,
                'fecha_recep' => $cp->fecha_recep,
                'deleted_at' => $cp->deleted_at,
                'pozo' => optional($cp->pozo)->only('nombre_pozo', 'deleted_at'),
            ]); 

        return Inertia::render('ComponentePozos/Index', compact('can', 'filters', 'componentePozos'));
    }

    /**
     * Render the create view for a new Pozo component.
     */
    public function create(Pozo $pozo): Response
    {
        // Retrieve a list of Pozos, selecting only the necessary columns
        $pozos = $pozo->select('id', 'nombre_pozo')
                      ->orderBy('id', 'desc') 
                      ->get()
                      ->map
                      ->only('id', 'nombre_pozo');

        // Render the create view, passing in the paginated list of Pozos.
        return Inertia::render('ComponentePozos/Create', [
            'pozos' => $pozos,
        ]);
    }

    /**
     * Store a newly created componente pozo in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'dioxido_carbono' => ['required', 'max:50'],
            'pe_dioxido_carbono' => ['required', 'max:50'],
            'mo_dioxido_carbono' => ['required', 'max:50'],
            'den_dioxido_carbono' => ['required', 'max:50'],
            'acido_sulfidrico' => ['required', 'max:50'],
            'pe_acido_sulfidrico' => ['required', 'max:50'],
            'mo_acido_sulfidrico' => ['required', 'max:50'],
            'den_acido_sulfidrico' => ['required', 'max:50'],
            'nitrogeno' => ['required', 'max:50'],
            'pe_nitrogeno' => ['required', 'max:50'],
            'mo_nitrogeno' => ['required', 'max:50'],
            'den_nitrogeno' => ['required', 'max:50'],
            'metano' => ['required', 'max:50'],
            'pe_metano' => ['required', 'max:50'],
            'mo_metano' => ['required', 'max:50'],
            'den_metano' => ['required', 'max:50'],
            'etano' => ['required', 'max:50'],
            'pe_etano' => ['required', 'max:50'],
            'mo_etano' => ['required', 'max:50'],
            'den_etano' => ['required', 'max:50'],
            'propano' => ['required', 'max:50'],
            'pe_propano' => ['required', 'max:50'],
            'mo_propano' => ['required', 'max:50'],
            'den_propano' => ['required', 'max:50'],
            'iso_butano' => ['required', 'max:50'],
            'pe_iso_butano' => ['required', 'max:50'],
            'mo_iso_butano' => ['required', 'max:50'],
            'den_iso_butano' => ['required', 'max:50'],
            'n_butano' => ['required', 'max:50'],
            'pe_n_butano' => ['required', 'max:50'],
            'mo_n_butano' => ['required', 'max:50'],
            'den_n_butano' => ['required', 'max:50'],
            'iso_pentano' => ['required', 'max:50'],
            'pe_iso_pentano' => ['required', 'max:50'],
            'mo_iso_pentano' => ['required', 'max:50'],
            'den_iso_pentano' => ['required', 'max:50'],
            'n_pentano' => ['required', 'max:50'],
            'pe_n_pentano' => ['required', 'max:50'],
            'mo_n_pentano' => ['required', 'max:50'],
            'den_n_pentano' => ['required', 'max:50'],
            'n_exano' => ['required', 'max:50'],
            'pe_n_exano' => ['required', 'max:50'],
            'mo_n_exano' => ['required', 'max:50'],
            'den_n_exano' => ['required', 'max:50'],            
            'fecha_recep' => ['required', 'date'],
            'pozo_id' => [
                'required',
                Rule::exists('pozos', 'id'),
            ],
            'fecha_analisis' => ['required', 'date'],
            'no_determinacion' => ['required', 'max:100'],
            'equipo_utilizado' => ['required', 'max:100'],
            'met_laboratorio' => ['required', 'max:255'],
            'observaciones' => ['nullable'],
            'nombre_componente' => ['required', 'max:100'],
            'fecha_muestreo' => ['nullable', 'date'],
        ]);
        
        ComponentePozo::create($validatedData);        
        return Redirect::back()->with('success', 'Componentes de pozo creados.');
    }

    /**
     * Display the information for specific component well.
     */
    public function show(ComponentePozoView $view, ComponentePozo $componentePozo, Pozo $pozo): Response
    {
        $user = Auth::user();
        $can = [
            'editComponentePozo' => $user->can('update', ComponentePozo::class),
            'restoreComponentePozo' => $user->can('restore', ComponentePozo::class),
            'deleteComponentePozo' => $user->can('delete', ComponentePozo::class),
        ];

        $quimicosData = $view->query()->where('idComPozo', $componentePozo->id)->get();

        return Inertia::render('ComponentePozos/Show', [
            'can' => $can,
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
                'pozo_id' => $componentePozo->pozo_id,
                'fecha_analisis' => $componentePozo->fecha_analisis,
                'no_determinacion' => $componentePozo->no_determinacion,
                'equipo_utilizado' => $componentePozo->equipo_utilizado,
                'met_laboratorio' => $componentePozo->met_laboratorio,
                'observaciones' => $componentePozo->observaciones,
                'nombre_componente' => $componentePozo->nombre_componente,
                'fecha_muestreo' => $componentePozo->fecha_muestreo,
                'deleted_at' => $componentePozo->deleted_at,
                'pozo' => $componentePozo->pozo ? $componentePozo->pozo->only('id', 'nombre_pozo', 'deleted_at') : null,
            ],
            'pozos' => $pozo->query()
                ->orderByDesc('id')
                ->get()
                ->map
                ->only('id', 'nombre_pozo'),
            'quimicosData' => $quimicosData,
        ]);
    }

    /**
     * Show the form for editing an specific components well.
     */
    public function edit(ComponentePozo $componentePozo, Pozo $pozo)
    {
        //
    }

    /**
     * Update the well's components information.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, ComponentePozo $componentePozo): RedirectResponse
    {
        $validated = $request->validate([
            'dioxido_carbono' => ['required', 'max:50'],
            'pe_dioxido_carbono' => ['required', 'max:50'],
            'mo_dioxido_carbono' => ['required', 'max:50'],
            'den_dioxido_carbono' => ['required', 'max:50'],
            'acido_sulfidrico' => ['required', 'max:50'],
            'pe_acido_sulfidrico' => ['required', 'max:50'],
            'mo_acido_sulfidrico' => ['required', 'max:50'],
            'den_acido_sulfidrico' => ['required', 'max:50'],
            'nitrogeno' => ['required', 'max:50'],
            'pe_nitrogeno' => ['required', 'max:50'],
            'mo_nitrogeno' => ['required', 'max:50'],
            'den_nitrogeno' => ['required', 'max:50'],
            'metano' => ['required', 'max:50'],
            'pe_metano' => ['required', 'max:50'],
            'mo_metano' => ['required', 'max:50'],
            'den_metano' => ['required', 'max:50'],
            'etano' => ['required', 'max:50'],
            'pe_etano' => ['required', 'max:50'],
            'mo_etano' => ['required', 'max:50'],
            'den_etano' => ['required', 'max:50'],
            'propano' => ['required', 'max:50'],
            'pe_propano' => ['required', 'max:50'],
            'mo_propano' => ['required', 'max:50'],
            'den_propano' => ['required', 'max:50'],
            'iso_butano' => ['required', 'max:50'],
            'pe_iso_butano' => ['required', 'max:50'],
            'mo_iso_butano' => ['required', 'max:50'],
            'den_iso_butano' => ['required', 'max:50'],
            'n_butano' => ['required', 'max:50'],
            'pe_n_butano' => ['required', 'max:50'],
            'mo_n_butano' => ['required', 'max:50'],
            'den_n_butano' => ['required', 'max:50'],
            'iso_pentano' => ['required', 'max:50'],
            'pe_iso_pentano' => ['required', 'max:50'],
            'mo_iso_pentano' => ['required', 'max:50'],
            'den_iso_pentano' => ['required', 'max:50'],
            'n_pentano' => ['required', 'max:50'],
            'pe_n_pentano' => ['required', 'max:50'],
            'mo_n_pentano' => ['required', 'max:50'],
            'den_n_pentano' => ['required', 'max:50'],
            'n_exano' => ['required', 'max:50'],
            'pe_n_exano' => ['required', 'max:50'],
            'mo_n_exano' => ['required', 'max:50'],
            'den_n_exano' => ['required', 'max:50'],            
            'fecha_recep' => ['required', 'date'],
            'pozo_id' => [
                'required',
                Rule::exists('pozos', 'id'),
            ],
            'fecha_analisis' => ['required', 'date'],
            'no_determinacion' => ['required', 'max:100'],
            'equipo_utilizado' => ['required', 'max:100'],
            'met_laboratorio' => ['required', 'max:255'],
            'observaciones' => ['required'],
            'nombre_componente' => ['required', 'max:100'],
            'fecha_muestreo' => ['required', 'date'],
        ]);

        $componentePozo->update($validated);

        return Redirect::back()->with('success', 'Componentes de pozo actualizados.');
    }

    /**
     * Delete temporary an specific components well.
     */
    public function destroy(ComponentePozo $componentePozo): RedirectResponse
    {
        $componentePozo->delete();
        return Redirect::back()->with('success', 'Componentes de pozo eliminados.');
    }

    /**
     * Delete multiple componentes wells.
     */
    public function destroyAll(Request $request, ComponentePozo $componentePozo): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $componentePozo->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Componentes de pozos eliminados.');
    }

    /**
     * Restore the components well.
     */
    public function restore(ComponentePozo $componentePozo): RedirectResponse
    {
        $componentePozo->restore();

        return Redirect::back()->with('success', 'Componentes de pozo restablecidos.');
    }

    /**
     * Restore mutliple components wells.
     */
    public function restoreAll(Request $request, ComponentePozo $componentePozo): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $componentePozo->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Componentes de pozos restablecidos.');
    }

    /**
     * Read data for component wells.
     */
    public function read(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        $data = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];

            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            $data[] = $rowData;
        }

        return response()->json($data);
    }

    /**
     * Import data for componente wells.
     */
    /* public function import(Request $request): RedirectResponse
    {
        $path = $request->file('file');

        if(!empty($path)){
            return Redirect::back()->with('succes', $path);
        }else{
            return Redirect::back()->with('succes', 'la importacion ha fallado'); 
        }

        $import = new ComponentePozosImport();
        Excel::import($import, $path); 

        return Redirect::back()->with('success', 'Datos importados correctamente.');
    }
 */ 
    public function import(Request $request)
    {       
        $validated = $request->validate([
            'file' => ['required'],
            'pozoId' => ['required'],
            'fechaRecep' => ['required'],
            'fechaAnalisis' => ['required'],
            'fechaMuest' => ['required'],
        ]);
        
        foreach($request->file('file') as $file){
            $pozoId = $request->pozoId;
            $fechaRecep = $request->fechaRecep;
            $fechaAnalisis = $request->fechaAnalisis;
            $fechaMuest = $request->fechaMuest;

            if(!empty($file) && $validated){
            Excel::import(new ComponentePozosImport($pozoId, $fechaRecep, $fechaAnalisis, $fechaMuest), $file );
            }

        }
        return redirect(route('componente-pozos'))->with('succes', 'archvos importados correctamente');
    }
    /**
     * Export data for component well as xlsx.
     */
    public function export(Request $request)
    {
        return Excel::download(new ComponentePozosExport($request->id), 'componentes_pozo.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
}
