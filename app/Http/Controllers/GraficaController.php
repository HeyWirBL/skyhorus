<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class GraficaController extends Controller
{
    /**
     * Display view of charts.
     */
    public function index(): Response
    {
        return Inertia::render('Graficas/Index');
    }
}
