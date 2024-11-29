<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\SshKeyController;

Route::apiResource('ssh-keys', SshKeyController::class)
     ->except(['show']);

Route::apiResource('servers', ServerController::class);
