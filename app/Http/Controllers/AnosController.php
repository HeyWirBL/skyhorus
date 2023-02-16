<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use Illuminate\Support\Facades\Request;
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
}
