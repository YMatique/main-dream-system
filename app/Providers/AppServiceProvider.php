<?php

namespace App\Providers;

use App\Auth\EmployeePortalGuard;
use App\Auth\EmployeePortalUserProvider;
use App\Models\Company\RepairOrder\RepairOrderForm2;
use App\Models\Company\RepairOrder\RepairOrderForm3;
use App\Observers\RepairOrderForm2Observer;
use App\Observers\RepairOrderForm3Observer;
use App\Services\ActivityLoggerService;
use App\Services\AuditService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
         $this->app->singleton(AuditService::class, function ($app) {
            return new AuditService();
        });
         $this->app->singleton(ActivityLoggerService::class, function ($app) {
            return new ActivityLoggerService();
        });

          require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar observers para auto-geração de faturação
        RepairOrderForm2::observe(RepairOrderForm2Observer::class);
        RepairOrderForm3::observe(RepairOrderForm3Observer::class);

        //  Event::listen(Login::class, function (Login $event) {
        //     app(AuditService::class)->logLogin($event->user);
        // });
        //  Event::listen(Logout::class, function (Logout $event) {
        //     if ($event->user) {
        //         app(AuditService::class)->logLogout($event->user);
        //     }
        // });

        // Event::listen(Failed::class, function (Failed $event) {
        //     app(AuditService::class)->logFailedLogin($event->credentials['email'] ?? 'unknown');
        // });

           // Registrar User Provider personalizado para o portal
        // Auth::provider('employee_portal_provider', function ($app, array $config) {
        //     return new EmployeePortalUserProvider();
        // });

        // // Registrar Guard personalizado para o portal
        // Auth::extend('employee_portal', function ($app, $name, array $config) {
        //     return new EmployeePortalGuard(
        //         Auth::createUserProvider($config['provider']),
        //         $app['request']
        //     );
        // });
    }
}
