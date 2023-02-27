<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Models\Directorio;
use App\Models\Documento;
use App\Models\MesDetalle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DocumentoController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request, Documento $documento, Ano $ano, MesDetalle $mes): Response
    {
        $filters = $request->only('search', 'year',' month', 'trashed');
        $documentos = $documento->with('directorio', 'ano', 'mesDetalle')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($doc) => [
                'id' => $doc->id,
                'documento' => json_decode($doc->documento),
                'deleted_at' => $doc->deleted_at,
                'directorio' => optional($doc->directorio)->only('nombre_dir', 'deleted_at'),
                'ano' => optional($doc->ano)->only('ano', 'deleted_at'),
                'mes' => optional($doc->mesDetalle)->only('nombre'),
            ]);

        $anos = $ano->latest()->get()->map->only('id', 'ano');
        $meses = $mes->latest()->get()->map->only('id', 'nombre');

        return Inertia::render('Documentos/Index', compact('filters', 'documentos', 'anos', 'meses'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Directorio $directorio, Ano $ano, MesDetalle $mes): Response
    {
        return Inertia::render('Documentos/Create', [
            'directorios' => $directorio->query()
                ->orderBy('id', 'desc')
                ->get()
                ->map
                ->only('id', 'nombre_dir'),
            'anos' => $ano->get()
                ->map
                ->only('id', 'ano'),
            'meses' => $mes->get()
                ->map
                ->only('id', 'nombre')
        ]);
    }

    /**
     * Store a newly created document in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    /* public function store(Request $request, Documento $documento): RedirectResponse
    {
        $validated = $request->validate([
            'documento' => 'required',
            'directorio_id' => ['required', Rule::exists('directorios', 'id')],
            'ano_id' => ['required', Rule::exists('anos', 'id')],
            'mes_id' => ['required', Rule::exists('meses', 'id')],
        ]);
        
        $documento->create($validated);

        return redirect(route('documentos'));
    }  */

    public function store(Request $request): RedirectResponse 
    {
        $validated = $request->validate([
            'files' => 'required',
            'directorio_id' => ['required', Rule::exists('directorios', 'id')],
            'ano_id' => ['required', Rule::exists('anos', 'id')],
            'mes_id' => ['required', Rule::exists('mes_detalles', 'id')],
        ]);
        if ($validated) {
            foreach($request->file('files') as $file){
                $filename = $file->getClientOriginalName();
                $fileRoute = time().$filename;
                $filesize = $file->getSize();
                $filetype = $file->getClientOriginalExtension();
                $file->storeAs('', $fileRoute);
                $document = new Documento();
                $document->documento = '{"name": "'.$fileRoute.'", "size": "'.$filesize.'", "type": "'.$filetype.'", "usrName": "'.$filename.'" }';
                $document->directorio_id = $request->directorio_id;
                $document->ano_id = $request->ano_id;
                $document->mes_detalle_id = $request->mes_id;
                $document->save();
            }
        } 
        return redirect(route('documentos'));
    }

   public function download($document)
    {
        if(Storage::disk('local')->exists($document)){
            return Storage::disk('local')->download($document);
        }else{
            return response('¡404! No se pudo encontrar este recurso. Si ves este mensaje, por favor contacta con un administrador. <br/> Powered by: Nerd Rage!', 404);
        }
    } 

    /**
     * Delete temporary an specific document.
     */
    public function destroy(Documento $documento): RedirectResponse
    {
        $documento->delete();
        return Redirect::back()->with('success', 'Documento eliminado.');
    }

    /**
     * Delete multiple documents.
     */
    public function destroyAll(Request $request, Documento $documento): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $documento->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore documents.
     */
    public function restore(Documento $documento): RedirectResponse
    {
        $documento->restore();
        return Redirect::back()->with('success', 'Documento restablecido.');
    }

    /**
     * Restore mutliple documents.
     */
    public function restoreAll(Request $request, Documento $documento): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $documento->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Documentos restablecidos.');
    }
}
