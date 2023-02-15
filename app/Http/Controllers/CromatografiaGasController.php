<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CromatografiaGasController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CromatografiaGas/Index');
    }
}
