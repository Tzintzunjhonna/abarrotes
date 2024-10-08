<?php

use App\Http\Controllers\ProvidersController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    // PROVEEDORES
    Route::group(['prefix' => 'proveedores'], function () {
        Route::get('/', [ProvidersController::class, 'index'])->name('proveedores');
        Route::get('/nuevo', [ProvidersController::class, 'create'])->name('create.proveedores');
        Route::get('/{id_token}/editar', [ProvidersController::class, 'edit'])->name('edit.proveedores');
    });
});


