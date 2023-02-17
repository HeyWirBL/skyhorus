<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AnosController extends Controller
{
    /**
     * Display a listing of years.
     */
    public function index(Request $request, Ano $ano): Response
    {
        return Inertia::render('Anos/Index', [
            'filters' => $request->all('search', 'trashed'),
            'anos' => $ano->query()
                ->latest()
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($ano) => [
                    'id' => $ano->id,
                    'ano' => $ano->ano,
                    'deleted_at' => $ano->deleted_at,
                ]), 
        ]);
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
     * Restore the year.
     */
    public function restore(Ano $ano): RedirectResponse
    {
        $ano->restore();

        return Redirect::back()->with('success', 'Año restablecido.');
    }
}
