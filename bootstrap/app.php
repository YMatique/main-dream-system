<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckUserType;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'user.type' => CheckUserType::class,
            'permission' => CheckPermission::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
            'company' => \App\Http\Middleware\CompanyMiddleware::class,
            'security'=> \App\Http\Middleware\SecurityMiddleware::class,
            // 'permission' => \App\Http\Middleware\PermissionMiddleware::class, verificar o alias do middleware
        ]);
        $middleware->append( \App\Http\Middleware\LogActivity::class, );

        /**
         *    Middleware global (opcional)
        $middleware->append(CheckUserType::class);
        
     Middleware de grupo (opcional)
        $middleware->group('system', [
            'auth',
            'user.type:super_admin'
        ]);

        $middleware->group('company', [
            'auth',
            'user.type:super_admin,company_admin'
        ]);

        $middleware->group('app', [
            'auth',
            'user.type:super_admin,company_admin,company_user'
        ]);
         */
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
