<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorioController;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Ano;
use App\Models\Mes;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Dashboard Principal */
Route::get('/', [DashboardController::class, 'index']);

/* Catálogo de Usuarios  */
Route::resource('usuarios', UsuarioController::class)
    ->only(['index', 'create', 'store']);

/* Catálogo de Directorios / Carpetas */
Route::resource('directorios', DirectorioController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

/* Catálogo de Meses / Meses Detalles */
Route::get('/meses', function () {
    return Inertia::render('Meses/Index', [
        'meses' => Mes::all()->map(fn($mes) => [
            ''
        ]),
    ]);
});

/* Catálogo de Años */
Route::get('/anos', function () {
    return Inertia::render('Anos/Index', [
        'anos' => Ano::all()->map(fn($ano) => [
            'idAno' => $ano->idAno,
            'ano' => $ano->ano,
        ]),
    ]);
});

/* Catálogo de Pozos */
Route::get('/pozos', function () {
    return Inertia::render('Pozos/Index');
});

/* Gráficas */
Route::get('/graficas', [GraficaController::class, 'index']);

Route::post('/logout', function () {
    dd('loggin the user out');
});