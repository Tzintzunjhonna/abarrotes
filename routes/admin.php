<?php

use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;


// USUARIOS
Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('usuarios');
    });
});
