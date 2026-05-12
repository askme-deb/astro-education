<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\Contracts\AuthApiServiceInterface;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function __construct(protected AuthApiServiceInterface $authApi)
    {
    }

    public function register(Request $request)
    {
        $data = $request->only(['first_name', 'last_name', 'mobile_no', 'email', 'password']);
        $response = $this->authApi->register($data);

        // If validation error, return errors to frontend
        if (isset($response['errors']) && is_array($response['errors'])) {
            // Get the first error message if available
            $firstError = $response['message'] ?? 'Validation error';
            foreach ($response['errors'] as $fieldErrors) {
                if (is_array($fieldErrors) && count($fieldErrors) > 0) {
                    $firstError = $fieldErrors[0];
                    break;
                }
            }
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $response['errors'],
            ], 422);
        }

        $isSuccessful = (bool) ($response['success'] ?? false);
        $userData = is_array($response['user'] ?? null) ? $response['user'] : null;
        $token = $response['token'] ?? $response['access_token'] ?? null;

        if ($isSuccessful && is_array($userData) && $token) {
            Session::regenerate();
            Session::put('auth.api_token', $token);
            Session::put('auth.user', $userData);
            Session::put('auth.roles', $response['roles'] ?? []);
            if (isset($userData['id'])) {
                Session::put('api_user_id', $userData['id']);
            }
            Session::save();

            $secure = $request->isSecure();
            $sessionCookie = cookie(
                config('session.cookie'),
                Session::getId(),
                config('session.lifetime'),
                config('session.path'),
                config('session.domain'),
                config('session.secure'),
                config('session.http_only'),
                false,
                config('session.same_site')
            );

            return response()->json([
                'success' => true,
                'message' => $response['message'] ?? 'Registration successful. You are now logged in.',
                'token' => $token,
                'user' => $userData,
                'redirect_url' => url('/'),
            ])
            ->cookie($sessionCookie)
            ->cookie(
                'auth_api_token',
                $token,
                60 * 24 * 7,
                '/',
                null,
                $secure,
                true,
                false,
                'Lax'
            );
        }

        return response()->json($response);
    }
}
