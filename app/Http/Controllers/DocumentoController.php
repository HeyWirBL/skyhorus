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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use League\CommonMark\Node\Block\Document;

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
    public function store(Request $request, Documento $documento): RedirectResponse
    {
        try {            
            $request->validate([
                'documento.*' => ['required', 'max:8000'], // MAX 8 MB per file
                'directorio_id' => ['required', Rule::exists('directorios', 'id')],
                'ano_id' => ['required', Rule::exists('anos', 'id')],
                'mes_detalle_id' => ['required', Rule::exists('mes_detalles', 'id')],
            ]);

            $files = $request->file('documento');

            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                // store the file in the "files" directory inside the "storage/app/public" disk
                $path = Storage::disk('public')->putFileAs('files', $file, $filename);
                $documento = $documento->create([
                    'directorio_id' => $request->input('directorio_id'),
                    'ano_id' => $request->input('ano_id'),
                    'mes_detalle_id' => $request->input('mes_detalle_id'),
                    'documento' => json_encode([
                        'name' => asset('storage/' . $path), // generate a public URL for the file
                        'usrName' => $filename,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType(),
                    ]),
                ]);
            }            
            return Redirect::back()->with('success', 'Subido correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

   public function download($document)
    {
        if(Storage::disk('public')->exists($document)){
           return Storage::disk('public')->download($document);
           //return response('error');           
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
