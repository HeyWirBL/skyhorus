<?php

namespace App\Http\Controllers;

use App\Imports\ComponentePozosImportCollection;
use App\Models\ComponentePozo;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function index(Request $request, ComponentePozo $componentePozo, Pozo $pozo): Response
    {
        $user = Auth::user();
        $can = [
            'createComponentePozo' => $user->can('create', ComponentePozo::class),
            'editComponentePozo' => $user->can('update', ComponentePozo::class),
            'restoreComponentePozo' => $user->can('restore', ComponentePozo::class),
            'deleteComponentePozo' => $user->can('delete', ComponentePozo::class),
        ];

        $filters = $request->only('search', 'trashed');
        $dateFilter = $request->only('year', 'month');        

        $componentePozos = $componentePozo->filter($filters)->datefilter($dateFilter)
            ->orderByDesc('id')
            ->with('pozo')
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
                'fecha_recep' => $cp->fecha_recep,
                'pozo_id' => $cp->pozo_id,
                'fecha_analisis' => $cp->fecha_analisis,
                'no_determinacion' => $cp->no_determinacion,
                'equipo_utilizado' => $cp->equipo_utilizado,
                'met_laboratorio' => $cp->met_laboratorio,
                'observaciones' => $cp->observaciones,
                'nombre_componente' => $cp->nombre_componente,
                'fecha_recep' => $cp->fecha_recep,
                'fecha_muestreo' =>$cp->fecha_muestreo,
                'deleted_at' => $cp->deleted_at,
                'pozo' => optional($cp->pozo)->only('nombre_pozo', 'deleted_at'),
                'quimicosData' => DB::table('graf_lineas_mo')
                    ->where('idComPozo', $cp->id)
                    ->get(),
            ]); 

        $pozos = $pozo->select('id', 'nombre_pozo')->orderByDesc('id')->get();

        return Inertia::render('ComponentePozos/Index', compact('can', 'filters', 'componentePozos', 'pozos'));
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
            'observaciones' => ['nullable'],
            'nombre_componente' => ['required', 'max:100'],
            'fecha_muestreo' => ['nullable', 'date'],
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
            'pozoId' => ['required', Rule::exists('pozos', 'id')],
            'fechaRecep' => ['required', 'date'],
            'fechaAnalisis' => ['required', 'date'],
            'fechaMuest' => ['nullable', 'date'],
        ]);
        
        foreach($request->file('file') as $file){
            $pozoId = $request->pozoId;
            $fechaRecep = $request->fechaRecep;
            $fechaAnalisis = $request->fechaAnalisis;
            $fechaMuest = $request->fechaMuest;

            if(!empty($file) && $validated){
                Excel::import(new ComponentePozosImportCollection($pozoId, $fechaRecep, $fechaAnalisis, $fechaMuest), $file );
            }

        }
        return redirect(route('componente-pozos'))->with('succes', 'archvos importados correctamente');
    }
}
