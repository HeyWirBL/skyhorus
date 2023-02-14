<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectorioController;
use App\Models\Ano;
use App\Models\Mes;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
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

Route::get('/usuarios', function () {
    return Inertia::render('Usuarios/Index', [
        'filtros' => Request::only(['search']),
        'usuarios' => Usuario::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('nombre', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($usuario) => [
                    'idUsuario' => $usuario->idUsuario,
                    'nombre' => $usuario->nombre,
                    'apellidos' => $usuario->apellidos,
                    'rol' => $usuario->rol,
                    'deleted_at' => $usuario->deleted_at,
                    'user' => $usuario->user ? $usuario->user->only('username', 'email') : null,
                ]),                    
    ]);
});

Route::get('/usuarios/create', function () {
    return Inertia::render('Usuarios/Create');
});

/* TODO: Fix Store One-to-One Method */
Route::post('/usuarios', function () {

    // $user = User::create([
    //     'username' => Request::get('username'),
    //     'email' => Request::get('email'),
    //     'password' => Hash::make(Request::get('password')),
    // ]);
    // 
    // Usuario::create([
    //     'nombre' => Request::get('nombre'),
    //     'apellidos' => Request::get('apellidos'),
    //     'rol' => Request::get('rol'),
    //     'user_id' => $user->id,
    // ]);
 
    // // redirect 
    // return redirect('/usuarios');
});

/* Catálogo de Directorios / Carpetas */
Route::get('directorios/{directorio}/edit', [DirectorioController::class, 'edit'])
    ->name('directorios.edit');
    
Route::resource('directorios', DirectorioController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy']);

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

Route::post('/logout', function () {
    dd('loggin the user out');
});