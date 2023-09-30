<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class JsonAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AuthenticationException $exception) {
            return response()->json(['message' => 'Login failed'], 403);
        }
    }
}
