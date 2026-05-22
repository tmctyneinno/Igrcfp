<?php

namespace App\Http\Middleware;

use App\Services\Api\V1\Identity\ExternalUserResolver;
use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ResolveExternalUserContext
{
    use ApiResponse;

    public function __construct(private readonly ExternalUserResolver $resolver)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $externalUser = $this->resolver->resolveFromRequest($request);
        } catch (InvalidArgumentException $exception) {
            return $this->errorResponse($exception->getMessage(), status: 401);
        }

        $request->attributes->set('external_user', $externalUser);
        $request->attributes->set('external_user_id', $externalUser->external_user_id);
        $request->attributes->set('external_client', $externalUser->client);

        return $next($request);
    }
}
