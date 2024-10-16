<?php

use App\Http\Controllers\App\CataloguesSatController;
use Illuminate\Support\Facades\Route;



// Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    
    Route::group(['prefix' => 'sat'], function () {
        Route::get('/addresses/zip_code/{zip_code}', [CataloguesSatController::class, 'search_zip_code'])->name('sat.search.zip.code');
    });

// });
