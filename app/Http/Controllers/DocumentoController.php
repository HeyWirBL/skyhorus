<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Models\Directorio;
use App\Models\Documento;
use App\Models\MesDetalle;
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
        return Inertia::render('Documentos/Index', [
            'filters' => $request->all('search', 'year', 'month', 'trashed'),
            'documentos' => $documento->query()
                ->orderBy('id', 'desc')
                ->with('directorio')
                ->with('ano')
                ->with('mes')
                ->filter($request->only('search', 'year', 'month', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($doc) => [
                    'id' => $doc->id,
                    'documento' => json_decode($doc->documento, true),
                    'deleted_at' => $doc->deleted_at,
                    'directorio' => $doc->directorio ? $doc->directorio->only('nombre_dir') : null,
                    'ano' => $doc->ano ? $doc->ano->only('ano') : null,
                    'mes' => $doc->mes ? $doc->mes->only('nombre') : null,
                ]),
            'anos' => $ano->query()
                ->latest()
                ->get()
                ->map
                ->only('id', 'ano'),
            'meses' => $mes->query()
                ->latest()
                ->get()
                ->map
                ->only('id', 'nombre'),

        ]);
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
            'documento' => 'required',
            'directorio_id' => ['required', Rule::exists('directorios', 'id')],
            'ano_id' => ['required', Rule::exists('anos', 'id')],
            'mes_id' => ['required', Rule::exists('meses', 'id')],
        ]);

        $counter = 0;

        if ($validated) {
            for ($i = 0; $i < count($request->documento); $i++) {
                $document = new Documento();
                $document->documento = json_encode($request->documento[$i]);
                $document->directorio_id = $request->directorio_id;
                $document->ano_id = $request->ano_id;
                $document->mes_id = $request->mes_id;
    
                $document->save();
    
                $counter++;
            }
        }
        
        if ($counter = count($request->documento)) {
            return redirect(route('documentos'));
        }
    }

    /**
     * Delete temporary an specific document.
     */

    /**
     * Delete multiple documents.
     */
    public function destroyMultiple(Request $request, Documento $documento): RedirectResponse
    {
        $documento->query()->whereIn('id', $request->get('selected'))->delete();

        return Redirect::back()->with('success', 'Documentos eliminados.');
    }

    /**
     * Restore documents.
     */
    public function restore(Documento $documento): RedirectResponse
    {
        $documento->restore();

        return Redirect::back()->with('success', 'Carpeta restablecida.');
    }

    //public function downloadFile(Documento $documento)
    //{
    //    $file = $documento->id;
    //    $subjectCode = $file->documento()->get()->map()->only('documento');
    //}
}
