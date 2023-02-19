<?php

namespace App\Http\Controllers;

use App\Models\Directorio;
use App\Models\Ano;
use App\Models\Mes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DirectorioController extends Controller
{
    /**
     * Display a listing of folders.
     */
    public function index(Request $request, Directorio $directorio): Response
    {
        return Inertia::render('Directorios/Index', [
            'filters' => $request->all('search', 'trashed'),
            'directorios' => $directorio->query()
                ->with('documentos')
                ->latest()
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($dir) => [
                    'id' => $dir->id,
                    'nombre_dir' => $dir->nombre_dir,
                    'fecha_dir' => $dir->fecha_dir,
                    'deleted_at' => $dir->deleted_at,
                    'documentos' => $dir->documentos()
                        ->get()
                        ->map
                        ->only('id', 'documento')
                ]),
        ]);
    }

    /**
     * Show the form for creating a new folder.
     */
    public function create(): Response
    {
        return Inertia::render('Directorios/Create');
    }

    /**
     * Store a newly created folder in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Directorio $directorio): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_dir' => 'required|string|max:100|unique:'.Directorio::class,
            'fecha_dir' => 'required|date',
        ]);

        $directorio->create($validated);

        return redirect(route('directorios'))->with('success', 'Carpeta creada.');
    }

    /**
     * Display the information for specific folder.
     */
    public function show(Directorio $directorio)
    {
        //
    }

    /**
     * Show the form for editing an specific folder.
     */
    public function edit(Request $request, Directorio $directorio, Ano $ano, Mes $mes): Response
    {
        return Inertia::render('Directorios/Edit', [
            'filters' => $request->all('search', 'year', 'month', 'trashed'),
            'directorio' => [
                'id' => $directorio->id,
                'nombre_dir' => $directorio->nombre_dir,
                'fecha_dir' => $directorio->fecha_dir,
                'deleted_at' => $directorio->deleted_at,
                'documentos' => $directorio->documentos()
                    ->filter($request->only('search', 'year', 'month', 'trashed'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($documento) => [
                        'id' => $documento->id,
                        'documento' => $documento->documento,
                        'deleted_at' => $documento->deleted_at,
                        'ano' => $documento->ano ? $documento->ano->only('ano') : null,
                        'mes' => $documento->mes ? $documento->mes->only('nombre') : null,
                    ]),             
            ],
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
     * Update the folder's information.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Directorio $directorio): RedirectResponse
    {
        $directorio->update(
            $request->validate([
                'nombre_dir' => ['required', 'string', 'max:100', Rule::unique('directorios')->ignore($directorio->id)],
                'fecha_dir' => ['required', 'date'],
            ]),
        );

        return Redirect::back()->with('success', 'Carpeta Actualizada.');
    }

    /**
     * Delete temporary an specific folder.
     */
    public function destroy(Directorio $directorio): RedirectResponse
    {
        $directorio->delete();

        return Redirect::back()->with('success', 'Carpeta eliminada.');
    }

    /**
     * Restore the user's account.
     */
    public function restore(Directorio $directorio): RedirectResponse
    {
        $directorio->restore();

        return Redirect::back()->with('success', 'Carpeta restablecida.');
    }
}
