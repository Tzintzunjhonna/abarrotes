<?php

use App\Http\Controllers\SalesManagement\SalesController;
use Illuminate\Support\Facades\Route;

$endpoint = 'gestion-de-ventas';
$endpointSales = 'caja';
$endpointHistorySales = 'historial';

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->prefix($endpoint)->group(function () use ($endpointSales, $endpointHistorySales) {

    // CAJA
    Route::group(['prefix' => $endpointSales], function () use ($endpointSales) {
        Route::get('/', [SalesController::class, 'indexToCheckoutCounter'])->name("$endpointSales.indexToCheckoutCounter");
    });
    
    
    // HISTORIAL DE VENTAS
    Route::group(['prefix' => $endpointHistorySales], function () use ($endpointHistorySales) {
        Route::get('/', [SalesController::class, 'indexToHistory'])->name("$endpointHistorySales.indexToHistory");
    });
});
