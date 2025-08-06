<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Assumindo que o super admin tem um campo role = 'super_admin' ou is_super_admin = true
        $user = auth()->user();
        
        // Check if user status is active
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Sua conta está inativa.');
        }

           $isSuperAdmin = $user->is_super_admin 
                       || $user->user_type === 'super_admin'
                       || $user->isSuperAdmin();

        if (!$isSuperAdmin) {
            abort(403, 'Acesso negado. Apenas super administradores podem aceder esta área.');
        }

          // Update last login
        if (method_exists($user, 'updateLastLogin')) {
            $user->updateLastLogin();
        }

        // if (!$user->is_super_admin) {
        //     abort(403, 'Acesso negado. Apenas super administradores podem aceder esta área.');
        // }
        return $next($request);
    }
}
