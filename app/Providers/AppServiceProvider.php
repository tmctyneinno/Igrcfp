<?php

namespace App\Providers;

use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Validator::extend('captcha', function ($attribute, $value) {
            return (new Recaptcha())->passes($attribute, $value);
        });

        $this->configureApiRateLimiters();
        $this->configureApiAbilities();

        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'flash' => fn () => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);

        View::composer('admin.*', function ($view) {
            if (Auth::guard('admin')->check()) {
                $admin = Auth::guard('admin')->user();

                $view->with([
                    'admin_name' => $admin->name,
                    'admin_email' => $admin->email,
                    'admin_role' => $admin->role ?? 'Admin',
                    'admin_avatar' => $admin->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($admin->name).'&background=random&color=fff',
                    'admin_id' => $admin->id,
                    'admin_joined' => $admin->created_at->format('M Y'),
                ]);
            } else {
                $view->with([
                    'admin_name' => 'Guest',
                    'admin_role' => 'Guest',
                    'admin_avatar' => 'https://ui-avatars.com/api/?name=Guest&background=ccc&color=000',
                ]);
            }
        });
    }

    private function configureApiRateLimiters(): void
    {
        RateLimiter::for('external-api', function (Request $request) {
            $identity = $request->user()?->id ?: $request->ip();

            return [
                Limit::perMinute(120)->by('external:'.$identity),
                Limit::perMinute(30)->by('external-write:'.$identity)->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many requests',
                        'data' => (object) [],
                        'meta' => (object) [],
                    ], 429);
                }),
            ];
        });

        RateLimiter::for('sync-api', function (Request $request) {
            return Limit::perMinute(300)->by('sync:'.$request->user()?->currentAccessToken()?->id ?: $request->ip());
        });

        RateLimiter::for('quiz-submit', function (Request $request) {
            return Limit::perMinute(20)->by('quiz:'.$request->user()?->id ?: $request->ip());
        });
    }

    private function configureApiAbilities(): void
    {
        Gate::define('access-admin-api', function (User $user): bool {
            return $user->role === 'admin' || $user->currentAccessToken()?->can('admin.manage') === true;
        });
    }
}
