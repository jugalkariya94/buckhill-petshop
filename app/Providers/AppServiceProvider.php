<?php

namespace App\Providers;

use App\Services\Guards\JWTGuard;
use App\Services\JWTService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->singleton(JWTService::class, function ($app) {
            return new JWTService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Auth::extend('jwt', function ($app, $name, array $config) {
            $jwtService = $app->make(JWTService::class);
            $guard = new JWTGuard(Auth::createUserProvider($config['provider']), $app['request'], $jwtService);
            $app->refresh('request', $guard, 'setRequest');
            return $guard;
        });

    }
}
