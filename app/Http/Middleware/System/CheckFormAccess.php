<?php

namespace App\Http\Middleware\System;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFormAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $formNumber): Response
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
        $permission = "forms.form{$formNumber}.access";
        

        if ($user->user_type === 'company_user' && !$user->hasPermission($permission)) {
            return redirect()->route('company.my-permissions')
                ->with('error', "Sem acesso ao Formulário {$formNumber}. Contacte o administrador.");
            // return redirect()->route('company.dashboard')
            //     ->with('error', "Sem acesso ao Formulário {$formNumber}. Contacte o administrador.");
        }
        return $next($request);
    }
}
