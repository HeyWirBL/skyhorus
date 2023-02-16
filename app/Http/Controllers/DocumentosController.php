<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentosController extends Controller
{
    public function index()
    {
        return Inertia::render('Documentos/Index');
    }

    public function create()
    {
        return Inertia::render('Documentos/Create');
    }
}
