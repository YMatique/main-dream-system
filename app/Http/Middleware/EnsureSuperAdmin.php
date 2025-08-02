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
        
        if (!$user->is_super_admin) {
            abort(403, 'Acesso negado. Apenas super administradores podem aceder esta área.');
        }
        return $next($request);
    }
}
