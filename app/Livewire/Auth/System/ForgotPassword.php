<?php

namespace App\Livewire\Auth\System;

use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public bool $emailSent = false;

    protected $rules = [
        'email' => ['required', 'string', 'email', 'max:255'],
    ];

    protected $messages = [
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'email.max' => 'O email deve ter no máximo 255 caracteres.',
    ];

    #[Layout('layouts.auth')]
    #[Title('Recuperar Senha')]
    public function render()
    {
        return view('livewire.auth.system.forgot-password');
    }
     public function sendPasswordResetLink()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        // Verificar se o usuário existe
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
            // Por segurança, não revelar se o email existe ou não
            $this->emailSent = true;
            
            // Log da tentativa com email inexistente
            $logger = app(ActivityLoggerService::class);
            $logger->log(
                'password_reset_attempt_invalid_email',
                "Tentativa de recuperação de senha com email inexistente: {$this->email}",
                'security',
                'warning'
            );
            
            return;
        }

        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'Esta conta está inativa. Entre em contacto com o administrador.',
            ]);
        }

        // Enviar email de recuperação
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            RateLimiter::clear($this->throttleKey());
            $this->emailSent = true;

            // Log do envio bem-sucedido
            $logger = app(ActivityLoggerService::class);
            $logger->log(
                'password_reset_link_sent',
                "Link de recuperação de senha enviado para {$user->name} ({$this->email})",
                'auth',
                'info'
            );
        } else {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'Não foi possível enviar o link de recuperação. Tente novamente.',
            ]);
        }
    }

    public function goBack()
    {
        // Método mais simples: verificar o tipo do usuário pelo email
        if (!empty($this->email)) {
            $user = \App\Models\User::where('email', $this->email)->first();
            
            if ($user && $user->isSuperAdmin()) {
                return $this->redirect(route('system.login'), navigate: true);
            }
            
            if ($user && in_array($user->user_type, ['company_admin', 'company_user'])) {
                return $this->redirect(route('company.login'), navigate: true);
            }
        }

        // Fallback: se não conseguir determinar, vai para login do sistema
        return $this->redirect(route('system.login'), navigate: true);
    }

    public function goToSystemLogin()
    {
        return $this->redirect(route('system.login'), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Muitas tentativas de recuperação. Tente novamente em " . ceil($seconds / 60) . " minutos.",
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate('password-reset|' . request()->ip());
    }
}
