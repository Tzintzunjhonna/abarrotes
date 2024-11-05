<?php

use App\Http\Controllers\App\CataloguesSatController;
use Illuminate\Support\Facades\Route;



    
Route::group(['prefix' => 'sat'], function () {
    Route::get('/addresses/zip_code/{zip_code}', [CataloguesSatController::class, 'search_zip_code'])->name('sat.search.zip.code');
    Route::get('/search-cat-sat-producto-servicio', [CataloguesSatController::class, 'search_clave_producto_servicio'])->name('sat.search.search_clave.producto.servicio');
    Route::get('/search-cat-sat-clave-unidad', [CataloguesSatController::class, 'search_clave_clave_unidad'])->name('sat.search.search_clave.clave.unidad');
});

