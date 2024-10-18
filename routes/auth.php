<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::group(['prefix' => 'api'], function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout-in', [AuthController::class, 'logout']);
});

