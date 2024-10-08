<?php

use App\Http\Controllers\CustomersController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    // PROVEEDORES
    Route::group(['prefix' => 'clientes'], function () {
        Route::get('/', [CustomersController::class, 'index'])->name('clientes');
        Route::get('/nuevo', [CustomersController::class, 'create'])->name('create.clientes');
        Route::get('/{id_token}/editar', [CustomersController::class, 'edit'])->name('edit.clientes');
    });
});


