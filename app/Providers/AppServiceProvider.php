<?php

namespace App\Providers;

use App\Services\AuditService;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auth::login(function ($user) {
        //     app(AuditService::class)->logLogin($user);
        // });
    }
}
