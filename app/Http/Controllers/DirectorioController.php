<?php

namespace App\Http\Controllers;

use App\Models\Directorio;
use App\Models\Ano;
use App\Models\MesDetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $can = Cache::remember('can', 3600, function () use ($directorio) {
            return [
                'createDirectorio' => Auth::user()->can('create', $directorio),
                'editDirectorio' => Auth::user()->can('update', $directorio),
                'restoreDirectorio' => Auth::user()->can('restore', $directorio),
                'deleteDirectorio' => Auth::user()->can('delete', $directorio),
            ];
        });

        $filters = $request->only('search', 'trashed');

        $directorios = Cache::remember('directorios', 3600, function () use ($filters) {
            return Directorio::with('documentos')
                ->filter($filters)
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($dir) => [
                    'id' => $dir->id,
                    'nombre_dir' => $dir->nombre_dir,
                    'fecha_dir' => $dir->fecha_dir,
                    'deleted_at' => $dir->deleted_at,
                    'documentos' => $dir->documentos->map(function ($doc) {
                        return $doc->only(['id', 'documento', 'deleted_at']);
                    }),
            ]);
        });

        return Inertia::render('Directorios/Index', compact('can', 'filters', 'directorios'));
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
    public function show(Request $request, Directorio $directorio, Ano $ano, MesDetalle $mes)
    {
        $can = Cache::remember('can', 3600, function () use ($directorio) {
            return [
                'restoreDirectorio' => Auth::user()->can('restore', $directorio),
                'deleteDirectorio' => Auth::user()->can('delete', $directorio),
            ];
        });

        $filters = $request->only('search', 'trashed');

        // Filter for year and month
        if ($request->has('year') || $request->has('month')) {
            $year = $request->input('year');
            $month = $request->input('month');

            $filters['year'] = $year;
            $filters['month'] = $month;
        }

        $directorioData = [
            'id' => $directorio->id,
            'nombre_dir' => $directorio->nombre_dir,
            'fecha_dir' => $directorio->fecha_dir,
            'deleted_at' => $directorio->deleted_at,
            'documentos' => $directorio->documentos()
                ->with('ano', 'mesDetalle')
                ->join('mes_detalles', 'documentos.mes_detalle_id', '=', 'mes_detalles.id')
                ->select('documentos.*', 'mes_detalles.id as mes_id')
                ->orderBy('mes_id', 'ASC')      
                ->filter($filters)
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($documento) => [
                    'id' => $documento->id,
                    'documento' => json_decode($documento->documento),
                    'directorio_id' => $documento->directorio_id,
                    'ano_id' => $documento->ano_id,
                    'mes_detalle_id' => $documento->mes_detalle_id,
                    'deleted_at' => $documento->deleted_at,
                    'ano' => optional($documento->ano)->only('ano', 'deleted_at'),
                    'mes' => optional($documento->mesDetalle)->only('nombre', 'deleted_at'),
                ]),  
        ];

        $anos = $ano->query()
            ->latest()
            ->get(['id', 'ano']);

        $meses = $mes->query()
            ->orderBy('id')
            ->get(['id', 'nombre']);

        return Inertia::render('Directorios/Show', compact('can', 'filters', 'directorioData', 'anos', 'meses'));
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
     * Delete multiple folders.
     */
    public function destroyAll(Request $request, Directorio $directorio): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $directorio->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Carpetas eliminadas.');
    }

    /**
     * Restore the an specific folder.
     */
    public function restore(Directorio $directorio): RedirectResponse
    {
        $directorio->restore();
        return Redirect::back()->with('success', 'Carpeta restablecida.');
    }

    /**
     * Restore mutliple folders.
     */
    public function restoreAll(Request $request, Directorio $directorio): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $directorio->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Carpetas restablecidas.');
    }
}
