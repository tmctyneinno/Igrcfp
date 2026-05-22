<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatformAuthorize
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if (! $token) {
            return $this->errorResponse('Unauthorized token', status: 401);
        }

        $allowedPlatforms = array_filter(array_map('trim', explode(',', (string) config('services.wgrcfp.allowed_platforms', 'WGRCFP'))));
        $allowedOrigins = array_filter(array_map('trim', explode(',', (string) config('services.wgrcfp.allowed_origins', ''))));

        $platformName = $token->name;
        $origin = (string) $request->headers->get('Origin', '');

        if (! in_array($platformName, $allowedPlatforms, true)) {
            return $this->errorResponse('Platform is not authorized', status: 403);
        }

        if (! empty($allowedOrigins) && $origin !== '' && ! in_array($origin, $allowedOrigins, true)) {
            return $this->errorResponse('Request origin is not authorized', status: 403);
        }

        return $next($request);
    }
}
