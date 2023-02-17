<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DocumentosController extends Controller
{
    public function index()
    {
        return Inertia::render('Documentos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'documentos' => Documento::query()
                ->with('directorio')
                ->with('ano')
                ->with('mes')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($documento) => [
                    'id' => $documento->id,
                    'documento' => $documento->documento,
                    'directorio' => $documento->directorio ? $documento->directorio->only('nombre_dir') : null,
                    'ano' => $documento->ano ? $documento->ano->only('ano') : null,
                    'mes' => $documento->mes ? $documento->mes->only('nombre') : null,
                ]),

        ]);
    }

    public function create()
    {
        return Inertia::render('Documentos/Create');
    }
}
