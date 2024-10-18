<?php

use App\Http\Controllers\App\CustomersController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-customers";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection', [CustomersController::class, 'collection'])->name("$endpointApp.collection");
        Route::post('/store', [CustomersController::class, 'store'])->name("$endpointApp.store");
        Route::get('/{id_token}/show', [CustomersController::class, 'show'])->name("$endpointApp.show");
        Route::post('/{id_token}/update', [CustomersController::class, 'update'])->name("$endpointApp.update");
        Route::delete('/{id_token}/destroy', [CustomersController::class, 'destroy'])->name("$endpointApp.destroy");
        Route::post('/{id_token}/change-status', [CustomersController::class, 'change_status'])->name("$endpointApp.change_status");

    });
});