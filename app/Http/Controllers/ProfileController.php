<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController
{
    public function show(Request $request)
    {
        return new ProfileResource($request->user());
    }
}
