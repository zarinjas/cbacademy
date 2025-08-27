<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Login rate limiting
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('app.security.rate_limiting.login.max_attempts'))
                ->by($request->input('email') . $request->ip());
        });

        // API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('app.security.rate_limiting.api.max_attempts'))
                ->by($request->user()?->id ?: $request->ip());
        });

        // Admin actions rate limiting
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(config('app.security.rate_limiting.admin.max_attempts'))
                ->by($request->user()?->id ?: $request->ip());
        });

        // Email verification rate limiting
        RateLimiter::for('email-verification', function (Request $request) {
            return Limit::perMinute(config('throttle.email_verification.max_attempts'))
                ->by($request->user()?->id ?: $request->ip());
        });

        // Password reset rate limiting
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(config('throttle.password_reset.max_attempts'))
                ->by($request->input('email') . $request->ip());
        });
    }
}
