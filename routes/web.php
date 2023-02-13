<?php

use App\Models\Ano;
use App\Models\Directorio;
use App\Models\Mes;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
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

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'user' => [
            'name' => 'Sam Hernandez'
        ]
    ]);
});

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

Route::get('/directorios', function () {
    return Inertia::render('Directorios/Index', [
        'filtros' => Request::only(['search']),
        'directorios' => Directorio::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('nombre_dir', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($directorio) => [
                    'idDirectorio' => $directorio->idDirectorio,
                    'nombre_dir' => $directorio->nombre_dir,
                    'fecha_dir' => $directorio->fecha_dir,
                ]),        
        
    ]);
});

Route::get('/directorios/create', function () {
    return Inertia::render('Directorios/Create');
});

Route::post('/directorios', function () {
    $attributes = Request::validate([
        'nombre_dir' => 'required',
        'fecha_dir' => 'required',
    ]);

    Directorio::create($attributes);

    return Redirect::route('directorios')->with('success', 'Carpeta creada.');
});

Route::get('/meses', function () {
    return Inertia::render('Meses/Index', [
        'meses' => Mes::all()->map(fn($mes) => [
            ''
        ]),
    ]);
});

Route::get('/anos', function () {
    return Inertia::render('Anos/Index', [
        'anos' => Ano::all()->map(fn($ano) => [
            'idAno' => $ano->idAno,
            'ano' => $ano->ano,
        ]),
    ]);
});

Route::get('/pozos', function () {
    return Inertia::render('Pozos/Index');
});

Route::post('/logout', function () {
    dd('loggin the user out');
});