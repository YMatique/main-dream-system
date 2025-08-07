<?php

namespace App\Livewire\Company;
use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Livewire\Component;

class CompanyLogin extends Component
{
     public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];

    protected $messages = [
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'password.required' => 'A senha é obrigatória.',
    ];

    #[Layout('layouts.auth')]
    #[Title('Login da Empresa')]
    public function render()
    {
        return view('livewire.company.company-login');
    }
    public function login()
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            
            // Log failed attempt
            $logger = app(ActivityLoggerService::class);
            $logger->logFailedLogin($this->email);
            
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas. Verifique seu email e senha.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        $user = Auth::user();

        // Verificar se o usuário está ativo
        if ($user->status !== 'active') {
            Auth::logout();
            session()->flash('error', 'Sua conta está inativa. Contacte o administrador da empresa.');
            return;
        }

        // Verificar se é usuário de empresa (não Super Admin)
        if ($user->isSuperAdmin()) {
            Auth::logout();
            session()->flash('error', 'Use o login do sistema para acessar a área administrativa.');
            return $this->redirect(route('system.login'), navigate: true);
        }

        // Verificar se tem empresa associada
        if (!$user->company_id || !$user->company) {
            Auth::logout();
            
            $logger = app(ActivityLoggerService::class);
            $logger->log(
                'company_access_without_company',
                "Usuário {$user->name} ({$user->email}) tentou acessar sem empresa associada",
                'security',
                'warning'
            );
            
            throw ValidationException::withMessages([
                'email' => 'Usuário não tem empresa associada. Contacte o administrador.',
            ]);
        }

        // Verificar se a empresa está ativa
        if ($user->company->status !== 'active') {
            Auth::logout();
            session()->flash('error', 'Sua empresa está inativa. Contacte o suporte.');
            return;
        }

        // Verificar se a subscrição está ativa
        if (!$user->company->hasActiveSubscription()) {
            Auth::logout();
            session()->flash('error', 'A subscrição da sua empresa expirou. Contacte o administrador.');
            return;
        }

        // Password reset required?
        if ($user->password_reset_required) {
            return $this->redirect(route('company.password.reset.required'), navigate: true);
        }

        // Log successful company login
        $logger = app(ActivityLoggerService::class);
        $logger->log(
            'company_login',
            "Usuário {$user->name} fez login na empresa {$user->company->name}",
            'auth',
            'info',
            ['company_id' => $user->company_id]
        );

        // Set company context in session
        session(['company_id' => $user->company_id]);
        
        $welcomeMessage = $user->user_type === 'company_admin' 
            ? "Bem-vindo, {$user->name}! (Administrador)"
            : "Bem-vindo, {$user->name}!";
            
        session()->flash('message', $welcomeMessage);

        return $this->redirect(route('company.dashboard'), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());
        
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
