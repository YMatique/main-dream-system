<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedTypes): Response
    {
        if (!Auth::check()) {
            abort(401, 'Não autenticado.');
        }

        $user = Auth::user();

        // Verificar se o tipo do usuário está nas permitidas
        if (!in_array($user->user_type, $allowedTypes)) {
            $this->logPermissionDenied($user, $request, $allowedTypes);
            return $this->redirectToUserArea($user, 'Acesso negado para seu tipo de usuário.');
        }
        return $next($request);
    }
      private function redirectToUserArea($user, string $message)
    {
        $route = match ($user->user_type) {
            'super_admin' => 'system.dashboard',
            'company_admin', 'company_user' => 'company.dashboard',
            default => 'home'
        };

        return redirect()->route($route)->with('error', $message);
    }

    private function logPermissionDenied($user, $request, $allowedTypes)
    {
        if (class_exists('\App\Services\ActivityLoggerService')) {
            $logger = app(\App\Services\ActivityLoggerService::class);
            $logger->log(
                'permission_denied',
                "Usuário {$user->name} ({$user->user_type}) tentou acessar área restrita. Tipos permitidos: " . implode(', ', $allowedTypes),
                'security',
                'warning'
            );
        }
    }
}
