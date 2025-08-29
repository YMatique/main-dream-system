<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEvaluationAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $name): Response
    {
                if (!Auth::check()) {
            return redirect()->route('company.login');
        }

        $user = Auth::user();

        // Super Admin e Company Admin têm acesso a todos os formulários
        if (in_array($user->user_type, ['super_admin', 'company_admin'])) {
            return $next($request);
        }

        // Verificar permissão do formulário para company_user
        $permission = "evaluation.{$name}";
        
        if ($user->user_type === 'company_user' && !$user->hasPermission($permission)) {
            return redirect()->route('company.my-permissions')
                ->with('error', "Sem acesso a Gestão de Avaliação - {$name}. Contacte o administrador.");
        }
        return $next($request);
    }
}
