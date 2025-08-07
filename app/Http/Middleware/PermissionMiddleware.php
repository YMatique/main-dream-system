<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('system.login');
        }
        
        // Super admins têm todas as permissões
        if ($user->user_type === 'super_admin') {
            return $next($request);
        }
        
        // Verificar se o usuário tem a permissão específica
        $userPermissions = $user->permissions ?? [];
        
        // Admin da empresa tem todas as permissões da empresa
        if ($user->user_type === 'company_admin') {
            $companyPermissions = [
                'repair_orders.create', 'repair_orders.edit', 'repair_orders.delete', 'repair_orders.view',
                'employees.manage', 'clients.manage', 'materials.manage', 'departments.manage',
                'billing.view', 'billing.manage', 'performance.view', 'performance.manage',
                'reports.view', 'reports.export', 'settings.manage'
            ];
            $userPermissions = array_merge($userPermissions, $companyPermissions);
        }
        
        if (!in_array($permission, $userPermissions) && !in_array('*', $userPermissions)) {
            abort(403, 'Acesso negado: você não tem permissão para acessar esta funcionalidade.');
        }
        return $next($request);
    }
}
