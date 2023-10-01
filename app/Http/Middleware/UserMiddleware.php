<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware

{
    public function handle($request, Closure $next)
    {
        if (auth()->user() && auth()->user()->role_id === 2) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden for you'], 403);
    }
}
