<?php

namespace App\Services\Api\Contracts;

use App\Services\Api\DTOs\AuthApiResponse;

interface AuthApiServiceInterface
{
    public function login(string $email, string $password): array;

    public function register(array $payload): array;

    public function requestOtp(string $mobile, string $countryCode): AuthApiResponse;

    public function resendOtp(string $mobile, string $countryCode): AuthApiResponse;

    public function verifyOtp(string $mobile, string $countryCode, string $otp): AuthApiResponse;
}
