<?php

use App\Http\Controllers\App\SalesManagement\SalesManagementController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-sales-management";


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () use ($endpointApp) {

    // VENTAS

    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::post('/store', [SalesManagementController::class, 'store'])->name("$endpointApp.store");

    });


    // HISTORIAL DE VENTAS
    Route::group(['prefix' => "$endpointApp"], function () use ($endpointApp) {

        Route::get('/collection/history_sales', [SalesManagementController::class, 'history_sales_collection'])->name("$endpointApp.history.sales.collection");
        Route::post('/assing-customer-to-sale/{id_sale}/{id_customer}', [SalesManagementController::class, 'assing_customer_to_sale'])->name("$endpointApp.assing.customer.to.sale");
    });
});