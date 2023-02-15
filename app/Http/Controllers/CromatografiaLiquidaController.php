<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CromatografiaLiquidaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CromatografiaLiquida/Index');
    }
}
