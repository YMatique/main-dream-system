<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFormPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $formNumber, string $type = 'access'): Response
    {
         if (!Auth::check()) {
            return redirect()->route('company.login');
        }

        $user = Auth::user();

        // Super Admin e Company Admin têm acesso a todos os formulários
        if (in_array($user->user_type, ['super_admin', 'company_admin'])) {
            return $next($request);
        }

        // Verificar permissão específica do formulário
        $permission = "forms.form{$formNumber}.{$type}";
        
        if ($user->user_type === 'company_user' && !$user->hasPermission($permission)) {
            $actionText = $type === 'list' ? 'visualizar' : 'acessar';
            
            return redirect()->route('company.my-permissions')
                ->with('error', "Sem permissão para {$actionText} o Formulário {$formNumber}. Contacte o administrador.");
        }
        return $next($request);
    }
}
