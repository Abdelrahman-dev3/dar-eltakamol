<?php

namespace App\Providers;

use App\Support\RoutePermissionMap;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('routePermission', function (...$routeNames): bool {
            return RoutePermissionMap::userCanAccess(auth()->user(), ...$routeNames);
        });
    }
}
