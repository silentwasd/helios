<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ServiceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() == 'YdDDoyuaJrrFbpp38oY2')
            return $next($request);

        abort(403, 'Unauthorized action.');
    }
}
