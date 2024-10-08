<?php

use App\Http\Controllers\App\ProvidersController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-providers";


// Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpoint, $endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection', [ProvidersController::class, 'collection'])->name("$endpointApp.collection");
        Route::post('/store', [ProvidersController::class, 'store'])->name("$endpointApp.store");
        Route::get('/{id_token}/show', [ProvidersController::class, 'show'])->name("$endpointApp.show");
        Route::post('/{id_token}/update', [ProvidersController::class, 'update'])->name("$endpointApp.update");
        Route::delete('/{id_token}/destroy', [ProvidersController::class, 'destroy'])->name("$endpointApp.destroy");
        Route::post('/{id_token}/change-status', [ProvidersController::class, 'change_status'])->name("$endpointApp.change_status");

    });
// });