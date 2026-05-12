<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiUserAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('auth_api_token');

        if (! $token) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return $next($request);
    }
}
