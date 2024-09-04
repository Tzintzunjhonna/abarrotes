<?php

use Illuminate\Support\Facades\Route;
use abarrotes\Admin\User\Infrastructure\Controllers\Collection\CollectionController;
use abarrotes\Admin\User\Infrastructure\Controllers\Create\CreateController;
use abarrotes\Admin\User\Infrastructure\Controllers\Delete\DeleteController;
use abarrotes\Admin\User\Infrastructure\Controllers\Find\FindController;
use abarrotes\Admin\User\Infrastructure\Controllers\Update\UpdateController;

/**
 * Rutas NO protegidas
 */
Route::group(['prefix' => 'users'], function(){
    Route::post('/', CreateController::class);
});


/**
 * Rutas protegidas
 */
//Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'users'], function(){
        Route::get('/', CollectionController::class);
        Route::get('{id}', FindController::class);
        Route::put('{id}', UpdateController::class);
        Route::delete('{id}', DeleteController::class);
    });
//});
