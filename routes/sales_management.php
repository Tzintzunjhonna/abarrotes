<?php

use App\Http\Controllers\SalesManagement\SalesController;
use Illuminate\Support\Facades\Route;

$endpoint = 'gestion-de-ventas';
$endpointSales = 'caja';

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->prefix($endpoint)->group(function () use ($endpointSales) {

    // CATEGORIA DE PRODUCTOS
    Route::group(['prefix' => $endpointSales], function () use ($endpointSales) {
        Route::get('/', [SalesController::class, 'index'])->name("$endpointSales.index");
    });
});
