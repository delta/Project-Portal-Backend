<?php

namespace App\Http\Middleware;

use Closure;

class JsonResponse
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
