<?php

namespace App\Http\Controllers;

use App\Models\DocPozo;
use App\Models\Pozo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'can' => [
                'createDocPozo' => Auth::user()->can('create', DocPozo::class),
                'editDocPozo' => Auth::user()->can('update', DocPozo::class),
            ],
            'filters' => $request->all('search', 'trashed'),
            'docPozos' => $docPozo->query()
                ->orderBy('id', 'desc')
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
}
