<?php

namespace App\Livewire\Auth\System;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ResetPassword extends Component
{
     public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'token' => ['required'],
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'password_confirmation' => ['required', 'string', 'min:8'],
    ];

    protected $messages = [
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'password.required' => 'A nova senha é obrigatória.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'A confirmação da senha não confere.',
        'password_confirmation.required' => 'Confirme sua nova senha.',
    ];

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->get('email', '');
    }

    #[Layout('layouts.auth')]
    #[Title('Redefinir Senha')]
    public function render()
    {
        return view('livewire.auth.system.reset-password');
    }

     public function resetPassword()
    {
        $this->validate();

        // Verificar se o usuário existe
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Não foi possível encontrar uma conta com este email.',
            ]);
        }

        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'Esta conta está inativa. Entre em contacto com o administrador.',
            ]);
        }

        // Reset da senha
        $status = Password::reset(
            $this->only(['email', 'password', 'password_confirmation', 'token']),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                    'password_reset_required' => false, // Remove flag de reset obrigatório
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => match ($status) {
                    Password::INVALID_TOKEN => 'O link de recuperação é inválido ou expirou. Solicite um novo link.',
                    Password::INVALID_USER => 'Não foi possível encontrar uma conta com este email.',
                    default => 'Não foi possível redefinir a senha. Tente novamente.',
                }
            ]);
        }

        // Log da redefinição de senha bem-sucedida
        $logger = app(ActivityLoggerService::class);
        $logger->log(
            'password_reset_success',
            "Senha redefinida com sucesso para {$user->name} ({$this->email})",
            'auth',
            'info'
        );

        // Fazer login automático após reset
        Auth::login($user);

        session()->flash('message', 'Senha redefinida com sucesso! Você foi conectado automaticamente.');

        // Redirecionar baseado no tipo de usuário
        return $this->redirect($this->getRedirectRoute($user), navigate: true);
    }

    protected function getRedirectRoute($user): string
    {
        return match($user->user_type) {
            'super_admin' => route('system.dashboard'),
            'company_admin', 'company_user' => route('admin.dashboard'),
            default => route('dashboard')
        };
    }

    public function goBack()
    {
        return $this->redirect(route('login'), navigate: true);
    }
}
