<?php

use App\Http\Middleware\AuditApiRequests;
use App\Http\Middleware\EnsureApiKey;
use App\Http\Middleware\PlatformAuthorize;
use App\Http\Middleware\ResolveExternalUserContext;
use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.role' => \App\Http\Middleware\AdminRoleMiddleware::class,
            'auth.admin' => \App\Http\Middleware\AuthenticateAdmin::class,
            'guest.admin' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'enrollment.redirect' => \App\Http\Middleware\HandleEnrollmentRedirect::class,
            'active.membership' => \App\Http\Middleware\EnsureActiveMembership::class,
            'mentor.membership' => \App\Http\Middleware\EnsureMentorMembership::class,
            'platform.authorize' => PlatformAuthorize::class,
            'audit.api' => AuditApiRequests::class,
            'api.key' => EnsureApiKey::class,
            'external.user' => ResolveExternalUserContext::class,
        ]);

        $middleware->group('admin', [
            'auth.admin',
            'web',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\HandleEnrollmentRedirect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $respond = new class {
            use ApiResponse;
        };

        $exceptions->render(function (ValidationException $e, $request) use ($respond) {
            if (! $request->is('api/*')) {
                return null;
            }

            return $respond->errorResponse(
                message: 'Validation failed',
                data: ['errors' => $e->errors()],
                status: 422
            );
        });

        $exceptions->render(function (AuthenticationException $e, $request) use ($respond) {
            return $request->is('api/*') ? $respond->errorResponse('Unauthenticated', status: 401) : null;
        });

        $exceptions->render(function (AuthorizationException $e, $request) use ($respond) {
            return $request->is('api/*') ? $respond->errorResponse('Forbidden', status: 403) : null;
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) use ($respond) {
            return $request->is('api/*') ? $respond->errorResponse('Resource not found', status: 404) : null;
        });

        $exceptions->render(function (HttpExceptionInterface $e, $request) use ($respond) {
            if (! $request->is('api/*')) {
                return null;
            }

            return $respond->errorResponse($e->getMessage() ?: 'HTTP error', status: $e->getStatusCode());
        });

        $exceptions->render(function (Throwable $e, $request) use ($respond) {
            if (! $request->is('api/*')) {
                return null;
            }

            return $respond->errorResponse(
                message: config('app.debug') ? $e->getMessage() : 'Server error',
                data: config('app.debug') ? ['exception' => class_basename($e)] : null,
                status: 500
            );
        });
    })->create();
