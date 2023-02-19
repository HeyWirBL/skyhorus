<?php

use App\Http\Controllers\AnoController;
use App\Http\Controllers\ComponentePozoController;
use App\Http\Controllers\CromatografiaGasController;
use App\Http\Controllers\CromatografiaLiquidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorioController;
use App\Http\Controllers\DocPozoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\MesController;
use App\Http\Controllers\PozoController;
use App\Http\Controllers\UserController;
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
    //Route::resource('users', UserController::class);}
    Route::get('users', [UserController::class, 'index'])
        ->name('users');

    Route::get('users/crear', [UserController::class, 'create'])
        ->name('users.create');

    Route::get('users/{user}/editar', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::put('users/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    /* Catálogo de Directorios / Carpetas */
    /*Route::resource('directorios', DirectorioController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);*/
    Route::get('directorios', [DirectorioController::class, 'index'])
        ->name('directorios');

    Route::get('directorios/crear', [DirectorioController::class, 'create'])
        ->name('directorios.create');

    Route::get('directorios/{directorio}/editar', [DirectorioController::class, 'edit'])
        ->name('directorios.edit');

    Route::post('directorios', [DirectorioController::class, 'store'])
        ->name('directorios.store');
        
    Route::put('directorios/{directorio}', [DirectorioController::class, 'update'])
        ->name('directorios.update');

    Route::put('directorios/{directorio}/restore', [DirectorioController::class, 'restore'])
        ->name('directorios.restore');

    Route::delete('directorios/{directorio}', [DirectorioController::class, 'destroy'])
        ->name('directorios.destroy');

    /* Catálogo de Meses */
    /*Route::resource('meses', MesController::class)->only([
        'index'
    ]);*/
    Route::get('meses', [MesController::class, 'index'])
        ->name('meses');

    Route::get('meses/crear', [MesController::class, 'create'])
        ->name('meses.create');

    Route::get('meses/{mes}/editar', [MesController::class, 'edit'])
        ->name('meses.edit');

    Route::post('meses', [MesController::class, 'store'])
        ->name('directorios.store');
        
    Route::put('meses/{mes}', [MesController::class, 'update'])
        ->name('meses.update');

    Route::put('meses/{mes}/restore', [MesController::class, 'restore'])
        ->name('meses.restore');

    Route::delete('meses/{mes}', [MesController::class, 'destroy'])
        ->name('meses.destroy');

    /* Catálogo de Años */
    /*Route::resource('anos', AnoController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy', 'restore'
    ]);*/

    Route::get('anos', [AnoController::class, 'index'])
        ->name('anos');

    Route::get('anos/crear', [AnoController::class, 'create'])
        ->name('anos.create');

    Route::get('anos/{ano}/editar', [AnoController::class, 'edit'])
        ->name('anos.edit');

    Route::post('anos', [AnoController::class, 'store'])
        ->name('anos.store');
        
    Route::put('anos/{ano}', [AnoController::class, 'update'])
        ->name('anos.update');

    Route::put('anos/{ano}/restore', [AnoController::class, 'restore'])
        ->name('anos.restore');

    Route::delete('anos/{ano}', [AnoController::class, 'destroy'])
        ->name('anos.destroy');

    /* Catálogo de Documentos */
    /*Route::resource('documentos', DocumentoController::class)->only([
        'index', 'create'
    ]);*/
    Route::get('documentos', [DocumentoController::class, 'index'])
        ->name('documentos');

    Route::get('documentos/crear', [DocumentoController::class, 'create'])
        ->name('documentos.create');

    Route::get('documentos/{documento}/editar', [DocumentoController::class, 'edit'])
        ->name('documentos.edit');

    Route::post('documentos', [DocumentoController::class, 'store'])
        ->name('documentos.store');

    Route::put('documentos/{documento}', [DocumentoController::class, 'update'])
        ->name('documentos.update');

    Route::put('documentos/{documento}/restore', [DocumentoController::class, 'restore'])
        ->name('documentos.restore');

    Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy'])
        ->name('documentos.destroy');

    /* Catálogo de Pozos */
    //Route::resource('pozos', PozoController::class);
    Route::get('pozos', [PozoController::class, 'index'])
        ->name('pozos');

    Route::get('pozos/crear', [PozoController::class, 'create'])
        ->name('pozos.create');

    Route::get('pozos/{pozo}', [PozoController::class, 'show'])
        ->name('pozos.show');

    Route::get('pozos/{pozo}/editar', [PozoController::class, 'edit'])
        ->name('pozos.edit');

    Route::post('pozos', [PozoController::class, 'store'])
        ->name('pozos.store');

    Route::put('pozos/{pozo}', [PozoController::class, 'update'])
        ->name('pozos.update');

    Route::put('pozos/{pozo}/restore', [PozoController::class, 'restore'])
        ->name('pozos.restore');

    Route::delete('pozos/{pozo}', [PozoController::class, 'destroy'])
        ->name('pozos.destroy');

    /* Catálogo de Pozos: Documentos */
    // Route::resource('docpozos', DocPozoController::class)->only(['index']);
    Route::get('doc-pozos', [DocPozoController::class, 'index'])
        ->name('doc-pozos');

    /* Catálogo de Pozos: Componentes */
    //Route::resource('componentespozos', ComponentePozoController::class)->only(['index']);

    Route::get('componente-pozos', [ComponentePozoController::class, 'index'])
        ->name('componente-pozos');

    Route::get('componente-pozos/crear', [ComponentePozoController::class, 'create'])
        ->name('componente-pozos.create');
        
    Route::get('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'show'])
        ->name('componente-pozos.show');

    Route::post('componente-pozos/import', [ComponentePozoController::class, 'import'])
        ->name('componente-pozos.import');

    Route::get('componente-pozos/export/{componentePozo}', [ComponentePozoController::class, 'export'])
        ->name('componente-pozos.export');

    /* Cromatografías: Gas */
    //Route::resource('cromatografiagas', CromatografiaGasController::class)->only(['index']);

    Route::get('cromatografia-gases', [CromatografiaGasController::class, 'index'])
        ->name('cromatografia-gases');

    /* Cromatografías: Liquída */
    //Route::resource('cromatografialiquida', CromatografiaLiquidaController::class)->only(['index']);

    Route::get('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'index'])
        ->name('cromatografia-liquidas');

    /* Gráficas Generales */
    Route::resource('graficas', GraficaController::class)->only(['index']);
});

/* Autenticación */
require __DIR__.'/auth.php';