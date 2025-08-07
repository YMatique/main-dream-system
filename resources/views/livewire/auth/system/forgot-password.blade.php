{{-- resources/views/livewire/auth/forgot-password.blade.php --}}

<div class="max-w-md w-full space-y-8 animate-fade-in">
    @if(!$emailSent)
        <!-- Form de Recuperação -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl flex items-center justify-center shadow-2xl glow">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-4xl font-bold text-white tracking-tight">Recuperar Senha</h2>
            <p class="mt-2 text-sm text-slate-300">Esqueceu sua senha? Sem problemas!</p>
            <p class="text-xs text-slate-400 mt-1">Digite seu email e enviaremos um link para redefinir</p>
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

            <form wire:submit="sendPasswordResetLink" class="space-y-6">
                <!-- Email -->
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
                               autocomplete="email"
                               placeholder="Digite seu email"
                               class="block w-full pl-12 pr-4 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm focus-ring"
                               required>
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

                <!-- Submit Button -->
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center px-4 py-4 bg-gradient-to-r from-amber-600 to-orange-600 border border-transparent rounded-xl font-semibold text-white hover:from-amber-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 btn-hover shadow-lg">
                    <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Link de Recuperação
                    </span>
                    <span wire:loading>Enviando...</span>
                </button>
            </form>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-500/20 border border-blue-500/50 rounded-xl backdrop-blur-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-300 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-200">Como funciona?</h4>
                        <p class="text-xs text-blue-300 mt-1">
                            1. Digite seu email registado<br>
                            2. Verifique sua caixa de entrada<br>
                            3. Clique no link recebido<br>
                            4. Defina uma nova senha
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center space-y-3">
                <button wire:click="goBack" 
                        class="inline-flex items-center text-sm text-slate-300 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar ao Login
                </button>
                
                <div class="text-xs text-slate-500">ou</div>
                
                <button wire:click="goToSystemLogin" 
                        class="inline-flex items-center text-sm text-amber-300 hover:text-amber-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Acesso de Administrador
                </button>
            </div>
        </div>

    @else
        <!-- Success State -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center shadow-2xl glow animate-pulse">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M12 12v7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-4xl font-bold text-white tracking-tight">Email Enviado!</h2>
            <p class="mt-2 text-sm text-slate-300">Verifique sua caixa de entrada</p>
        </div>

        <!-- Success Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500/20 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Link Enviado com Sucesso</h3>
                    <p class="text-sm text-slate-300">
                        Enviámos um link de recuperação para<br>
                        <span class="font-medium text-white">{{ $email }}</span>
                    </p>
                </div>

                <!-- Instructions -->
                <div class="space-y-4 text-left">
                    <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                        <h4 class="font-medium text-white mb-2 flex items-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500/20 rounded-full text-blue-400 text-xs font-bold mr-2">1</span>
                            Verifique seu email
                        </h4>
                        <p class="text-xs text-slate-300 ml-8">O link foi enviado para sua caixa de entrada. Pode demorar alguns minutos.</p>
                    </div>
                    
                    <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                        <h4 class="font-medium text-white mb-2 flex items-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500/20 rounded-full text-blue-400 text-xs font-bold mr-2">2</span>
                            Clique no link
                        </h4>
                        <p class="text-xs text-slate-300 ml-8">O link é válido por 60 minutos por motivos de segurança.</p>
                    </div>
                    
                    <div class="bg-white/10 rounded-xl p-4 border border-white/20">
                        <h4 class="font-medium text-white mb-2 flex items-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500/20 rounded-full text-blue-400 text-xs font-bold mr-2">3</span>
                            Defina nova senha
                        </h4>
                        <p class="text-xs text-slate-300 ml-8">Escolha uma senha forte e única para proteger sua conta.</p>
                    </div>
                </div>

                <!-- Não recebeu? -->
                <div class="mt-6 p-4 bg-amber-500/20 border border-amber-500/50 rounded-xl backdrop-blur-sm">
                    <p class="text-sm text-amber-200 mb-2">Não recebeu o email?</p>
                    <div class="text-xs text-amber-300 space-y-1">
                        <p>• Verifique a pasta de spam/lixo eletrônico</p>
                        <p>• Aguarde alguns minutos</p>
                        <p>• Verifique se o email está correto</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-3">
                    <button wire:click="goBack" 
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl font-medium text-white hover:bg-white/20 transition-all duration-200 btn-hover">
                        Voltar ao Login
                    </button>
                    
                    <button wire:click="$set('emailSent', false)" 
                            class="text-sm text-slate-400 hover:text-slate-300 transition-colors">
                        Tentar com outro email
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>