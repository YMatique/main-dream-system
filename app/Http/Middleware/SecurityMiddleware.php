<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar rate limiting
        $this->checkRateLimit($request);
        
        // Verificar restrições de IP
        $this->checkIpRestrictions($request);
        
        // Verificar sessão expirada
        $this->checkSessionTimeout($request);
        
        // Verificar força troca de senha
        $this->checkPasswordExpiry($request);
        
        // Headers de segurança
        $response = $next($request);
        $this->addSecurityHeaders($response);
        return $next($request);
    }
     private function checkRateLimit(Request $request): void
    {
        $key = 'security_check:' . $request->ip();
        $maxAttempts = 100; // Ajuste conforme necessário
        $decayMinutes = 1;
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            abort(429, 'Muitas requisições. Tente novamente em alguns minutos.');
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
    }
    
    private function checkIpRestrictions(Request $request): void
    {
        if (!config('security.ip_restrictions.enabled')) {
            return;
        }
        
        $user = auth()->user();
        if (!$user) {
            return;
        }
        
        // Verificar IPs para super admins
        if ($user->user_type === 'super_admin') {
            $allowedIps = config('security.ip_restrictions.admin_ips');
            if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
                auth()->logout();
                abort(403, 'Acesso negado: IP não autorizado para administradores.');
            }
        }
        
        // Verificar restrições da empresa
        if ($user->company && config('security.ip_restrictions.company_ip_restrictions')) {
            $companyIps = $user->company->allowed_ips ?? [];
            if (!empty($companyIps) && !in_array($request->ip(), $companyIps)) {
                abort(403, 'Acesso negado: IP não autorizado para sua empresa.');
            }
        }
    }
    
    private function checkSessionTimeout(Request $request): void
    {
        if (!auth()->check()) {
            return;
        }
        
        $timeoutMinutes = config('security.session.idle_timeout_minutes');
        $lastActivity = session('last_activity', time());
        
        if (time() - $lastActivity > $timeoutMinutes * 60) {
            auth()->logout();
            session()->flush();
            abort(419, 'Sessão expirada por inatividade.');
        }
        
        session(['last_activity' => time()]);
    }
    
    private function checkPasswordExpiry(Request $request): void
    {
        if (!auth()->check() || $request->is('password/*') || $request->is('logout')) {
            return;
        }
        
        $user = auth()->user();
        $maxAgeDays = config('security.password.max_age_days');
        
        if ($user->password_reset_required) {
            if (!$request->is('settings/password')) {
                return redirect()->route('settings.password')
                    ->with('warning', 'Você deve alterar sua senha antes de continuar.');
            }
        }
        
        if ($maxAgeDays > 0 && $user->password_updated_at) {
            $passwordAge = Carbon::parse($user->password_updated_at)->diffInDays();
            if ($passwordAge >= $maxAgeDays) {
                if (!$request->is('settings/password')) {
                    return redirect()->route('settings.password')
                        ->with('warning', "Sua senha expirou há {$passwordAge} dias. Por favor, altere sua senha.");
                }
            }
        }
    }
    
    private function addSecurityHeaders(Response $response): void
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
    }
}
