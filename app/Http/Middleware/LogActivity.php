<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ActivityLoggerService;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    protected $logger;
    
    // Rotas que devem ser ignoradas nos logs automáticos
    protected $skipRoutes = [
        'livewire.message',
        'livewire.upload-file',
        'livewire.preview-file',
        'debugbar.*',
        '_ignition.*'
    ];

    // Rotas que merecem logs especiais
    protected $specialRoutes = [
        'login' => 'auth',
        'logout' => 'auth',
        'password.*' => 'auth',
        'system.*' => 'system',
        'company.*' => 'company'
    ];

    public function __construct(ActivityLoggerService $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $response = $next($request);

        // Só fazer log se o usuário estiver autenticado
        if (auth()->check()) {
            $this->logRequest($request, $response);
        }
        return $response;
        // return $next($request);
    }
      protected function logRequest(Request $request, Response $response): void
    {
        $route = $request->route();
        $routeName = $route?->getName() ?? $request->path();

        // Ignorar certas rotas
        if ($this->shouldSkipRoute($routeName)) {
            return;
        }

        // Logs específicos baseados na rota
        if ($this->isSpecialRoute($routeName)) {
            $this->logSpecialRoute($request, $routeName);
            return;
        }

        // Log geral de navegação (apenas para rotas importantes)
        if ($this->isImportantRoute($request, $routeName)) {
            $this->logNavigation($request, $routeName);
        }
    }

    protected function shouldSkipRoute(string $routeName): bool
    {
        foreach ($this->skipRoutes as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $routeName)) {
                    return true;
                }
            } elseif ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }

    protected function isSpecialRoute(string $routeName): bool
    {
        foreach ($this->specialRoutes as $pattern => $category) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $routeName)) {
                    return true;
                }
            } elseif ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }

    protected function logSpecialRoute(Request $request, string $routeName): void
    {
        $category = $this->getRouteCategory($routeName);
        
        switch ($category) {
            case 'auth':
                $this->logAuthRoute($request, $routeName);
                break;
            case 'system':
                $this->logSystemRoute($request, $routeName);
                break;
            case 'company':
                $this->logCompanyRoute($request, $routeName);
                break;
        }
    }

    protected function getRouteCategory(string $routeName): string
    {
        foreach ($this->specialRoutes as $pattern => $category) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $routeName)) {
                    return $category;
                }
            } elseif ($routeName === $pattern) {
                return $category;
            }
        }

        return 'general';
    }

    protected function logAuthRoute(Request $request, string $routeName): void
    {
        // Logs de autenticação são tratados pelos respectivos controllers/livewire
        // Este middleware apenas captura navegação entre páginas de auth
        
        $actions = [
            'login' => 'Acessou página de login',
            'password.request' => 'Solicitou recuperação de senha',
            'password.reset' => 'Acessou página de redefinição de senha',
        ];

        $description = $actions[$routeName] ?? "Acessou página de autenticação: {$routeName}";

        $this->logger->log('page_access', $description, 'auth', 'info');
    }

    protected function logSystemRoute(Request $request, string $routeName): void
    {
        // Apenas super admins podem acessar rotas system
        $this->logger->log(
            'system_access',
            "Acessou área do sistema: {$routeName}",
            'system',
            'info'
        );
    }

    protected function logCompanyRoute(Request $request, string $routeName): void
    {
        $this->logger->log(
            'company_access',
            "Acessou área da empresa: {$routeName}",
            'company',
            'info'
        );
    }

    protected function isImportantRoute(Request $request, string $routeName): bool
    {
        // Considerar importante se:
        // 1. É uma rota nomeada (não asset/api)
        // 2. É um GET (navegação)
        // 3. Não é AJAX/Livewire
        
        if ($request->method() !== 'GET') {
            return false;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Rotas importantes
        $importantPatterns = [
            'dashboard',
            'system.*',
            'company.*',
            'settings.*',
            '*.index',
            '*.show',
            '*.create',
            '*.edit'
        ];

        foreach ($importantPatterns as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $routeName)) {
                    return true;
                }
            } elseif ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }

    protected function logNavigation(Request $request, string $routeName): void
    {
        $this->logger->log(
            'page_visit',
            "Visitou página: {$routeName}",
            'system',
            'info',
            [
                'route' => $routeName,
                'url' => $request->fullUrl(),
                'referrer' => $request->header('referer')
            ]
        );
    }
}
