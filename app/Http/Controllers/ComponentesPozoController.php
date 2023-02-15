<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ComponentesPozoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('ComponentesPozos/Index');
    }
}
