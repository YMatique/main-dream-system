<?php

namespace App\Http\Middleware;

// use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Se for uma rota do sistema (/system/*), redirecionar para system.login
        if ($request->routeIs('system.*')) {
            return route('system.login');
        }

        // Se for uma rota de admin (/admin/*), redirecionar para login padrão
        if ($request->routeIs('admin.*')) {
            return route('system.login');
        }

        // Se for uma rota da empresa (/company/*), redirecionar para login padrão
        if ($request->routeIs('company.*')) {
            return route('system.login');
        }

        // Para outras rotas, usar login padrão
        return route('system.login');
        // return $next($request);
    }
}
