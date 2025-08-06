<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class SecurityService
{
    
    public function validatePasswordSecurity(string $password, ?User $user = null): array
    {
        $errors = [];
        $config = config('security.password');
        
        // Verificar reutilização de senhas
        if ($user && $config['prevent_reuse'] > 0) {
            $previousPasswords = $user->password_history ?? [];
            $recentPasswords = array_slice($previousPasswords, -$config['prevent_reuse']);
            
            foreach ($recentPasswords as $previousPassword) {
                if (Hash::check($password, $previousPassword)) {
                    $errors[] = "Não é possível reutilizar uma das últimas {$config['prevent_reuse']} senhas.";
                    break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Salvar senha no histórico
     */
    public function savePasswordHistory(User $user, string $hashedPassword): void
    {
        $maxHistory = config('security.password.prevent_reuse', 5);
        if ($maxHistory <= 0) {
            return;
        }
        
        $history = $user->password_history ?? [];
        $history[] = $hashedPassword;
        
        // Manter apenas as últimas senhas
        if (count($history) > $maxHistory) {
            $history = array_slice($history, -$maxHistory);
        }
        
        $user->update([
            'password_history' => $history,
            'password_updated_at' => now(),
            'password_reset_required' => false,
        ]);
    }
    
    /**
     * Verificar tentativas de login
     */
    public function checkLoginAttempts(string $email): bool
    {
        $key = 'login_attempts:' . $email;
        $attempts = Cache::get($key, 0);
        $maxAttempts = config('security.session.max_login_attempts');
        
        return $attempts < $maxAttempts;
    }
    
    /**
     * Registrar tentativa de login
     */
    public function recordLoginAttempt(string $email, bool $successful = false): void
    {
        $key = 'login_attempts:' . $email;
        $lockoutDuration = config('security.session.lockout_duration_minutes') * 60;
        
        if ($successful) {
            Cache::forget($key);
        } else {
            $attempts = Cache::get($key, 0) + 1;
            Cache::put($key, $attempts, $lockoutDuration);
        }
    }
    
    /**
     * Verificar se o usuário está bloqueado
     */
    public function isUserLocked(string $email): bool
    {
        return !$this->checkLoginAttempts($email);
    }
    
    /**
     * Obter tempo restante de bloqueio
     */
    public function getLockoutTimeRemaining(string $email): int
    {
        $key = 'login_attempts:' . $email;
        $ttl = Cache::store()->getRedis()->ttl(Cache::store()->getPrefix() . $key);
        
        return max(0, $ttl);
    }
    
    /**
     * Limpar tentativas de login
     */
    public function clearLoginAttempts(string $email): void
    {
        Cache::forget('login_attempts:' . $email);
    }
    
    /**
     * Verificar sessões simultâneas
     */
    public function checkConcurrentSessions(User $user): bool
    {
        $maxSessions = config('security.session.max_concurrent_sessions');
        if ($maxSessions <= 0) {
            return true;
        }
        
        $activeSessions = Cache::get("user_sessions:{$user->id}", []);
        
        return count($activeSessions) < $maxSessions;
    }
    
    /**
     * Registrar nova sessão
     */
    public function registerSession(User $user, string $sessionId): void
    {
        $maxSessions = config('security.session.max_concurrent_sessions');
        if ($maxSessions <= 0) {
            return;
        }
        
        $key = "user_sessions:{$user->id}";
        $sessions = Cache::get($key, []);
        
        // Adicionar nova sessão
        $sessions[$sessionId] = now()->toISOString();
        
        // Remover sessões antigas se exceder o limite
        if (count($sessions) > $maxSessions) {
            // Manter apenas as mais recentes
            $sessions = array_slice($sessions, -$maxSessions, null, true);
        }
        
        Cache::put($key, $sessions, 86400); // 24 horas
    }
    
    /**
     * Remover sessão
     */
    public function unregisterSession(User $user, string $sessionId): void
    {
        $key = "user_sessions:{$user->id}";
        $sessions = Cache::get($key, []);
        
        unset($sessions[$sessionId]);
        
        if (empty($sessions)) {
            Cache::forget($key);
        } else {
            Cache::put($key, $sessions, 86400);
        }
    }
    
    /**
     * Verificar se a conta precisa de verificação adicional
     */
    public function requiresAdditionalVerification(User $user, string $action): bool
    {
        $sensitiveActions = [
            'delete_account',
            'change_password',
            'export_data',
            'change_permissions',
            'delete_company',
        ];
        
        if (!in_array($action, $sensitiveActions)) {
            return false;
        }
        
        // Verificar se já foi verificado recentemente
        $verificationKey = "verification:{$user->id}:{$action}";
        
        return !Cache::has($verificationKey);
    }
    
    /**
     * Marcar ação como verificada
     */
    public function markActionVerified(User $user, string $action): void
    {
        $verificationKey = "verification:{$user->id}:{$action}";
        Cache::put($verificationKey, true, 300); // 5 minutos
    }
}
