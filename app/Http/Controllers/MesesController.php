<?php

namespace App\Http\Controllers;

use App\Models\Mes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MesesController extends Controller
{
     /**
     * Display a listing of months.
     */
    public function index(Request $request, Mes $mes): Response
    {
        return Inertia::render('Meses/Index', [
            'filters' => $request->all('search', 'trashed'),
            'meses' => $mes->query()
                ->filter($request->only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($m) => [
                    'id' => $m->id,
                    'nombre' => $m->nombre,
                    'deleted_at' => $m->deleted_at,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new month.
     */
    public function create(Mes $mes): Response
    {
        return Inertia::render('Meses/Create', [
            'meses' => $mes->get()
                ->map
                ->only('nombre'),
        ]);
    }

    /**
     * Store a newly created month in database.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Mes $mes): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|max:50|unique:'.Mes::class,
        ]);

        $mes->create($validated);

        return redirect(route('meses'))->with('success', 'Mes creado.');
    }
}
