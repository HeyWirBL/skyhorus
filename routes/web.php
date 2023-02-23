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
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        ->name('users')->middleware('can:viewAny,App\Models\User');

    Route::get('users/crear', [UserController::class, 'create'])
        ->name('users.create')->middleware('can:create,App\Models\User');

    Route::get('users/{user}/editar', [UserController::class, 'edit'])
        ->name('users.edit')->middleware('can:update,App\Models\User');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update')->middleware('can:update,App\Models\User');

    Route::put('users/{user}/restore', [UserController::class, 'restore'])
        ->name('users.restore');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    /** Perfil de Usuario */
    Route::get('perfil', [ProfileController::class, 'edit'])
        ->name('perfil.edit');

    Route::patch('perfil/{user}', [ProfileController::class, 'update'])
        ->name('perfil.update');

    /* Catálogo de Directorios / Carpetas */
    /*Route::resource('directorios', DirectorioController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);*/
    Route::get('directorios', [DirectorioController::class, 'index'])
        ->name('directorios');

    Route::get('directorios/crear', [DirectorioController::class, 'create'])
        ->name('directorios.create')->middleware('can:create,App\Models\Directorio');

    Route::get('directorios/{directorio}/editar', [DirectorioController::class, 'edit'])
        ->name('directorios.edit')->middleware('can:update,App\Models\Directorio');

    Route::post('directorios', [DirectorioController::class, 'store'])
        ->name('directorios.store')->middleware('can:create,App\Models\Directorio');;
        
    Route::put('directorios/{directorio}', [DirectorioController::class, 'update'])
        ->name('directorios.update')->middleware('can:update,App\Models\Directorio');

    Route::put('directorios/{directorio}/restore', [DirectorioController::class, 'restore'])
        ->name('directorios.restore')->middleware('can:restore,App\Models\Directorio');;

    Route::delete('directorios/{directorio}', [DirectorioController::class, 'destroy'])
        ->name('directorios.destroy')->middleware('can:delete,App\Models\Directorio');;

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

    Route::post('documentos/destroy-multiple', [DocumentoController::class, 'destroyMultiple'])
        ->name('documentos.destroyMultiple');

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
        ->name('pozos.create')->middleware('can:create,App\Models\Pozo');

    Route::get('pozos/{pozo}', [PozoController::class, 'show'])
        ->name('pozos.show');

    Route::post('pozos', [PozoController::class, 'store'])
        ->name('pozos.store')->middleware('can:create,App\Models\Pozo');

    Route::put('pozos/{pozo}', [PozoController::class, 'update'])
        ->name('pozos.update')->middleware('can:update,App\Models\Pozo');

    Route::put('pozos/{pozo}/restore', [PozoController::class, 'restore'])
        ->name('pozos.restore')->middleware('can:restore,App\Models\Pozo');

    Route::delete('pozos/{pozo}', [PozoController::class, 'destroy'])
        ->name('pozos.destroy')->middleware('can:delete,App\Models\Pozo');

    Route::post('pozos/destroy-all', [PozoController::class, 'destroyAll'])
        ->name('pozos.destroyAll');

    /* Catálogo de Pozos: Documentos */
    // Route::resource('docpozos', DocPozoController::class)->only(['index']);
    Route::get('doc-pozos', [DocPozoController::class, 'index'])
        ->name('doc-pozos');

    Route::get('doc-pozos/crear', [DocPozoController::class, 'create'])
        ->name('doc-pozos.create');

    Route::post('doc-pozos', [DocPozoController::class, 'store'])
        ->name('doc-pozos.store');

    /* Catálogo de Pozos: Componentes */
    //Route::resource('componentespozos', ComponentePozoController::class)->only(['index']);

    Route::get('componente-pozos', [ComponentePozoController::class, 'index'])
        ->name('componente-pozos');

    Route::get('componente-pozos/crear', [ComponentePozoController::class, 'create'])
        ->name('componente-pozos.create')->middleware('can:create,App\Models\ComponentePozo');
        
    Route::get('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'show'])
        ->name('componente-pozos.show');

    Route::put('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'update'])
        ->name('componente-pozos.update')->middleware('can:update,App\Models\ComponentePozo');

    Route::put('componente-pozos/{componentePozo}/restore', [ComponentePozoController::class, 'restore'])
        ->name('pocomponente-pozoszos.restore')->middleware('can:restore,App\Models\ComponentePozo');

    Route::delete('componente-pozos/{componentePozo}', [ComponentePozoController::class, 'destroy'])
        ->name('componente-pozos.destroy')->middleware('can:delete,App\Models\ComponentePozo');

    Route::post('/componente-pozos', function (Request $request) {
            $file = $request->file('file');
        
            // Parse the Excel file and extract the data
            // Here, we're using the PHPExcel library to do this
            $reader = IOFactory::createReaderForFile($file);
            $reader->setReadDataOnly(true);
            $excel = $reader->load($file);
            $worksheet = $excel->getActiveSheet();
            $rows = $worksheet->toArray();
        
            // Insert the data into the database
            DB::table('componente-pozos')->insert($rows);
        
            return response()->json(['success' => true]);
        });

    Route::get('componente-pozos/export/{componentePozo}', [ComponentePozoController::class, 'export'])
        ->name('componente-pozos.export');

    /* Cromatografías: Gas */
    //Route::resource('cromatografiagas', CromatografiaGasController::class)->only(['index']);

    Route::get('cromatografia-gases', [CromatografiaGasController::class, 'index'])
        ->name('cromatografia-gases');

    Route::get('cromatografia-gases/crear', [CromatografiaGasController::class, 'create'])
        ->name('cromatografia-gases.create');

    /* Cromatografías: Liquída */
    //Route::resource('cromatografialiquida', CromatografiaLiquidaController::class)->only(['index']);

    Route::get('cromatografia-liquidas', [CromatografiaLiquidaController::class, 'index'])
        ->name('cromatografia-liquidas');

    Route::get('cromatografia-liquidas/crear', [CromatografiaLiquidaController::class, 'create'])
        ->name('cromatografia-liquidas.create');

    /* Gráficas Generales */
    Route::resource('graficas', GraficaController::class)->only(['index']);

    /* Menús */
    Route::resource('menu', MenuController::class)->only(['index']);
});

/* Autenticación */
require __DIR__.'/auth.php';