<?php

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
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
        'filters' => Request::only(['search']),
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

Route::post('/logout', function () {
    dd('loggin the user out');
});