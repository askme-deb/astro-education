<?php

namespace App\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $token = $request->cookie('auth_api_token');

        if (! $token) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (session()->has('auth.user')) {
            $sessionUser = session('auth.user');
            $userData = is_array($sessionUser) ? $sessionUser : (array) $sessionUser;

            $user = new User();
            $user->forceFill($userData);

            if (isset($userData['id'])) {
                $user->setAttribute($user->getKeyName(), $userData['id']);
            }

            Auth::setUser($user);
        }

        return $next($request);
    }
}
