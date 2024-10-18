<?php

use App\Http\Controllers\App\Admin\UsersController as AdminUsersController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-users";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection', [AdminUsersController::class, 'collection'])->name("$endpointApp.collection");
        Route::post('/store', [AdminUsersController::class, 'store'])->name("$endpointApp.store");
        Route::get('/{id_token}/show', [AdminUsersController::class, 'show'])->name("$endpointApp.show");
        Route::put('/{id_token}/update', [AdminUsersController::class, 'update'])->name("$endpointApp.update");
        Route::delete('/{id_token}/destroy', [AdminUsersController::class, 'destroy'])->name("$endpointApp.destroy");

    });
});