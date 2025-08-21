<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Se tentar acessar system.login e já está logado como super admin
                if ($request->routeIs('system.login') && $user->isSuperAdmin()) {
                    return redirect()->route('system.dashboard');
                }
                
                // Se tentar acessar login normal e já está logado
                if ($request->routeIs('portal.login')) {
                    return $this->redirectBasedOnUserType($user);
                }
                
                // Se tentar acessar password.request ou password.reset
                if ($request->routeIs('password.*')) {
                    return $this->redirectBasedOnUserType($user);
                }
            }
        }
        return $next($request);
    }

    /**
     * Redirect user based on their type
     */
    protected function redirectBasedOnUserType($user): Response
    {
        // Check if user is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('system.login')->with('error', 'Sua conta está inativa.');
        }

        // Redirect based on user type
        return match($user->user_type) {
            'super_admin' => redirect()->route('system.dashboard'),
            'company_admin', 'company_user' => redirect()->route('admin.dashboard'),
            default => redirect()->route('dashboard') // fallback
        };
    }
}
