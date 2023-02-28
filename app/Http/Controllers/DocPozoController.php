<?php

namespace App\Http\Controllers;

use App\Models\DocPozo;
use App\Models\Pozo;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Redirect;

class DocPozoController extends Controller
{
    /**
     * Display a listing of pozo documents.
     */
    public function index(Request $request, DocPozo $docPozo): Response
    {
        $filters = $request->all('search', 'trashed');
        $can = [
            'createDocPozo' => Auth::user()->can('create', DocPozo::class),
            'editDocPozo' => Auth::user()->can('update', DocPozo::class),
        ];

        $docPozos = $docPozo->query()->latest()->filter($filters)
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($dp) => [
                'id' => $dp->id,
                'documento' => $dp->documento,
                'fecha_hora' => $dp->fecha_hora,
                'deleted_at' => $dp->deleted_at,
                'pozo' => optional($dp->pozo)->only('nombre_pozo', 'deleted_at'),
            ]);

        return Inertia::render('DocPozos/Index', compact('can', 'filters', 'docPozos'));
    }

    /**
     * Show the form for uploading a new well documents.
     */
    public function create(Pozo $pozo): Response
    {
        return Inertia::render('DocPozos/Create', [
            'pozos' => $pozo->query()
                ->orderBy('id', 'desc') 
                ->get()
                ->map
                ->only('id', 'nombre_pozo'),
        ]);
    }

    public function store(Request $request):RedirectResponse
    {
        $validated = $request->validate([
            'files' => 'required',
            'pozo' => ['required', Rule::exists('pozos', 'id')],
            'fecha' => 'required',
        ]);
        if($validated){
            foreach($request->file('files') as $file){
                $filename = $file->getClientOriginalName();
                $fileRoute = time().$filename;
                $filesize = $file->getSize();
                $filetype = $file->getClientOriginalExtension();
                $file->storeAs('', $fileRoute);
                $doc = new DocPozo();
                $doc->documento = '{"name": "'.$fileRoute.'", "size": "'.$filesize.'", "type": "'.$filetype.'", "usrName": "'.$filename.'" }';
                $doc->pozo_id = $request->pozo;
                $doc->fecha_hora = $request->fecha;
                $doc->save();
            }
        }
        return redirect(route('doc-pozos'));
    }

    /**
     * Delete temporary an specific document.
     */
    public function destroy(DocPozo $docPozo): RedirectResponse
    {
        $docPozo->delete();
        return Redirect::back()->with('success', 'Documento de pozo eliminado.');
    }

    /**
     * Delete multiple documents.
     */
    public function destroyAll(Request $request, DocPozo $docPozo): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $docPozo->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos de pozo eliminados.');
    }

    /**
     * Restore documents.
     */
    public function restore(DocPozo $docPozo): RedirectResponse
    {
        $docPozo->restore();
        return Redirect::back()->with('success', 'Documento de pozo restablecido.');
    }

    /**
     * Restore mutliple documents.
     */
    public function restoreAll(Request $request, DocPozo $docPozo): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $docPozo->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos de pozo restablecidos.');
    }
}
