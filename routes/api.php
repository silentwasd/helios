<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SshKeyController;
use App\Http\Controllers\Project;
use App\Http\Controllers\Server;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiSingleton('profile', ProfileController::class)
         ->only(['show']);

    Route::apiResource('ssh-keys', SshKeyController::class)
         ->except(['show']);

    Route::apiResource('servers', ServerController::class);

    Route::apiResource('servers.programs', Server\ProgramController::class);

    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('projects.applications', Project\ApplicationController::class)->scoped();
});
