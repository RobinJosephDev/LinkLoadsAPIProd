<?php

namespace App\Providers;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
