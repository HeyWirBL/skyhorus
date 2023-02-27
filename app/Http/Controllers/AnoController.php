<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AnoController extends Controller
{
    /**
     * Display a listing of years.
     */
    public function index(Request $request, Ano $ano): Response
    {
        $can = [
            'createAno' => Auth::user()->can('create', Ano::class),
            'editAno' => Auth::user()->can('update', Ano::class),
            'restoreAno' => Auth::user()->can('restore', Ano::class),
            'deleteAno' => Auth::user()->can('delete', Ano::class),
        ];
        $filters = $request->all('search', 'trashed');
        $anos = $ano->query()->latest()->filter($filters)
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($ano) => [
                'id' => $ano->id,
                'ano' => $ano->ano,
                'deleted_at' => $ano->deleted_at,
            ]);

        return Inertia::render('Anos/Index', compact('can', 'filters', 'anos'));
    }

    /**
     * Show the form for creating a new year.
     */
    public function create(): Response
    {
        return Inertia::render('Anos/Create');
    }

    /**
     * Store a newly created month in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Ano $ano): RedirectResponse
    {
        $validated = $request->validate([
            'ano' => 'required|max:50|unique:'.Ano::class,
        ]);

        $ano->create($validated);

        return redirect(route('anos'))->with('success', 'Año creado.');
    }

    /**
     * Display the information for specific year.
     */
    public function show(Ano $ano)
    {
        //
    }

    /**
     * Show the form for editing an specific year.
     */
    public function edit(Ano $ano): Response
    {
        return Inertia::render('Anos/Edit', [
            'ano' => $ano,    
        ]);
    }

    /**
     * Update the year's information.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Ano $ano): RedirectResponse
    {
        $ano->update(
            $request->validate([
                'ano' => ['required', 'max:50', Rule::unique('anos')->ignore($ano->id)],
            ]),
        );

        return Redirect::back()->with('success', 'Año actualizado.');
    }

    /**
     * Delete temporary an specific year.
     */
    public function destroy(Ano $ano): RedirectResponse
    {
        $ano->delete();
        return Redirect::back()->with('success', 'Año eliminado.');
    }

    /**
     * Delete multiple years.
     */
    public function destroyAll(Request $request, Ano $ano): RedirectResponse
    {
        $ids = explode(',', $request->query('ids', ''));
        $ano->whereIn('id', $ids)->delete();
        return Redirect::back()->with('success', 'Años eliminados.');
    }

    /**
     * Restore the year.
     */
    public function restore(Ano $ano): RedirectResponse
    {
        $ano->restore();
        return Redirect::back()->with('success', 'Año restablecido.');
    }
    
    /**
     * Restore mutliple years.
     */
    public function restoreAll(Request $request, Ano $ano): RedirectResponse
    {        
        $ids = explode(',', $request->query('ids', ''));
        $ano->whereIn('id', $ids)->restore();       
        return Redirect::back()->with('success', 'Años restablecidos.');
    }
}
