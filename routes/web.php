<?php

use App\Http\Controllers\AnosController;
use App\Http\Controllers\ComponentesPozosController;
use App\Http\Controllers\CromatografiaGasController;
use App\Http\Controllers\CromatografiaLiquidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoriosController;
use App\Http\Controllers\DocPozoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\MesesController;
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
    //Route::resource('users', UsersController::class);}
    Route::get('users', [UsersController::class, 'index'])
        ->name('users');

    Route::get('users/crear', [UsersController::class, 'create'])
        ->name('users.create');

    Route::get('users/{user}/editar', [UsersController::class, 'edit'])
        ->name('users.edit');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update');

    Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy');

    /* Catálogo de Directorios / Carpetas */
    /*Route::resource('directorios', DirectoriosController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);*/
    Route::get('directorios', [DirectoriosController::class, 'index'])
        ->name('directorios');

    Route::get('directorios/crear', [DirectoriosController::class, 'create'])
        ->name('directorios.create');

    Route::get('directorios/{directorio}/editar', [DirectoriosController::class, 'edit'])
        ->name('directorios.edit');

    Route::post('directorios', [DirectoriosController::class, 'store'])
        ->name('directorios.store');
        
    Route::put('directorios/{directorio}', [DirectoriosController::class, 'update'])
        ->name('directorios.update');

    Route::put('directorios/{directorio}/restore', [DirectoriosController::class, 'restore'])
        ->name('directorios.restore');

    Route::delete('directorios/{directorio}', [DirectoriosController::class, 'destroy'])
        ->name('directorios.destroy');

    /* Catálogo de Meses */
    /*Route::resource('meses', MesesController::class)->only([
        'index'
    ]);*/
    Route::get('meses', [MesesController::class, 'index'])
        ->name('meses');

    Route::get('meses/crear', [MesesController::class, 'create'])
        ->name('meses.create');

    Route::get('meses/{mes}/editar', [MesesController::class, 'edit'])
        ->name('meses.edit');

    Route::post('meses', [MesesController::class, 'store'])
        ->name('directorios.store');
        
    Route::put('meses/{mes}', [MesesController::class, 'update'])
        ->name('meses.update');

    Route::put('meses/{mes}/restore', [MesesController::class, 'restore'])
        ->name('meses.restore');

    Route::delete('meses/{mes}', [MesesController::class, 'destroy'])
        ->name('meses.destroy');

    /* Catálogo de Años */
    /*Route::resource('anos', AnosController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy', 'restore'
    ]);*/

    Route::get('anos', [AnosController::class, 'index'])
        ->name('anos');

    Route::get('anos/crear', [AnosController::class, 'create'])
        ->name('anos.create');

    Route::get('anos/{ano}/editar', [AnosController::class, 'edit'])
        ->name('anos.edit');

    Route::post('anos', [AnosController::class, 'store'])
        ->name('anos.store');
        
    Route::put('anos/{ano}', [AnosController::class, 'update'])
        ->name('anos.update');

    Route::put('anos/{ano}/restore', [AnosController::class, 'restore'])
        ->name('anos.restore');

    Route::delete('anos/{ano}', [AnosController::class, 'destroy'])
        ->name('anos.destroy');

    /* Catálogo de Documentos */
    /*Route::resource('documentos', DocumentosController::class)->only([
        'index', 'create'
    ]);*/
    Route::get('documentos', [DocumentosController::class, 'index'])
        ->name('documentos');

    Route::get('documentos/crear', [DocumentosController::class, 'create'])
        ->name('documentos.create');

    Route::get('documentos/{documento}/editar', [DocumentosController::class, 'edit'])
        ->name('documentos.edit');

    Route::post('documentos', [DocumentosController::class, 'store'])
        ->name('documentos.store');

    Route::put('documentos/{documento}', [DocumentosController::class, 'update'])
        ->name('documentos.update');

    Route::put('documentos/{documento}/restore', [DocumentosController::class, 'restore'])
        ->name('documentos.restore');

    Route::delete('documentos/{documento}', [DocumentosController::class, 'destroy'])
        ->name('documentos.destroy');

    /* Catálogo de Pozos */
    //Route::resource('pozos', PozosController::class);
    Route::get('pozos', [PozosController::class, 'index'])
        ->name('pozos');

    Route::get('pozos/crear', [PozosController::class, 'create'])
        ->name('pozos.create');

    Route::get('pozos/{pozo}', [PozosController::class, 'show'])
        ->name('pozos.show');

    Route::get('pozos/{pozo}/editar', [PozosController::class, 'edit'])
        ->name('pozos.edit');

    Route::post('pozos', [PozosController::class, 'store'])
        ->name('pozos.store');

    Route::put('pozos/{pozo}', [PozosController::class, 'update'])
        ->name('pozos.update');

    Route::put('pozos/{pozo}/restore', [PozosController::class, 'restore'])
        ->name('pozos.restore');

    Route::delete('pozos/{pozo}', [PozosController::class, 'destroy'])
        ->name('pozos.destroy');

    /* Catálogo de Pozos: Documentos */
    Route::resource('docpozos', DocPozoController::class)->only(['index']);

    /* Catálogo de Pozos: Componentes */
    //Route::resource('componentespozos', ComponentesPozosController::class)->only(['index']);

    Route::get('componentespozos', [ComponentesPozosController::class, 'index'])
        ->name('componentespozos');

    Route::get('componentespozos/crear', [ComponentesPozosController::class, 'create'])
        ->name('pozos.create');

    Route::get('componentespozos/{pozo}', [ComponentesPozosController::class, 'show'])
        ->name('componentespozos.show');

    Route::get('componentespozos/{pozo}/editar', [ComponentesPozosController::class, 'edit'])
        ->name('componentespozos.edit');

    Route::post('componentespozos', [ComponentesPozosController::class, 'store'])
        ->name('componentespozos.store');

    Route::put('componentespozos/{pozo}', [ComponentesPozosController::class, 'update'])
        ->name('componentespozos.update');

    Route::put('componentespozos/{pozo}/restore', [ComponentesPozosController::class, 'restore'])
        ->name('componentespozos.restore');

    Route::delete('componentespozos/{pozo}', [ComponentesPozosController::class, 'destroy'])
        ->name('componentespozos.destroy');

    /* Cromatografías: Gas */
    Route::resource('cromatografiagas', CromatografiaGasController::class)->only(['index']);

    /* Cromatografías: Liquída */
    Route::resource('cromatografialiquida', CromatografiaLiquidaController::class)->only(['index']);

    /* Gráficas Generales */
    Route::resource('graficas', GraficaController::class)->only(['index']);
});

/* Autenticación */
require __DIR__.'/auth.php';