<?php

use App\Http\Controllers\App\Admin\RolesController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-roles";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection', [RolesController::class, 'collection'])->name("$endpointApp.collection");
        Route::post('/store', [RolesController::class, 'store'])->name("$endpointApp.store");
        Route::get('/{id_token}/show', [RolesController::class, 'show'])->name("$endpointApp.show");
        Route::put('/{id_token}/update', [RolesController::class, 'update'])->name("$endpointApp.update");
        Route::delete('/{id_token}/destroy', [RolesController::class, 'destroy'])->name("$endpointApp.destroy");
        Route::put('/{id_token}/assing/permissions', [RolesController::class, 'assing_permissions'])->name("$endpointApp.assing_permissions");

    });
});