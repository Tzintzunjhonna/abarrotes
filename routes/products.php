<?php

use App\Http\Controllers\Products\CategoriesProductsController;
use App\Http\Controllers\Products\ProductsController;
use Illuminate\Support\Facades\Route;

$endpointCategoryProducts = 'categorias-de-producto';
$endpointProducts = 'productos';

Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use (
    $endpointCategoryProducts,
    $endpointProducts) {

    // CATEGORIA DE PRODUCTOS
    Route::group(['prefix' => $endpointCategoryProducts], function () use ($endpointCategoryProducts) {
        Route::get('/', [CategoriesProductsController::class, 'index'])->name("$endpointCategoryProducts.index");
        Route::get('/nuevo', [CategoriesProductsController::class, 'create'])->name("create.$endpointCategoryProducts");
        Route::get('/{id_token}/editar', [CategoriesProductsController::class, 'edit'])->name("edit.$endpointCategoryProducts");
    });
    
    // PRODUCTOS
    Route::group(['prefix' => $endpointProducts], function () use ($endpointProducts) {
        Route::get('/', [ProductsController::class, 'index'])->name("$endpointProducts.index");
        Route::get('/nuevo', [ProductsController::class, 'create'])->name("create.$endpointProducts");
        Route::get('/{id_token}/editar', [ProductsController::class, 'edit'])->name("edit.$endpointProducts");
    });
});


