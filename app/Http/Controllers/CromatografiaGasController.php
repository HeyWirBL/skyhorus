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
use Illuminate\Validation\Rule;

class CromatografiaGasController extends Controller
{
    /**
     * Display a list of pozo chromatographies.
     */
    public function index(Request $request, CromatografiaGas $cromatografiaGas): Response
    {
        $filters = $request->only('search', 'trashed');
        $cromatografiaGases = $cromatografiaGas->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cg) => [
                'id' => $cg->id,
                'documento' => json_decode($cg->documento),
                'fecha_hora' => $cg->fecha_hora,
                'deleted_at' => $cg->deleted_at,
                'pozo' => optional($cg->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);

        return Inertia::render('CromatografiaGases/Index', [
            'can' => [
                'restoreCromatografiaGas' => Auth::user()->can('restore', CromatografiaGas::class),
            ],
            'filters' => $filters,
            'cromatografiaGases' => $cromatografiaGases,
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
     * Delete multiple well cromatographies.
     */
    public function destroyAll(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaGas->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaGas $cromatografiaGas): RedirectResponse
    {
        $cromatografiaGas->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore multiple well cromatographies.
     */
    public function restoreAll(Request $request, CromatografiaGas $cromatografiaGas): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaGas->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'files' => 'required',
            'fecha' => 'required',
            'pozo' => ['required', Rule::exists('pozos', 'id')],
        ]);
        if($validated){
            foreach($request->file('files') as $file){
                $filename = $file->getClientOriginalName();
                $fileRoute = time().$filename;
                $filesize = $file->getSize();
                $filetype = $file->getClientOriginalExtension();
                $file->storeAs('public/files/', $fileRoute);
                $doc = new CromatografiaGas();
                $doc->documento = '{"name": "'.$fileRoute.'", "size": "'.$filesize.'", "type": "'.$filetype.'", "usrName": "'.$filename.'" }';
                $doc->pozo_id = $request->pozo;
                $doc->fecha_hora = $request->fecha;
                $doc->save();
            }
            return redirect(route('cromatografia-gases'));
        }
    }
}
