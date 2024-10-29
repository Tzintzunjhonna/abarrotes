<?php

use App\Http\Controllers\Configuration\TaxSettingsController;
use Illuminate\Support\Facades\Route;

$endpoint = 'configuracion';
$endpointCatalogoIva = 'catalogo-impuestos';

Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')
->prefix($endpoint)
->group(function () use (
    $endpointCatalogoIva) {

    // CATALOGO DE IMPUESTOS
    Route::group(['prefix' => $endpointCatalogoIva], function () use ($endpointCatalogoIva) {
        Route::get('/', [TaxSettingsController::class, 'index'])->name("$endpointCatalogoIva.index");
        Route::get('/nuevo', [TaxSettingsController::class, 'create'])->name("create.$endpointCatalogoIva");
        Route::get('/{id_token}/editar', [TaxSettingsController::class, 'edit'])->name("edit.$endpointCatalogoIva");
    });
});


