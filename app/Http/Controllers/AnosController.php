<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AnosController extends Controller
{
    public function index()
    {
        return Inertia::render('Anos/Index', [
            'filters' => Request::all('search', 'trashed'),
            'anos' => Ano::query()
                ->latest()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($ano) => [
                    'id' => $ano->id,
                    'ano' => $ano->ano,
                    'deleted_at' => $ano->deleted_at,
                ]), 
        ]);
    }

    public function create()
    {
        return Inertia::render('Anos/Create');
    }

    public function store()
    {
        Request::validate([
            'ano' => ['required', 'max:50', Rule::unique('anos')],
        ]);

        Ano::create([
            'ano' => Request::get('ano'),
        ]);

        return redirect(route('anos.index'))->with('success', 'Año creado.');
    }

    public function edit(Ano $ano)
    {
        return Inertia::render('Anos/Edit', [
            'ano' => $ano,    
        ]);
    }

    public function update(Ano $ano)
    {
        Request::validate([
            'ano' => ['required', 'max:50', Rule::unique('anos')->ignore($ano->id)],
        ]);

        $ano->update(Request::only('ano'));

        return Redirect::back()->with('success', 'Año actualizado.');
    }

    public function destroy(Ano $ano)
    {
        $ano->delete();

        return Redirect::back()->with('success', 'Año eliminado.');
    }

    public function restore(Ano $ano)
    {
        $ano->restore();

        return Redirect::back()->with('success', 'Año restablecido.');
    }
}
