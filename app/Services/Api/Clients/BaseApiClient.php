<?php

namespace App\Services\Api\Clients;

use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\RequestException;
use Throwable;

/**
 * BaseApiClient
 * Centralized API client for all LMS API communication.
 * Handles authorization, retries, timeouts, logging, and unified response formatting.
 */
abstract class BaseApiClient
{
    protected string $baseUrl;
    protected ?string $token;
    protected int $timeout;
    protected int $retryTimes;
    protected int $retrySleep;
    protected ?string $lastTokenSource = null;
    protected bool $lastAuthHeaderPresent = false;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.lms_api.base_url', '');
        $this->token = config('services.lms_api.token') ?: null;
        $this->timeout = (int) config('services.lms_api.timeout', 30);
        $this->retryTimes = (int) config('services.lms_api.retry_times', 3);
        $this->retrySleep = (int) config('services.lms_api.retry_sleep', 500);
    }

    protected function buildHeaders(array $headers = []): array
    {
        $default = [
            'Accept' => 'application/json',
        ];

        $tokenContext = $this->resolveTokenContext();
        $token = $tokenContext['token'];
        $this->lastTokenSource = $tokenContext['source'];
        $this->lastAuthHeaderPresent = is_string($token) && $token !== '';

        if ($token) {
            $default['Authorization'] = 'Bearer ' . $token;
        }

        return array_merge($default, $headers);
    }

    protected function resolveToken(): ?string
    {
        return $this->resolveTokenContext()['token'];
    }

    protected function resolveTokenContext(): array
    {
        $sessionToken = Session::get('auth.api_token');

        if (is_string($sessionToken) && $sessionToken !== '') {
            return [
                'token' => $sessionToken,
                'source' => 'session',
            ];
        }

        $cookieToken = request()?->cookie('auth_api_token');

        if (is_string($cookieToken) && $cookieToken !== '') {
            return [
                'token' => $cookieToken,
                'source' => 'cookie',
            ];
        }

        if (is_string($this->token) && $this->token !== '') {
            return [
                'token' => $this->token,
                'source' => 'config',
            ];
        }

        return [
            'token' => null,
            'source' => null,
        ];
    }

    protected function request(string $method, string $uri, array $options = [])
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($uri, '/');
        $headers = $this->buildHeaders($options['headers'] ?? []);
        $body = $options['body'] ?? $options['json'] ?? [];
        $query = $options['query'] ?? [];
        $multipart = $options['multipart'] ?? null;
        $cacheKey = $options['cache_key'] ?? null;
        $cacheTtl = $options['cache_ttl'] ?? null;

        // Caching for GET requests
        if ($method === 'GET' && $cacheKey && $cacheTtl) {
            return $this->rememberResponse($cacheKey, $cacheTtl, ['api', $uri], function () use ($method, $url, $headers, $query) {
                return $this->sendRequest($method, $url, [
                    'headers' => $headers,
                    'query' => $query,
                ]);
            });
        }

        return $this->sendRequest($method, $url, [
            'headers' => $headers,
            'query' => $query,
            'body' => $body,
            'multipart' => $multipart,
        ]);
    }

    protected function rememberResponse(string $cacheKey, int $cacheTtl, array $tags, callable $callback): mixed
    {
        $repository = Cache::store();

        if ($this->supportsTags($repository)) {
            return Cache::tags($tags)->remember($cacheKey, $cacheTtl, $callback);
        }

        return $repository->remember($cacheKey, $cacheTtl, $callback);
    }

    protected function supportsTags(CacheRepository $repository): bool
    {
        return $repository->getStore() instanceof TaggableStore;
    }

    protected function sendRequest(string $method, string $url, array $options)
    {
        try {
            $payload = [
                'query' => $options['query'] ?? [],
            ];

            if (is_array($options['multipart'] ?? null) && $options['multipart'] !== []) {
                $payload['multipart'] = $options['multipart'];
            } elseif (array_key_exists('body', $options) && is_array($options['body']) && $options['body'] !== []) {
                $payload['json'] = $options['body'];
            }

            $response = Http::withOptions([
                'timeout' => $this->timeout,
            ])
                ->withHeaders($options['headers'] ?? [])
                ->retry($this->retryTimes, $this->retrySleep, function ($exception) {
                    return $exception instanceof RequestException;
                }, false)
                ->send($method, $url, $payload);

            return $this->handleResponse($response);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        }
        Log::warning('API Error', [
            'url' => $response->effectiveUri(),
            'status' => $response->status(),
            'authorization_present' => $this->lastAuthHeaderPresent,
            'token_source' => $this->lastTokenSource,
            'body' => $response->body(),
        ]);
        return [
            'success' => false,
            'error' => $response->json('message') ?? 'API Error',
            'status' => $response->status(),
        ];
    }

    protected function handleException(Throwable $e)
    {
        Log::error('API Exception', [
            'message' => $e->getMessage(),
            'authorization_present' => $this->lastAuthHeaderPresent,
            'token_source' => $this->lastTokenSource,
            'trace' => $e->getTraceAsString(),
        ]);
        return [
            'success' => false,
            'error' => 'Service unavailable. Please try again later.',
            'status' => 500,
        ];
    }

    public function get(string $uri, array $query = [], array $options = [])
    {
        $options['query'] = $query;
        return $this->request('GET', $uri, $options);
    }

    public function post(string $uri, array $body = [], array $options = [])
    {
        $options['body'] = $body;
        return $this->request('POST', $uri, $options);
    }

    public function put(string $uri, array $body = [], array $options = [])
    {
        $options['body'] = $body;
        return $this->request('PUT', $uri, $options);
    }

    public function delete(string $uri, array $options = [])
    {
        return $this->request('DELETE', $uri, $options);
    }

    public function upload(string $uri, array $files, array $body = [], array $options = [])
    {
        $multipart = [];
        foreach ($files as $name => $file) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }
        foreach ($body as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }
        $options['multipart'] = $multipart;
        return $this->request('POST', $uri, $options);
    }
}
