<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Directorio;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DirectoriosController extends Controller
{
    public function index()
    {
        return Inertia::render('Directorios/Index', [
            'filters' => Request::all('search', 'trashed'),
            'directorios' => Directorio::query()
                ->latest()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($directorio) => [
                    'id' => $directorio->id,
                    'nombre_dir' => $directorio->nombre_dir,
                    'fecha_dir' => $directorio->fecha_dir,
                    'deleted_at' => $directorio->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Directorios/Create');
    }

    public function store()
    {
        $validated = Request::validate([
            'nombre_dir' => 'required|unique:directorios|max:100',
            'fecha_dir' => 'required|date',
        ]);

        Directorio::create($validated);
    
        return Redirect::route('directorios.index')->with('success', 'Carpeta creada.');
    }

    public function edit(Directorio $directorio)
    {
        return Inertia::render('Directorios/Edit', [
            'directorio' => [
                'id' => $directorio->id,
                'nombre_dir' => $directorio->nombre_dir,
                'fecha_dir' => $directorio->fecha_dir,
                'deleted_at' => $directorio->deleted_at,
            ],
        ]);
    }

    public function update(Directorio $directorio)
    {
        $directorio->update(
            Request::validate([
                'nombre_dir' => ['required', 'max:100', Rule::unique('directorios')->ignore($directorio->id)],
                'fecha_dir' => 'required|date',
            ]),
        );

        return Redirect::back()->with('success', 'Carpeta Actualizada.');
    }
}
