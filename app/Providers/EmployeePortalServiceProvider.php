<?php
namespace App\Providers;

use App\Auth\EmployeePortalGuard;
use App\Auth\EmployeePortalUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class EmployeePortalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Registrar User Provider personalizado
        Auth::provider('employee_portal', function ($app, array $config) {
            return new EmployeePortalUserProvider();
        });

        // Registrar Guard personalizado
        Auth::extend('employee_portal', function ($app, $name, array $config) {
            return new EmployeePortalGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
