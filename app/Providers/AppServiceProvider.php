<?php

namespace App\Providers;

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
         Event::listen(Login::class, function (Login $event) {
            app(AuditService::class)->logLogin($event->user);
        });
         Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                app(AuditService::class)->logLogout($event->user);
            }
        });

        Event::listen(Failed::class, function (Failed $event) {
            app(AuditService::class)->logFailedLogin($event->credentials['email'] ?? 'unknown');
        });



         // Registrar helper global
        if (!function_exists('locale_route')) {
            function locale_route($name, $parameters = [], $locale = null) {
                return \App\Helpers\LocaleHelper::route($name, $parameters, $locale);
            }
        }
        
        if (!function_exists('alternate_urls')) {
            function alternate_urls() {
                return \App\Helpers\LocaleHelper::getAlternateUrls();
            }
        }
    }
}
