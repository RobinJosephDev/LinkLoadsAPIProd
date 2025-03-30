<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    public function getData()
    {
        // Check if data is already cached
        $data = Cache::get('key');

        if (!$data) {
            // Simulate data retrieval (e.g., from a database)
            $data = 'fetched data'; // Replace with actual data source

            // Store data in cache
            Cache::put('key', $data, now()->addMinutes(10));
        }

        return $data;
    }
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            $ip = $request->ip();
            $attempts = Cache::get($ip . '_attempts', 0);
        
            // **Check if the user has exceeded 12 failed attempts**
            if ($attempts >= 12) {
                return Limit::none()->response(function () {
                    return response()->json([
                        'error' => 'You have been permanently blocked due to too many failed login attempts.'
                    ], 403);
                });
            }
        
            // **If under 12 attempts, apply increasing delay**
            $delay = min(pow(2, $attempts), 3600);
        
            return Limit::perMinute(5)->by($ip)->response(function () use ($delay, $ip) {
                Cache::increment($ip . '_attempts');
                return response()->json([
                    'error' => "Too many login attempts. Please try again in $delay seconds."
                ], 429);
            });
        });    
        
    }
}
