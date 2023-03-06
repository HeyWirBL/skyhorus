<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display view of dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard/Index');
    }
}
