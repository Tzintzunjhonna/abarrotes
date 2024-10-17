<?php

use App\Http\Controllers\App\Admin\CompanyController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-company";


// Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpoint, $endpointApp) {

    // USUARIOS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::post('/update', [CompanyController::class, 'update'])->name("$endpointApp.update");

    });
// });