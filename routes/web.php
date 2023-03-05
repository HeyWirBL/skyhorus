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
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PozoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
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

Route::middleware('auth')->group(function () {

    /* Main Dashboard */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /* Generic Charts */
    Route::resource('graficas', GraficaController::class)->only(['index']);

    /* Menu Navigation App */
    Route::resource('menu', MenuController::class)->only(['index']);

    /* Technical Information on the Application */
    Route::get('/informacion', function () {
        return Inertia::render('About/Informacion', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    /* Download Excel File Format */
    Route::get('/formato', function () {
        $path = Storage::disk('public')->path('formats/componentes_de_pozo.xlsx');
        return Response::download($path);
    });

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Users
    |--------------------------------------------------------------------------
    |
    | Catalogue for registering new users in the system and assigning respective 
    | roles.
    |
    */
    Route::get('users', [UserController::class, 'index'])
        ->name('users')
        ->middleware('can:viewAny,App\Models\User');

    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:view,App\Models\User');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:create,App\Models\User');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:update,App\Models\User');

    Route::put('users/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore')
        ->middleware('can:restore,App\Models\User');

    Route::put('users', [UserController::class, 'restoreAll'])
        ->name('users.restoreAll')
        ->middleware('can:restore,App\Models\User');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:delete,App\Models\User');

    Route::delete('users', [UserController::class, 'destroyAll'])
        ->name('users.destroyAll')
        ->middleware('can:delete,App\Models\User');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Profile Information About User
    |--------------------------------------------------------------------------
    |
    | Display personal information about a specific user.
    |
    */
    Route::get('perfil', [ProfileController::class, 'edit'])
        ->name('perfil.edit');

    Route::patch('perfil', [ProfileController::class, 'update'])
        ->name('perfil.update');

    Route::delete('perfil', [ProfileController::class, 'destroy'])
        ->name('perfil.destroy');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Profile Information About User
    |--------------------------------------------------------------------------
    |
    | Display personal information about a specific user.
    |
    */
    Route::get('directorios', [DirectorioController::class, 'index'])
        ->name('directorios');

    Route::get('directorios/{directorio}', [DirectorioController::class, 'show'])
        ->name('directorios.show')
        ->middleware('can:view,App\Models\Directorio');

    Route::post('directorios', [DirectorioController::class, 'store'])
        ->name('directorios.store')
        ->middleware('can:create,App\Models\Directorio');
        
    Route::put('directorios/{directorio}', [DirectorioController::class, 'update'])
        ->name('directorios.update')
        ->middleware('can:update,App\Models\Directorio');

    Route::put('directorios/{directorio}/restore', [DirectorioController::class, 'restore'])
        ->name('directorios.restore')
        ->middleware('can:restore,App\Models\Directorio');
        
    Route::put('directorios', [DirectorioController::class, 'restoreAll'])
        ->name('directorios.restoreAll')
        ->middleware('can:restore,App\Models\Directorio');

    Route::delete('directorios/{directorio}', [DirectorioController::class, 'destroy'])
        ->name('directorios.destroy')
        ->middleware('can:delete,App\Models\Directorio');

    Route::delete('directorios', [DirectorioController::class, 'destroyAll'])
        ->name('directorios.destroyAll')
        ->middleware('can:delete,App\Models\Directorio');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Years
    |--------------------------------------------------------------------------
    |
    | Display the years.
    |
    */
    Route::get('anos', [AnoController::class, 'index'])
        ->name('anos');

    Route::post('anos', [AnoController::class, 'store'])
        ->name('anos.store')
        ->middleware('can:create,App\Models\Ano');
        
    Route::put('anos/{ano}', [AnoController::class, 'update'])
        ->name('anos.update')
        ->middleware('can:update,App\Models\Ano');

    Route::put('anos/{ano}/restore', [AnoController::class, 'restore'])
        ->name('anos.restore')
        ->middleware('can:restore,App\Models\Ano');

    Route::put('anos', [AnoController::class, 'restoreAll'])
        ->name('anos.restoreAll')
        ->middleware('can:restore,App\Models\Ano');

    Route::delete('anos/{ano}', [AnoController::class, 'destroy'])
        ->name('anos.destroy')
        ->middleware('can:delete,App\Models\Ano');

    Route::delete('anos', [AnoController::class, 'destroyAll'])
        ->name('anos.destroyAll')
        ->middleware('can:delete,App\Models\Ano');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Documents
    |--------------------------------------------------------------------------
    |
    | Display the whole documents that management the system.
    |
    */
    Route::get('documentos', [DocumentoController::class, 'index'])
        ->name('documentos');

    Route::get('documentos/{id}/descargar', [DocumentoController::class, 'download'])
        ->name('documento.download');

    Route::get('documentos/{id}/descargar/{index}', [DocumentoController::class, 'downloadMultiple'])
        ->name('documento.downloadMultiple');

    Route::post('documentos', [DocumentoController::class, 'store'])
        ->name('documentos.store');

    Route::put('documentos/{documento}', [DocumentoController::class, 'update'])
        ->name('documentos.update');

    Route::put('documentos/{documento}/restore', [DocumentoController::class, 'restore'])
        ->name('documentos.restore');

    Route::put('documentos', [DocumentoController::class, 'restoreAll'])
        ->name('documentos.restoreAll');

    Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy'])
        ->name('documentos.destroy');

    Route::delete('documentos', [DocumentoController::class, 'destroyAll'])
        ->name('documentos.destroyAll');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Wells
    |--------------------------------------------------------------------------
    |
    | Catalogue for well management where you can view all the details.
    |
    */
    Route::get('pozos', [PozoController::class, 'index'])
        ->name('pozos');

    Route::get('pozos/{pozo}', [PozoController::class, 'show'])
        ->name('pozos.show');

    Route::post('pozos', [PozoController::class, 'store'])
        ->name('pozos.store')
        ->middleware('can:create,App\Models\Pozo');

    Route::put('pozos/{pozo}', [PozoController::class, 'update'])
        ->name('pozos.update')
        ->middleware('can:update,App\Models\Pozo');

    Route::put('pozos/{pozo}/restore', [PozoController::class, 'restore'])
        ->name('pozos.restore')
        ->middleware('can:restore,App\Models\Pozo');

    Route::put('pozos', [PozoController::class, 'restoreAll'])
        ->name('pozos.restoreAll')
        ->middleware('can:restore,App\Models\Pozo');

    Route::delete('pozos/{pozo}', [PozoController::class, 'destroy'])
        ->name('pozos.destroy')
        ->middleware('can:delete,App\Models\Pozo');
    
    Route::delete('pozos', [PozoController::class, 'destroyAll'])
        ->name('pozos.destroyAll')
        ->middleware('can:delete,App\Models\Pozo');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Well Documents
    |--------------------------------------------------------------------------
    |
    | Catalogue of documents assigned to each of the wells.
    |
    */
    Route::get('doc-pozos', [DocPozoController::class, 'index'])
        ->name('doc-pozos');

    Route::get('doc-pozos/{id}/descargar', [DocPozoController::class, 'download'])
        ->name('doc-pozos.download');

    Route::post('doc-pozos', [DocPozoController::class, 'store'])
        ->name('doc-pozos.store')
        ->middleware('can:create,App\Models\DocPozo');

    Route::put('doc-pozos/{docPozo}', [DocPozoController::class, 'update'])
        ->name('doc-pozos.update')
        ->middleware('can:update,App\Models\DocPozo');

    Route::put('doc-pozos/{docPozo}/restore', [DocPozoController::class, 'restore'])
        ->name('doc-pozos.restore')
        ->middleware('can:restore,App\Models\DocPozo');

    Route::put('doc-pozos', [DocPozoController::class, 'restoreAll'])
        ->name('doc-pozos.restoreAll')
        ->middleware('can:restore,App\Models\DocPozo');

    Route::delete('doc-pozos/{docPozo}', [DocPozoController::class, 'destroy'])
        ->name('doc-pozos.destroy')
        ->middleware('can:delete,App\Models\DocPozo');

    Route::delete('doc-pozos', [DocPozoController::class, 'destroyAll'])
        ->name('doc-pozos.destroyAll')
        ->middleware('can:delete,App\Models\DocPozo');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Well Components
    |--------------------------------------------------------------------------
    |
    | Catalogue for the administration and assignment of values to wells.
    |
    */
    Route::get('componente-pozos', [ComponentePozoController::class, 'index'])
        ->name('componente-pozos');

    Route::get('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'show'])
        ->name('componente-pozos.show');

    Route::post('componente-pozos', [ComponentePozoController::class, 'store'])
        ->name('componente-pozos.store')
        ->middleware('can:create,App\Models\ComponentePozo');

    Route::post('componente-pozos/import', [ComponentePozoController::class, 'import'])
        ->name('componente-pozos.import');

    Route::put('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'update'])
        ->name('componente-pozos.update')
        ->middleware('can:update,App\Models\ComponentePozo');

    Route::put('componente-pozos/{componentePozo}/restore', [ComponentePozoController::class, 'restore'])
        ->name('pocomponente-pozoszos.restore')
        ->middleware('can:restore,App\Models\ComponentePozo');

    Route::put('componente-pozos', [ComponentePozoController::class, 'restoreAll'])
        ->name('componente-pozos.restoreAll')
        ->middleware('can:restore,App\Models\ComponentePozo');

    Route::delete('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'destroy'])
        ->name('componente-pozos.destroy')
        ->middleware('can:delete,App\Models\ComponentePozo');
    
    Route::delete('componente-pozos', [ComponentePozoController::class, 'destroyAll'])
        ->name('componente-pozos.destroyAll')
        ->middleware('can:delete,App\Models\ComponentePozo');
    

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Well Chromatography Documents
    |--------------------------------------------------------------------------
    |
    | Document catalogue for attaching PDF files in the gas chromatography section.
    |
    */
    Route::get('cromatografia-gases', [CromatografiaGasController::class, 'index'])
        ->name('cromatografia-gases');        

    Route::get('cromatografia-gases/{id}/descargar', [CromatografiaGasController::class, 'download'])
        ->name('cromatografia-gases.download');
    
    Route::post('cromatografia-gases', [CromatografiaGasController::class, 'store'])
        ->name('cromatografia-gases.store')
        ->middleware('can:create,App\Models\CromatografiaGas');  

    Route::put('cromatografia-gases/{cromatografiaGas}', [CromatografiaGasController::class, 'update'])
        ->name('cromatografia-gases.update')
        ->middleware('can:update,App\Models\CromatografiaGas');

    Route::put('cromatografia-gases/{cromatografiaGas}/restore', [CromatografiaGasController::class, 'restore'])
        ->name('cromatografia-gases.restore')
        ->middleware('can:restore,App\Models\CromatografiaGas');

    Route::put('cromatografia-gases', [CromatografiaGasController::class, 'restoreAll'])
        ->name('cromatografia-gases.restoreAll')
        ->middleware('can:restore,App\Models\CromatografiaGas');

    Route::delete('cromatografia-gases/{cromatografiaGas}', [CromatografiaGasController::class, 'destroy'])
        ->name('cromatografia-gases.destroy')
        ->middleware('can:delete,App\Models\CromatografiaGas');
    
    Route::delete('cromatografia-gases', [CromatografiaGasController::class, 'destroyAll'])
        ->name('cromatografia-gases.destroyAll')
        ->middleware('can:delete,App\Models\CromatografiaGas');

    /*
    |--------------------------------------------------------------------------
    | Catalogue of Well Chromatography Documents
    |--------------------------------------------------------------------------
    |
    | Document catalogue for attaching PDF files in the liquid chromatography section.
    |
    */
    Route::get('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'index'])
        ->name('cromatografia-liquidas');

    Route::get('cromatografia-liquidas/{documento}/descargar', [CromatografiaLiquidaController::class, 'download'])
        ->name('cromatografia-liquidas.download');

    Route::post('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'store'])
        ->name('cromatografia-liquidas.store')
        ->middleware('can:create,App\Models\CromatografiaLiquida'); 

    Route::put('cromatografia-liquidas/{cromatografiaLiquida}', [CromatografiaLiquidaController::class, 'update'])
        ->name('cromatografia-liquidas.update')
        ->middleware('can:update,App\Models\CromatografiaLiquida');

    Route::put('cromatografia-liquidas/{cromatografiaLiquida}/restore', [CromatografiaLiquidaController::class, 'restore'])
        ->name('cromatografia-liquidas.restore')
        ->middleware('can:restore,App\Models\CromatografiaLiquida');

    Route::put('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'restoreAll'])
        ->name('cromatografia-liquidas.restoreAll')
        ->middleware('can:restore,App\Models\CromatografiaLiquida');

    Route::delete('cromatografia-liquidas/{cromatografiaLiquida}', [CromatografiaLiquidaController::class, 'destroy'])
        ->name('cromatografia-liquidas.destroy')
        ->middleware('can:delete,App\Models\CromatografiaLiquida');

    Route::delete('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'destroyAll'])
        ->name('cromatografia-liquidas.destroyAll')
        ->middleware('can:delete,App\Models\CromatografiaLiquida');
});

/* Auth Pages */
require __DIR__.'/auth.php';