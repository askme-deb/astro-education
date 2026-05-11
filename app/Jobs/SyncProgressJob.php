<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * SyncProgressJob
 * Handles background progress sync with retry and logging.
 */
class SyncProgressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $progressData;

    public function __construct($userId, $progressData)
    {
        $this->userId = $userId;
        $this->progressData = $progressData;
    }

    public function handle()
    {
        try {
            // Call API or service to sync progress
            // ...
        } catch (\Throwable $e) {
            Log::error('Progress sync failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }
}
