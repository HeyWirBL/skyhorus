<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

/* Login */
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});


/** Operaciones con Autenticación */
Route::middleware('auth')->group(function () {
    /** Actualizar Contraseña */
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    /* Logout / Cerrar sesión */
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
});

