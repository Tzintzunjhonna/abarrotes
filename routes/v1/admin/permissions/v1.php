<?php

use App\Http\Controllers\App\Admin\PermissionsController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-permissions";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection', [PermissionsController::class, 'collection'])->name("$endpointApp.collection");
        Route::post('/store', [PermissionsController::class, 'store'])->name("$endpointApp.store");
        Route::get('/{id_token}/show', [PermissionsController::class, 'show'])->name("$endpointApp.show");
        Route::put('/{id_token}/update', [PermissionsController::class, 'update'])->name("$endpointApp.update");
        Route::delete('/{id_token}/destroy', [PermissionsController::class, 'destroy'])->name("$endpointApp.destroy");

    });
});