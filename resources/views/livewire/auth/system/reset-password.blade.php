{{-- resources/views/livewire/auth/reset-password.blade.php --}}

<div class="max-w-md w-full space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="text-center">
        <div class="mx-auto h-20 w-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl flex items-center justify-center shadow-2xl glow">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h2 class="mt-6 text-4xl font-bold text-white tracking-tight">Nova Senha</h2>
        <p class="mt-2 text-sm text-slate-300">Defina uma senha segura para sua conta</p>
        <p class="text-xs text-slate-400 mt-1">Quase pronto! Apenas mais um passo.</p>
    </div>

    <!-- Form Card -->
    <div class="glass-effect rounded-3xl shadow-2xl p-8 border border-white/20">
        <!-- Error Messages -->
        @if (session()->has('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-100 px-4 py-3 rounded-xl backdrop-blur-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form wire:submit="resetPassword" class="space-y-6">
            <!-- Email (readonly) -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input wire:model="email" 
                           type="email" 
                           readonly
                           class="block w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-xl text-slate-300 cursor-not-allowed backdrop-blur-sm">
                </div>
                @error('email') 
                    <p class="mt-2 text-sm text-red-300 flex items-center animate-fade-in">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Nova Senha</label>
                <div class="relative" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input wire:model="password" 
                           :type="showPassword ? 'text' : 'password'"
                           placeholder="Digite sua nova senha"
                           class="block w-full pl-12 pr-14 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm focus-ring"
                           required>
                    <button type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L18 18"></path>
                        </svg>
                    </button>
                </div>
                @error('password') 
                    <p class="mt-2 text-sm text-red-300 flex items-center animate-fade-in">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Confirmar Nova Senha</label>
                <div class="relative" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <input wire:model="password_confirmation" 
                           :type="showPassword ? 'text' : 'password'"
                           placeholder="Confirme sua nova senha"
                           class="block w-full pl-12 pr-14 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm focus-ring"
                           required>
                    <button type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <svg x-show="!showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="h-5 w-5 text-slate-400 hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L18 18"></path>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation') 
                    <p class="mt-2 text-sm text-red-300 flex items-center animate-fade-in">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password Strength Indicator -->
            <div class="space-y-2" x-data="{ 
                password: @entangle('password'),
                get strength() {
                    let score = 0;
                    if (this.password.length >= 8) score++;
                    if (/[a-z]/.test(this.password)) score++;
                    if (/[A-Z]/.test(this.password)) score++;
                    if (/[0-9]/.test(this.password)) score++;
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;
                    return score;
                },
                get strengthText() {
                    const texts = ['Muito Fraca', 'Fraca', 'Regular', 'Boa', 'Forte', 'Muito Forte'];
                    return texts[this.strength] || 'Muito Fraca';
                },
                get strengthColor() {
                    const colors = ['bg-red-500', 'bg-red-400', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-green-600'];
                    return colors[this.strength] || 'bg-red-500';
                }
            }" x-show="password.length > 0">
                <label class="block text-sm font-medium text-white">Força da Senha</label>
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-300" 
                         :class="strengthColor" 
                         :style="`width: ${(strength/5) * 100}%`"></div>
                </div>
                <p class="text-xs" :class="{
                    'text-red-300': strength <= 1,
                    'text-yellow-300': strength === 2,
                    'text-blue-300': strength === 3,
                    'text-green-300': strength >= 4
                }" x-text="strengthText"></p>
            </div>

            <!-- Password Requirements -->
            <div class="bg-white/10 rounded-xl p-4 border border-white/20" x-data="{ password: @entangle('password') }">
                <h4 class="text-sm font-medium text-white mb-3">Requisitos da Senha:</h4>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center" :class="password.length >= 8 ? 'text-green-300' : 'text-slate-400'">
                        <svg class="w-4 h-4 mr-2" :class="password.length >= 8 ? 'text-green-400' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Pelo menos 8 caracteres
                    </div>
                    <div class="flex items-center" :class="/[a-z]/.test(password) ? 'text-green-300' : 'text-slate-400'">
                        <svg class="w-4 h-4 mr-2" :class="/[a-z]/.test(password) ? 'text-green-400' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Uma letra minúscula
                    </div>
                    <div class="flex items-center" :class="/[A-Z]/.test(password) ? 'text-green-300' : 'text-slate-400'">
                        <svg class="w-4 h-4 mr-2" :class="/[A-Z]/.test(password) ? 'text-green-400' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Uma letra maiúscula
                    </div>
                    <div class="flex items-center" :class="/[0-9]/.test(password) ? 'text-green-300' : 'text-slate-400'">
                        <svg class="w-4 h-4 mr-2" :class="/[0-9]/.test(password) ? 'text-green-400' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Um número
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center px-4 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 border border-transparent rounded-xl font-semibold text-white hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 btn-hover shadow-lg">
                <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Redefinir Senha
                </span>
                <span wire:loading>Redefinindo...</span>
            </button>
        </form>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-blue-500/20 border border-blue-500/50 rounded-xl backdrop-blur-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-300 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-200">Dica de Segurança</h4>
                    <p class="text-xs text-blue-300 mt-1">
                        Use uma combinação única de letras, números e símbolos. 
                        Evite informações pessoais como nome, data de nascimento ou palavras comuns.
                    </p>
                </div>
            </div>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <button wire:click="goBack" 
                    class="inline-flex items-center text-sm text-slate-300 hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar ao Login
            </button>
        </div>
    </div>
</div>