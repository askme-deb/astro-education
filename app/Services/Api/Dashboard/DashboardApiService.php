<?php

declare(strict_types=1);

namespace App\Services\Api\Dashboard;

use App\Services\Api\Clients\BaseApiClient;

/**
 * Aggregates dashboard widgets from the LMS API.
 */
class DashboardApiService extends BaseApiClient
{
    public function overview(): array
    {
        return $this->get('dashboard', [], [
            'cache_key' => 'dashboard.overview',
            'cache_ttl' => (int) config('services.lms_api.cache_ttl', 3600),
        ]);
    }
}
