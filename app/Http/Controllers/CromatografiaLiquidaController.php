<?php

namespace App\Http\Controllers;

use App\Models\CromatografiaLiquida;
use App\Models\Pozo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rule;

class CromatografiaLiquidaController extends Controller
{
    /**
     * Display a list of well chromatographies.
     */
    public function index(Request $request, CromatografiaLiquida $cromatografiaLiquida): Response
    {
        $filters = $request->only('search', 'trashed');
        $cromatografiaLiquidas = $cromatografiaLiquida->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($cl) => [
                'id' => $cl->id,
                'documento' => json_decode($cl->documento),
                'fecha_hora' => $cl->fecha_hora,
                'deleted_at' => $cl->deleted_at,
                'pozo' => optional($cl->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);
        return Inertia::render('CromatografiaLiquidas/Index', [
            'can' => [
                'restoreCromatografiaLiquida' => Auth::user()->can('restore', CromatografiaLiquida::class),
            ],
            'filters' => $filters,
            'cromatografiaLiquidas' => $cromatografiaLiquidas,
        ]);
    }

     /**
     * Show the form for uploading a new well chromatographies.
     */
    public function create(Pozo $pozo): Response
    {
        return Inertia::render('CromatografiaLiquidas/Create', [
            'pozos' => $pozo->query()
                ->orderBy('id', 'desc')
                ->get()
                ->map
                ->only('id', 'nombre_pozo'),
        ]);
    }

    /* store multiple documents */

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
                $doc = new CromatografiaLiquida();
                $doc->documento = '{"name": "'.$fileRoute.'", "size": "'.$filesize.'", "type": "'.$filetype.'", "usrName": "'.$filename.'" }';
                $doc->pozo_id = $request->pozo;
                $doc->fecha_hora = $request->fecha;
                $doc->save();
            }
        }
        return redirect(route('documentos'));
    }

    /**
     * Delete temporary an specific well cromatography.
     */
    public function destroy(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Delete multiple well cromatographies.
     */
    public function destroyAll(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaLiquida->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore the well cromatography.
     */
    public function restore(CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {
        $cromatografiaLiquida->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore multiple well cromatographies.
     */
    public function restoreAll(Request $request, CromatografiaLiquida $cromatografiaLiquida): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $cromatografiaLiquida->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }
}
