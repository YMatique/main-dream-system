<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
     public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Só auditar se o usuário estiver autenticado
        if (!auth()->check()) {
            return $response;
        }
        
        $user = auth()->user();
        $method = $request->method();
        $path = $request->path();
        
        // Auditar ações específicas baseadas na rota
        $this->auditRouteAction($request, $user, $method, $path);
        
        
        return $next($request);
    }
    private function auditRouteAction(Request $request, $user, string $method, string $path): void
    {
        // Login/Logout são tratados em AuthServiceProvider
        
        // Auditar ações de sistema (apenas super admins)
        if ($user->user_type === 'super_admin' && str_starts_with($path, 'system/')) {
            $this->auditSystemAction($request, $method, $path);
        }
        
        // Auditar ações de empresa
        if (str_starts_with($path, 'company/') || str_starts_with($path, 'dashboard')) {
            $this->auditCompanyAction($request, $method, $path);
        }
        
        // Auditar acessos a relatórios e exportações
        if (str_contains($path, 'export') || str_contains($path, 'report')) {
            $this->auditDataAccess($request, $method, $path);
        }
    }
     private function auditSystemAction(Request $request, string $method, string $path): void
    {
        $actions = [
            'GET system/companies' => 'viewed_companies_list',
            'GET system/users' => 'viewed_users_list', 
            'GET system/plans' => 'viewed_plans_list',
            'GET system/subscriptions' => 'viewed_subscriptions_list',
            'GET system/dashboard' => 'viewed_system_dashboard',
        ];
        
        $routeKey = "{$method} {$path}";
        
        if (isset($actions[$routeKey])) {
            $this->auditService->logSystemAction($actions[$routeKey], [
                'path' => $path,
                'method' => $method,
                'query_params' => $request->query(),
            ]);
        }
    }
     private function auditCompanyAction(Request $request, string $method, string $path): void
    {
        $actions = [
            'GET dashboard' => 'viewed_company_dashboard',
            'GET company/repair-orders' => 'viewed_repair_orders',
            'GET company/employees' => 'viewed_employees',
            'GET company/billing' => 'viewed_billing',
            'GET company/reports' => 'viewed_reports',
            'GET company/performance' => 'viewed_performance',
        ];
        
        $routeKey = "{$method} {$path}";
        
        if (isset($actions[$routeKey])) {
            $this->auditService->logSystemAction($actions[$routeKey], [
                'path' => $path,
                'method' => $method,
                'company_id' => auth()->user()->company_id,
                'query_params' => $request->query(),
            ]);
        }
    }
     private function auditDataAccess(Request $request, string $method, string $path): void
    {
        $exportTypes = [
            'export/users' => 'exported_users_data',
            'export/companies' => 'exported_companies_data',
            'export/subscriptions' => 'exported_subscriptions_data',
            'export/repair-orders' => 'exported_repair_orders_data',
            'export/billing' => 'exported_billing_data',
            'export/performance' => 'exported_performance_data',
            'report/audit' => 'accessed_audit_report',
        ];
        
        foreach ($exportTypes as $pattern => $action) {
            if (str_contains($path, $pattern)) {
                $this->auditService->logSystemAction($action, [
                    'path' => $path,
                    'method' => $method,
                    'exported_at' => now()->toISOString(),
                    'filters' => $request->all(),
                ]);
                break;
            }
        }
    }
}
