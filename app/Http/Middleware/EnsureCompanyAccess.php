<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('company.login');
        }

        $user = Auth::user();

        // Verificar se não é Super Admin (Super Admin deve usar área do sistema)
        if ($user->isSuperAdmin()) {
            return redirect()->route('system.dashboard')
                ->with('error', 'Use a área do sistema para administração.');
        }

        // Verificar se o usuário tem empresa associada
        if (!$user->company_id || !$user->company) {
            Auth::logout();
            return redirect()->route('company.login')
                ->with('error', 'Usuário não tem empresa associada.');
        }

        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('company.login')
                ->with('error', 'Sua conta está inativa. Contacte o administrador.');
        }

        // Verificar se a empresa está ativa
        if ($user->company->status !== 'active') {
            Auth::logout();
            return redirect()->route('company.login')
                ->with('error', 'Sua empresa está inativa. Contacte o suporte.');
        }

        // Verificar se a subscrição está ativa
        if (!$user->company->hasActiveSubscription()) {
            Auth::logout();
            return redirect()->route('company.login')
                ->with('error', 'A subscrição da sua empresa expirou.');
        }

        // Definir contexto da empresa na sessão
        session(['company_id' => $user->company_id]);

        return $next($request);
    }
}
