<?php

namespace App\Http\Controllers;

use App\Models\MesesDetalle;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MesesDetallesController extends Controller
{
    public function index()
    {
        return Inertia::render('MesesDetalles/Index', [
            'filters' => Request::all('search', 'trashed'),
            'meses' => MesesDetalle::query()
                ->with('mes')
                ->latest()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($mes) => [
                    'id' => $mes->id,
                    'mes' => $mes->mes ? $mes->mes->only('mes') : null,
                    'nombre' => $mes->nombre,
                    'deleted_at' => $mes->deleted_at
                ]),
        ]);
    }
}
