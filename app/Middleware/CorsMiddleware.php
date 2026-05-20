<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = [
            'http://127.0.0.1:8000',
            'http://127.0.0.1:8001',
            'http://localhost:8000',
            'http://localhost:8001',
        ];

        $origin = $request->headers->get('Origin');
        $allowOrigin = in_array($origin, $allowedOrigins, true) ? $origin : 'http://127.0.0.1:8001';

        if ($request->isMethod('OPTIONS')) {
            return response('', 204)->withHeaders($this->headers($allowOrigin));
        }

        $response = $next($request);

        foreach ($this->headers($allowOrigin) as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    private function headers(string $allowOrigin): array
    {
        return [
            'Access-Control-Allow-Origin' => $allowOrigin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN',
            'Access-Control-Max-Age' => '86400',
            'Vary' => 'Origin',
        ];
    }
}
