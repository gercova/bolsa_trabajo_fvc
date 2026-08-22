<?php

namespace App\Providers;

use App\Models\Enterprise;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('enterprise', Enterprise::getDefault());
        });

        // Configuración de limitador de tasa (Rate Limiter) para login
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            $throttleKey = Str::transliterate(Str::lower($email) . '|' . $request->ip());

            return [
                Limit::perMinute(5)->by($throttleKey)->response(function (Request $request, array $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 60;
                    return back()->withErrors([
                        'email' => __('auth.throttle', [
                            'seconds' => $retryAfter,
                            'minutes' => (int) ceil($retryAfter / 60),
                        ]),
                    ])->onlyInput('email');
                }),
                Limit::perMinute(10)->by($request->ip()),
            ];
        });
    }
}
