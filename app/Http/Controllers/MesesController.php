<?php

namespace App\Http\Controllers;

use App\Models\Mes;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MesesController extends Controller
{
    public function index()
    {
        return Inertia::render('Meses/Index', [
            'filters' => Request::all('search', 'trashed'),
            'meses' => Mes::query()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($mes) => [
                    'id' => $mes->id,
                    'mes' => $mes->mes,
                    'nombre' => $mes->nombre,
                    'deleted_at' => $mes->deleted_at,
                ]),
        ]);
    }
}
