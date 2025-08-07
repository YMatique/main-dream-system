<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*
    public function handle(Request $request, Closure $next,  ...$types): Response
    {
         if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sua conta está inativa. Entre em contato com o administrador.');
        }

        // Check if user type is allowed
        if (!in_array($user->user_type, $types)) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        // Update last login
        $user->updateLastLogin();
        return $next($request);
    }
    */




     /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (!Auth::check()) {
            return $this->redirectToAppropriateLogin($request);
        }

        $user = Auth::user();

        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return $this->redirectToAppropriateLogin($request, 'Sua conta está inativa. Entre em contacto com o administrador.');
        }

        // Check if user type is allowed
        if (!in_array($user->user_type, $types)) {
            // Log unauthorized access attempt
            $logger = app(\App\Services\ActivityLoggerService::class);
            $logger->log(
                'unauthorized_access_attempt',
                "Usuário {$user->name} ({$user->user_type}) tentou acessar área restrita: " . $request->route()->getName(),
                'security',
                'warning'
            );

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Você não tem permissão para acessar esta área.'], 403);
            }

            // Redirect to appropriate area based on user type
            return $this->redirectToUserArea($user, 'Você não tem permissão para acessar esta área.');
        }

        // Update last login
        $user->updateLastLogin();
        
        return $next($request);
    }
    
    /**
     * Redirect to appropriate login based on request
     */
    protected function redirectToAppropriateLogin(Request $request, string $message = null): Response
    {
        $loginRoute = 'system.login'; // default

        // If accessing system routes, redirect to system login
        if ($request->routeIs('system.*')) {
            $loginRoute = 'system.login';
        }

        $redirect = redirect()->route($loginRoute);
        
        if ($message) {
            $redirect->with('error', $message);
        }

        return $redirect;
    }

    /**
     * Redirect to user's appropriate area
     */
    protected function redirectToUserArea($user, string $message = null): Response
    {
        $route = match($user->user_type) {
            'super_admin' => 'system.dashboard',
            'company_admin', 'company_user' => 'admin.dashboard',
            default => 'dashboard'
        };

        $redirect = redirect()->route($route);
        
        if ($message) {
            $redirect->with('error', $message);
        }

        return $redirect;
    }
}
