<?php

namespace App\Jobs\Api;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class WriteApiAuditLogJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly array $payload)
    {
    }

    public function handle(): void
    {
        Log::channel(config('logging.default'))->info('api_audit', $this->payload);
    }
}
