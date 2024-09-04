<?php

use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    // USUARIOS
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('usuarios');
    });
    Route::prefix('usuarios')->group(function () {
        Route::get('/{id_token}/editar', [UsersController::class, 'edit'])->name('editar.usuarios');
    });
});
