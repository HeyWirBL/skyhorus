<?php

namespace App\Http\Controllers;

use App\Models\DocPozo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocPozoController extends Controller
{
    /**
     * Display a listing of well documents.
     */
    public function index(Request $request, DocPozo $docPozo): Response
    {
        return Inertia::render('DocPozos/Index', [
            'filters' => $request->all('search', 'trashed'),
            'docPozos' => $docPozo->query()
                ->filter($request->only(['search', 'trashed']))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($dp) => [
                    'id' => $dp->id,
                    'documento' => $dp->documento,
                    'fecha_hora' => $dp->fecha_hora,
                    'deleted_at' => $dp->deleted_at,
                    'pozo' => $dp->pozo ? $dp->pozo->only('nombre_pozo') : null,
                ]),
        ]);
    }
}
