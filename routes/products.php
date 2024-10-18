<?php

use App\Http\Controllers\CategoriesProductsController;
use Illuminate\Support\Facades\Route;

$endpointCategoryProducts = 'categorias-de-producto';

Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointCategoryProducts) {

    // PROVEEDORES
    Route::group(['prefix' => $endpointCategoryProducts], function () use ($endpointCategoryProducts) {
        Route::get('/', [CategoriesProductsController::class, 'index'])->name("$endpointCategoryProducts.index");
        Route::get('/nuevo', [CategoriesProductsController::class, 'create'])->name("create.$endpointCategoryProducts");
        Route::get('/{id_token}/editar', [CategoriesProductsController::class, 'edit'])->name("edit.$endpointCategoryProducts");
    });
});


