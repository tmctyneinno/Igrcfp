<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $providedApiKey = (string) $request->header('X-API-KEY', '');
        $configuredApiKey = (string) config('app.api_key', '');

        if ($providedApiKey === '' || $configuredApiKey === '' || ! hash_equals($configuredApiKey, $providedApiKey)) {
            return $this->errorResponse('Unauthorized', status: 401);
        }

        return $next($request);
    }
}

