<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Models\Directorio;
use App\Models\Documento;
use App\Models\Mes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DocumentosController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request, Documento $documento): Response
    {
        return Inertia::render('Documentos/Index', [
            'filters' => $request->all('search','trashed'),
            'documentos' => $documento->query()
                ->with('directorio')
                ->with('ano')
                ->with('mes')
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($doc) => [
                    'id' => $doc->id,
                    'documento' => $doc->documento,
                    'deleted_at' => $doc->deleted_at,
                    'directorio' => $doc->directorio ? $doc->directorio->only('nombre_dir') : null,
                    'ano' => $doc->ano ? $doc->ano->only('ano') : null,
                    'mes' => $doc->mes ? $doc->mes->only('nombre') : null,
                ]),

        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Directorio $directorio, Ano $ano, Mes $mes): Response
    {
        return Inertia::render('Documentos/Create', [
            'directorios' => $directorio->get()
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
        $validated = $request->validate([
            'documento' => 'required',
            'directorio_id' => ['required', Rule::exists('directorios', 'id')],
            'ano_id' => ['required', Rule::exists('anos', 'id')],
            'mes_id' => ['required', Rule::exists('meses', 'id')],
        ]);
        
        $documento->create($validated);

        return redirect(route('documentos'));
    }
}
