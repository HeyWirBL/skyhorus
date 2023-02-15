<?php

use App\Http\Controllers\AnoController;
use App\Http\Controllers\ComponentesPozoController;
use App\Http\Controllers\CromatografiaGasController;
use App\Http\Controllers\CromatografiaLiquidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoriosController;
use App\Http\Controllers\DocPozoController;
use App\Http\Controllers\GraficaController;
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

/* Dashboard Principal */
Route::get('/', [DashboardController::class, 'index']);

/* Catálogo de Usuarios  */
Route::resource('users', UsersController::class);

/* Catálogo de Directorios / Carpetas */
Route::resource('directorios', DirectoriosController::class)->only([
    'index', 'create', 'store', 'edit', 'update', 'destroy'
]);

/* Catálogo de Años */
Route::resource('anos', AnoController::class)
    ->only(['index']);

/* Catálogo de Pozos */
Route::resource('pozos', PozosController::class);

/* Catálogo de Pozos: Documentos */
Route::resource('docpozos', DocPozoController::class)
    ->only(['index']);

/* Catálogo de Pozos: Componentes */
Route::resource('componentespozos', ComponentesPozoController::class)
    ->only(['index']);

/* Cromatografías: Gas */
Route::resource('cromatografiagas', CromatografiaGasController::class)
    ->only(['index']);

/* Cromatografías: Liquída */
Route::resource('cromatografialiquida', CromatografiaLiquidaController::class)
    ->only(['index']);

/* Gráficas Generales */
Route::get('/graficas', [GraficaController::class, 'index']);

/* Autenticación */
Route::post('/logout', function () {
    dd('loggin the user out');
});