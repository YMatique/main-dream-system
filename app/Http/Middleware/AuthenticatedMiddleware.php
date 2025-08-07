<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar se está autenticado
        if (!Auth::check()) {
            return $this->redirectToLogin($request);
        }

        $user = Auth::user();

        // 2. Verificar se usuário está ativo
        if ($user->status !== 'active') {
            Auth::logout();
            return $this->redirectToLogin($request, 'Sua conta está inativa.');
        }

        // 3. Verificações específicas por área
        if ($request->routeIs('system.*')) {
            if (!$user->isSuperAdmin()) {
                $this->logUnauthorizedAccess($user, $request);
                Auth::logout();
                return redirect()->route('system.login')
                    ->with('error', 'Acesso restrito para Super Administradores.');
            }
        }

        if ($request->routeIs('company.*') || $request->routeIs('admin.*')) {
            // Super Admin pode acessar área da empresa
            if (!$user->isSuperAdmin()) {
                // Verificar se tem empresa
                if (!$user->company_id || !$user->company) {
                    Auth::logout();
                    return redirect()->route('company.login')
                        ->with('error', 'Usuário não tem empresa associada.');
                }

                // Verificar se empresa está ativa
                if (!$user->company->isActive()) {
                    Auth::logout();
                    return redirect()->route('company.login')
                        ->with('error', 'Sua empresa está inativa.');
                }

                // Verificar subscrição
                if (!$user->company->hasActiveSubscription()) {
                    Auth::logout();
                    return redirect()->route('company.login')
                        ->with('error', 'Subscrição da empresa expirou.');
                }

                // Definir contexto da empresa
                session(['company_id' => $user->company_id]);
            }
        }

        // 4. Atualizar último login
        $user->updateLastLogin();
        return $next($request);
    }

     private function redirectToLogin(Request $request, string $message = null)
    {
        $loginRoute = match (true) {
            $request->routeIs('system.*') => 'system.login',
            $request->routeIs('company.*') => 'company.login',
            default => 'login'
        };

        $redirect = redirect()->route($loginRoute);
        
        if ($message) {
            $redirect->with('error', $message);
        }

        return $redirect;
    }

    private function logUnauthorizedAccess($user, $request)
    {
        if (class_exists('\App\Services\ActivityLoggerService')) {
            $logger = app(\App\Services\ActivityLoggerService::class);
            $logger->log(
                'unauthorized_system_access',
                "Usuário {$user->name} ({$user->email}) tentou acessar área do sistema sem permissão",
                'security',
                'warning'
            );
        }
    }
}
