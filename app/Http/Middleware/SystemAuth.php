<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SystemAuth
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
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            
            // Redirecionar para login específico do sistema
            return redirect()->route('system.login')
                ->with('error', 'Você precisa fazer login para acessar esta área.');
        }

        $user = Auth::user();

        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            Auth::logout();
            
            return redirect()->route('system.login')
                ->with('error', 'Sua conta está inativa. Contacte o administrador.');
        }

        // Verificar se é Super Admin
        if (!$user->isSuperAdmin()) {
            // Log da tentativa não autorizada
            $logger = app(\App\Services\ActivityLoggerService::class);
            $logger->log(
                'unauthorized_system_access',
                "Usuário {$user->name} ({$user->email}) tentou acessar área do sistema sem permissão",
                'security',
                'warning'
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso negado. Apenas Super Administradores podem acessar esta área.'
                ], 403);
            }

            // Fazer logout e redirecionar
            Auth::logout();
            
            return redirect()->route('system.login')
                ->with('error', 'Acesso negado. Apenas Super Administradores podem acessar esta área.');
        }

        // Atualizar timestamp do último login
        $user->updateLastLogin();
        return $next($request);
    }
}
