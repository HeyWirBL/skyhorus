<?php

use App\Http\Controllers\AnosController;
use App\Http\Controllers\ComponentesPozoController;
use App\Http\Controllers\CromatografiaGasController;
use App\Http\Controllers\CromatografiaLiquidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoriosController;
use App\Http\Controllers\DocPozoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\MesesDetallesController;
use App\Http\Controllers\PozosController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    /* Dashboard Principal */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /* Catálogo de Usuarios  */
    Route::resource('users', UsersController::class);

    /* Catálogo de Directorios / Carpetas */
    Route::resource('directorios', DirectoriosController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    /* Catálogo de Meses */
    Route::resource('meses', MesesDetallesController::class)->only([
        'index'
    ]);

    /* Catálogo de Años */
    Route::resource('anos', AnosController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy', 'restore'
    ]);

    /* Catálogo de Documentos */
    Route::resource('documentos', DocumentosController::class)->only([
        'index', 'create'
    ]);

    /* Catálogo de Pozos */
    Route::resource('pozos', PozosController::class);

    /* Catálogo de Pozos: Documentos */
    Route::resource('docpozos', DocPozoController::class)->only(['index']);

    /* Catálogo de Pozos: Componentes */
    Route::resource('componentespozos', ComponentesPozoController::class)->only(['index']);

    /* Cromatografías: Gas */
    Route::resource('cromatografiagas', CromatografiaGasController::class)->only(['index']);

    /* Cromatografías: Liquída */
    Route::resource('cromatografialiquida', CromatografiaLiquidaController::class)->only(['index']);

    /* Gráficas Generales */
    Route::resource('graficas', GraficaController::class)->only(['index']);
});

/* Autenticación */
require __DIR__.'/auth.php';