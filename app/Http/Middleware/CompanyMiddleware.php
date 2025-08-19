<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          $user = auth()->user();
        
        // Super admins podem acessar tudo
        if ($user && $user->user_type === 'super_admin') {
            return $next($request);
        }
        
        // Verificar se o usuário tem empresa
        if (!$user || !$user->company_id) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado: usuário não está vinculado a uma empresa.');
        }
        
        // Verificar se a empresa tem subscrição ativa
        if (!$user->hasActiveSubscription()) {
            return redirect()->route('dashboard')->with('warning', 'Sua empresa não possui subscrição ativa. Entre em contato com o administrador.');
        }
        
        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('system.login')->with('error', 'Sua conta foi desativada. Entre em contato com o administrador.');
        }
        return $next($request);
    }
}
