<?php

use App\Models\Usuario;
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

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'user' => [
            'name' => 'Sam Hernandez'
        ]
    ]);
});

Route::get('/usuarios', function () {
    return Inertia::render('Usuarios', [
        'usuarios' => Usuario::all()->map(fn($usuario) => [
            'idUsuario' => $usuario->idUsuario,
            'nombre' => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'rol' => $usuario->rol,
        ]),
    ]);
});

Route::post('/logout', function () {
    dd('loggin the user out');
});