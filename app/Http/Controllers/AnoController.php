<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use Inertia\Inertia;
use Inertia\Response;

class AnoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Anos/Index', [
            'anos' => Ano::all()->map(fn($ano) => [
                'idAno' => $ano->idAno,
                'ano' => $ano->ano,
            ]),
        ]);
    }
}
