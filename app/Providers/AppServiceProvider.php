<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\Captcha;

class AppServiceProvider extends ServiceProvider
{ 
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            return $response->json('success');
        });
        Vite::prefetch(concurrency: 3);
        Inertia::share([
            'flash' => fn () => [
                'success' => session('success'),
                'error'   => session('error'),
            ],
        ]);
        View::composer('admin.*', function ($view) {
            if (Auth::guard('admin')->check()) {
                $admin = Auth::guard('admin')->user();
                
                $view->with([
                    'admin_name' => $admin->name,
                    'admin_email' => $admin->email,
                    'admin_role' => $admin->role ?? 'Admin',
                    'admin_avatar' => $admin->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) . '&background=random&color=fff',
                    'admin_id' => $admin->id,
                    'admin_joined' => $admin->created_at->format('M Y'),
                ]);
            } else {
                // Default values if not authenticated
                $view->with([
                    'admin_name' => 'Guest',
                    'admin_role' => 'Guest',
                    'admin_avatar' => 'https://ui-avatars.com/api/?name=Guest&background=ccc&color=000',
                ]);
            }
        });

    }
}
