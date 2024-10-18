<?php

use App\Http\Controllers\App\CategoriesProductsController;
use Illuminate\Support\Facades\Route;

$endpointCategoryProducts = "app-category-products";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointCategoryProducts) {

    // USUARIOS

    Route::group(['prefix' => "$endpointCategoryProducts"], function () use ($endpointCategoryProducts) {

        Route::get('/collection', [CategoriesProductsController::class, 'collection'])->name("$endpointCategoryProducts.collection");
        Route::post('/store', [CategoriesProductsController::class, 'store'])->name("$endpointCategoryProducts.store");
        Route::get('/{id_token}/show', [CategoriesProductsController::class, 'show'])->name("$endpointCategoryProducts.show");
        Route::post('/{id_token}/update', [CategoriesProductsController::class, 'update'])->name("$endpointCategoryProducts.update");
        Route::delete('/{id_token}/destroy', [CategoriesProductsController::class, 'destroy'])->name("$endpointCategoryProducts.destroy");
        Route::post('/{id_token}/change-status', [CategoriesProductsController::class, 'change_status'])->name("$endpointCategoryProducts.change_status");

    });
});