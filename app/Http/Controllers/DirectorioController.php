<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Directorio;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DirectorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Directorios/Index', [
            'filters' => Request::all('search', 'trashed'),
            'directorios' => Directorio::query()
                ->latest()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($directorio) => [
                    'idDirectorio' => $directorio->idDirectorio,
                    'nombre_dir' => $directorio->nombre_dir,
                    'fecha_dir' => $directorio->fecha_dir,
                    'deleted_at' => $directorio->deleted_at,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Directorios/Create');
    }

    /**
     * Store a newly created resource in database.
     */
    public function store()
    {
        $validated = Request::validate([
            'nombre_dir' => 'required|unique:directorios|max:100',
            'fecha_dir' => 'required|date',
        ]);

        Directorio::create($validated);
    
        return Redirect::route('directorios.index')->with('success', 'Carpeta creada.');
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param \App\Models\Directorio  $directorio
     */
    public function edit(Directorio $directorio)
    {
        return Inertia::render('Directorios/Edit', [
            'directorio' => [
                'idDirectorio' => $directorio->idDirectorio,
                'nombre_dir' => $directorio->nombre_dir,
                'fecha_dir' => $directorio->fecha_dir,
                'deleted_at' => $directorio->deleted_at,
            ],
        ]);
    }

    /**
     * Update the specified resource in database.
     * 
     * @param \App\Models\Directorio  $directorio
     */
    public function update(Directorio $directorio)
    {
        $directorio->update(
            Request::validate([
                'nombre_dir' => ['required', 'max:100', Rule::unique('directorios')->ignore($directorio->idDirectorio)],
                'fecha_dir' => 'required|date',
            ]),
        );

        return Redirect::back()->with('success', 'Carpeta Actualizada.');
    }
}
