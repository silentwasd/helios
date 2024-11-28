<?php

use App\Http\Controllers\SshKeyController;

Route::apiResource('ssh-keys', SshKeyController::class)
     ->except(['show']);
