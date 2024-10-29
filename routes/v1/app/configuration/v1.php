<?php

use App\Http\Controllers\App\Configuration\TaxSettingsController;
use Illuminate\Support\Facades\Route;

$endpointApp = "app-configuration";
$endpointTaxSettings = 'tax-settings';


Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')
->prefix($endpointApp)
->group(function () use ($endpointTaxSettings) {

    // USUARIOS

    Route::group(['prefix' => "$endpointTaxSettings"], function () use ($endpointTaxSettings) {

        Route::get('/collection', [TaxSettingsController::class, 'collection'])->name("$endpointTaxSettings.collection");
        Route::post('/store', [TaxSettingsController::class, 'store'])->name("$endpointTaxSettings.store");
        Route::get('/{id_token}/show', [TaxSettingsController::class, 'show'])->name("$endpointTaxSettings.show");
        Route::post('/{id_token}/update', [TaxSettingsController::class, 'update'])->name("$endpointTaxSettings.update");
        Route::delete('/{id_token}/destroy', [TaxSettingsController::class, 'destroy'])->name("$endpointTaxSettings.destroy");
        Route::post('/{id_token}/change-status', [TaxSettingsController::class, 'change_status'])->name("$endpointTaxSettings.change_status");
        
        Route::post('/info-tasa-cuota', [TaxSettingsController::class, 'info_tasa_cuota'])->name("$endpointTaxSettings.info.tasa.cuota");



    });
});