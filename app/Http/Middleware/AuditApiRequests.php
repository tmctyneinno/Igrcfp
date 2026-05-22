<?php

namespace App\Http\Middleware;

use App\Jobs\Api\WriteApiAuditLogJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuditApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = (string) Str::uuid();
        $request->headers->set('X-Request-Id', $requestId);

        $response = $next($request);
        $response->headers->set('X-Request-Id', $requestId);

        $user = $request->user();
        $token = $user?->currentAccessToken();

        $payload = [
            'request_id' => $requestId,
            'actor_id' => $user?->getAuthIdentifier(),
            'token_id' => $token?->id,
            'token_name' => $token?->name,
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'occurred_at' => now()->toISOString(),
        ];

        WriteApiAuditLogJob::dispatch($payload);

        return $response;
    }
}
