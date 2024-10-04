<?php

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum', config('jetstream.auth_session'), 'verified')->group(function () {

    // USUARIOS
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('usuarios');
        Route::get('/nuevo', [UsersController::class, 'create'])->name('create.usuarios');
        Route::get('/{id_token}/editar', [UsersController::class, 'edit'])->name('edit.usuarios');
    });
    
    // ROLES
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RolesController::class, 'index'])->name('roles');
        Route::get('/nuevo', [RolesController::class, 'create'])->name('create.roles');
        Route::get('/{id_token}/editar', [RolesController::class, 'edit'])->name('edit.roles');
        Route::get('/{id_token}/asignar', [RolesController::class, 'assing'])->name('assing.roles');
    });
    
    // PERMISSIONS
    Route::group(['prefix' => 'permisos'], function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('permissions');
        Route::get('/nuevo', [PermissionsController::class, 'create'])->name('create.permissions');
        Route::get('/{id_token}/editar', [PermissionsController::class, 'edit'])->name('edit.permissions');
    });
});


