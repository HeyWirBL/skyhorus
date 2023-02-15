<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PozoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Pozos/Index');
    }
}
