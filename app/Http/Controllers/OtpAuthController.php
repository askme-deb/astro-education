<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestOtpRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\Api\Contracts\AuthApiServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class OtpAuthController extends Controller
{
    public function __construct(protected AuthApiServiceInterface $authApi)
    {
    }

    public function showLoginForm(Request $request): View
    {
        return view('auth.otp-login');
    }

    public function requestOtp(RequestOtpRequest $request): JsonResponse
    {
        $context = $request->string('context', 'header')->toString();

        $response = $this->authApi->requestOtp(
            $request->string('mobile_no')->toString(),
            $request->string('country_code')->toString()
        );

        if (! $response->success) {
            return response()->json([
                'success' => false,
                'message' => $response->message ?? 'Unable to send OTP. Please try again.',
                'context' => $context,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $response->message ?? 'OTP sent successfully.',
            'context' => $context,
        ]);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $context = $request->string('context', 'header')->toString();

        $response = $this->authApi->resendOtp(
            $request->string('mobile_no')->toString(),
            $request->string('country_code')->toString()
        );

        if (! $response->success) {
            return response()->json([
                'success' => false,
                'message' => $response->message ?? 'Unable to resend OTP. Please try again later.',
                'context' => $context,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $response->message ?? 'OTP resent successfully.',
            'context' => $context,
        ]);
    }

public function verifyOtp(VerifyOtpRequest $request): JsonResponse
{
    $context = $request->string('context', 'header')->toString();

    $response = $this->authApi->verifyOtp(
        $request->string('mobile_no')->toString(),
        $request->string('country_code')->toString(),
        $request->string('otp')->toString()
    );

    if (! $response->success) {
        return response()->json([
            'success' => false,
            'message' => $response->message ?? 'The OTP you entered is invalid or expired.',
            'context' => $context,
        ], 422);
    }

    $userPayload = $response->data['user'] ?? $response->data['customer'] ?? $response->data;
    $token       = $response->data['token'] ?? $response->data['access_token'] ?? null;

    if ($token === null) {
        Log::warning('OTP verification succeeded but no token returned from API.', [
            'context' => $context,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Authentication service did not return a session token.',
            'context' => $context,
        ], 500);
    }

    // Regenerate session to prevent fixation attacks
    Session::regenerate();

    // Store the raw Sanctum token and user data
    Session::put('auth.api_token', $token);

    $userData = $userPayload ?? [];

    if (! isset($userData['mobile_no'])) {
        $userData['mobile_no'] = $request->string('mobile_no')->toString();
    }

    Session::put('auth.user', $userData);

    if (isset($userData['id'])) {
        Session::put('api_user_id', $userData['id']);
    }

    // Force session to disk NOW before response is sent.
    // Critical for JSON responses — without this, the session data may not
    // be persisted before the next request reads it.
    Session::save();

    Log::info('verifyOtp session saved', [
        'session_id'     => Session::getId(),
        'token_preview'  => substr($token, 0, 20) . '...',
        'user_id'        => $userData['id'] ?? null,
    ]);

    $redirectUrl = $context === 'checkout'
        ? route('checkout.index')
        : (url()->previous() ?: route('products.index'));

    $secure = $request->isSecure();

    // Explicitly send the session cookie back with the new session ID.
    // Required when verifyOtp is on a route that doesn't automatically
    // append session cookies to the response (e.g. api middleware group).
    $sessionCookie = cookie(
        config('session.cookie'),          // e.g. 'laravel_session'
        Session::getId(),                  // the new ID after regenerate()
        config('session.lifetime'),        // minutes
        config('session.path'),            // '/'
        config('session.domain'),          // null or your domain
        config('session.secure'),          // true in production
        config('session.http_only'),       // true
        false,                             // raw
        config('session.same_site')        // 'lax'
    );

    return response()->json([
        'success'      => true,
        'message'      => $response->message ?? 'Logged in successfully.',
        'redirect_url' => $redirectUrl,
        'context'      => $context,
    ])
    ->cookie($sessionCookie)   // <-- sends updated session ID to browser
    ->cookie(
        'auth_api_token',      // unencrypted (excluded in EncryptCookies)
        $token,
        60 * 24 * 7,
        '/',
        null,
        $secure,
        true,                  // HttpOnly
        false,
        'Lax'
    );
}
    // public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    // {
    //     $context = $request->string('context', 'header')->toString();

    //     $response = $this->authApi->verifyOtp(
    //         $request->string('mobile_no')->toString(),
    //         $request->string('country_code')->toString(),
    //         $request->string('otp')->toString()
    //     );

    //     if (! $response->success) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $response->message ?? 'The OTP you entered is invalid or expired.',
    //             'context' => $context,
    //         ], 422);
    //     }

    //     $userPayload = $response->data['user'] ?? $response->data['customer'] ?? $response->data;
    //     $token = $response->data['token'] ?? $response->data['access_token'] ?? null;

    //     if ($token === null) {
    //         Log::warning('OTP verification succeeded but no token returned from API.', [
    //             'context' => $context,
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Authentication service did not return a session token.',
    //             'context' => $context,
    //         ], 500);
    //     }

    //     Session::regenerate();
    //     Session::put('auth.api_token', $token);

    //     $userData = $userPayload ?? [];

    //     if (! isset($userData['mobile_no'])) {
    //         $userData['mobile_no'] = $request->string('mobile_no')->toString();
    //     }

    //     Session::put('auth.user', $userData);

    //     if (isset($userData['id'])) {
    //         Session::put('api_user_id', $userData['id']);
    //     }

    //     $redirectUrl = $context === 'checkout'
    //         ? route('checkout.index')
    //         : (url()->previous() ?: route('products.index'));

    //     // Also persist the API token in a cookie so API routes (without session)
    //     // can authenticate requests to the external service.
    //     $secure = $request->isSecure();

    //     return response()->json([
    //         'success' => true,
    //         'message' => $response->message ?? 'Logged in successfully.',
    //         'redirect_url' => $redirectUrl,
    //         'context' => $context,
    //     ])->cookie(
    //         'auth_api_token',
    //         $token,
    //         60 * 24 * 7, // 7 days
    //         '/',
    //         null,
    //         $secure,
    //         true,
    //         false,
    //         'Lax'
    //     );
    // }

    public function logout(Request $request): JsonResponse
    {
        Session::forget('auth.api_token');
        Session::forget('auth.user');
        Session::forget('auth.roles');
        Session::forget('api_user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $removeTokenCookie = Cookie::forget(
            'auth_api_token',
            '/',
            config('session.domain')
        );

        $removeSessionCookie = Cookie::forget(
            config('session.cookie'),
            config('session.path'),
            config('session.domain')
        );

        $payload = [
            'success' => true,
            'message' => 'You have been logged out.',
            'redirect_url' => url('/'),
        ];

        $response = $request->expectsJson()
            ? response()->json($payload)
            : redirect('/');

        return $response
            ->withCookie($removeTokenCookie)
            ->withCookie($removeSessionCookie);
    }


    
}
