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
}
