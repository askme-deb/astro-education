<?php

return [
    'base_url' => env('LMS_API_BASE_URL', env('LMS_API_BASE_URL', 'https://admin.astrorajumaharaj.com/api/v1')),
    'timeout' => (int) env('LMS_API_TIMEOUT', env('LMS_API_TIMEOUT', 10)),
    'retry' => (int) env('LMS_API_RETRY', env('LMS_API_RETRY', 2)),
    'endpoints' => [
        'login' => '/login',
        'register' => '/register',
        'request_otp' => '/login/otp/request',
        'resend_otp' => '/login/otp/resend',
        'verify_otp' => '/login/otp/verify',
    ],
    'features' => [
        'log_payloads' => (bool) env('LMS_AUTH_API_LOG_PAYLOADS', false),
    ],
];
