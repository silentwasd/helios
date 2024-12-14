<?php

use App\Http\Controllers\Ai;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Server;
use App\Http\Controllers\Server\Certbot\CertController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SshKeyController;
use App\Http\Middleware\ServiceMiddleware;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiSingleton('profile', ProfileController::class)
         ->only(['show']);

    Route::apiResource('ssh-keys', SshKeyController::class)
         ->except(['show']);

    Route::apiResource('servers', ServerController::class);

    Route::apiResource('servers.programs', Server\ProgramController::class);

    Route::apiSingleton('servers.nginx', Server\Nginx\ConfigController::class)
         ->only(['show', 'update']);

    Route::prefix('servers/{server}/nginx')->group(function () {
        Route::apiResource('sites', Server\Nginx\SiteController::class);
        Route::patch('sites/{site}/enable', [Server\Nginx\SiteController::class, 'enable']);
        Route::patch('sites/{site}/disable', [Server\Nginx\SiteController::class, 'disable']);

        Route::apiResource('logs', Server\Nginx\LogController::class)
             ->only(['index', 'show', 'destroy']);
    });

    Route::prefix('servers/{server}/certbot')->group(function () {
        Route::apiResource('certs', CertController::class)
             ->only(['index', 'store', 'update']);
    });

    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('projects.applications', Project\ApplicationController::class)->scoped();
});

Route::middleware([ServiceMiddleware::class])->prefix('ai')->group(function () {
    Route::post('servers/{server}/execute-command', [Ai\ServerController::class, 'executeCommand']);
});