<?php

use App\Http\Controllers\App\CategoriesProductsController;
use App\Http\Controllers\App\ProductsController;
use Illuminate\Support\Facades\Route;

$endpointCategoryProducts = "app-category-products";
$endpointProducts = "app-products";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointCategoryProducts, $endpointProducts) {

    // CATEGORIAS DE PRODUCTOS

    Route::group(['prefix' => "$endpointCategoryProducts"], function () use ($endpointCategoryProducts) {

        Route::get('/collection', [CategoriesProductsController::class, 'collection'])->name("$endpointCategoryProducts.collection");
        Route::post('/store', [CategoriesProductsController::class, 'store'])->name("$endpointCategoryProducts.store");
        Route::get('/{id_token}/show', [CategoriesProductsController::class, 'show'])->name("$endpointCategoryProducts.show");
        Route::post('/{id_token}/update', [CategoriesProductsController::class, 'update'])->name("$endpointCategoryProducts.update");
        Route::delete('/{id_token}/destroy', [CategoriesProductsController::class, 'destroy'])->name("$endpointCategoryProducts.destroy");
        Route::post('/{id_token}/change-status', [CategoriesProductsController::class, 'change_status'])->name("$endpointCategoryProducts.change_status");

    });
    
    // PRODUCTOS

    Route::group(['prefix' => "$endpointProducts"], function () use ($endpointProducts) {

        Route::get('/collection', [ProductsController::class, 'collection'])->name("$endpointProducts.collection");
        Route::post('/store', [ProductsController::class, 'store'])->name("$endpointProducts.store");
        Route::get('/{id_token}/show', [ProductsController::class, 'show'])->name("$endpointProducts.show");
        Route::post('/{id_token}/update', [ProductsController::class, 'update'])->name("$endpointProducts.update");
        Route::delete('/{id_token}/destroy', [ProductsController::class, 'destroy'])->name("$endpointProducts.destroy");
        Route::post('/{id_token}/change-status', [ProductsController::class, 'change_status'])->name("$endpointProducts.change_status");

    });
});