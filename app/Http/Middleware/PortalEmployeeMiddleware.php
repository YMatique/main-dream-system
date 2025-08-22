<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalEmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
    // Verificar se está logado no guard portal
        if (!Auth::guard('portal')->check()) {
            return redirect()->route('portal.login');
        }

        $user = Auth::guard('portal')->user();

        // Verificar se está ativo
        if (!$user->is_active) {
            Auth::guard('portal')->logout();
            return redirect()->route('portal.login')
                ->withErrors(['email' => 'Conta desativada.']);
        }

        // Verificar se o funcionário está ativo
        if (!$user->employee || !$user->employee->is_active) {
            Auth::guard('portal')->logout();
            return redirect()->route('portal.login')
                ->withErrors(['email' => 'Funcionário inativo.']);
        }

        return $next($request);
    }
}