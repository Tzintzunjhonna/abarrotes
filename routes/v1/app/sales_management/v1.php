<?php

use App\Http\Controllers\App\SalesManagement\SalesManagementController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-sales-management";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::post('/store', [SalesManagementController::class, 'store'])->name("$endpointApp.store");

    });
});