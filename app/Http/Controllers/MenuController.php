<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Menu/Index');
    }
}
