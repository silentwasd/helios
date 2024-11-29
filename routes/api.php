<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SshKeyController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiSingleton('profile', ProfileController::class)
         ->only(['show']);

    Route::apiResource('ssh-keys', SshKeyController::class)
         ->except(['show']);

    Route::apiResource('servers', ServerController::class);
});
