{{-- resources/views/livewire/company/company-login.blade.php --}}

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black/20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    </div>

    <!-- Animated Elements -->
    <div class="absolute top-20 left-20 w-72 h-72 bg-gradient-to-r from-blue-500/30 to-cyan-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute top-40 right-20 w-72 h-72 bg-gradient-to-r from-purple-500/30 to-blue-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-gradient-to-r from-indigo-500/30 to-purple-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

    <div class="max-w-md w-full space-y-8 p-8 relative z-10">
        <!-- Logo/Brand -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-4xl font-bold text-white tracking-tight">{{ config('app.name') }}</h2>
            <p class="mt-2 text-sm text-blue-100">Sistema de Gestão de Ordens de Reparação</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-white text-center">Acesso da Empresa</h3>
                <p class="mt-2 text-sm text-blue-100 text-center">Entre com suas credenciais para acessar o sistema</p>
            </div>

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

            <form wire:submit="login" class="space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input wire:model="email" 
                               type="email" 
                               autocomplete="email"
                               placeholder="seu.email@empresa.com"
                               class="block w-full pl-12 pr-4 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                               required>
                    </div>
                    @error('email') 
                        <p class="mt-2 text-sm text-red-300 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Senha</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input wire:model="password" 
                               type="password"
                               autocomplete="current-password"
                               placeholder="Sua senha"
                               class="block w-full pl-12 pr-4 py-4 bg-white/20 border border-white/30 rounded-xl text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                               required>
                    </div>
                    @error('password') 
                        <p class="mt-2 text-sm text-red-300 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input wire:model="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-white/30 rounded bg-white/20">
                        <span class="ml-2 text-sm text-blue-100">Manter-me conectado</span>
                    </label>
                    <a href="{{ route('company.password.request') }}" 
                       class="text-sm text-blue-300 hover:text-blue-200 font-medium transition-colors">
                        Esqueceu a senha?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full flex justify-center items-center px-4 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                    <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>Acessar Sistema</span>
                    <span wire:loading>Verificando...</span>
                </button>
            </form>

            <!-- Help Section -->
            <div class="mt-6 p-4 bg-blue-500/20 border border-blue-500/50 rounded-xl backdrop-blur-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-300 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs text-blue-200">
                            <strong>Precisa de ajuda?</strong> Se não conseguir aceder, contacte o administrador da sua empresa ou o suporte técnico.
                        </p>
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-blue-300">• Verifique se sua conta está ativa</p>
                            <p class="text-xs text-blue-300">• Confirme se a subscrição da empresa está válida</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links de Navegação -->
        <div class="text-center space-y-3">
            <div class="flex justify-center space-x-4 text-sm">
                <a href="{{ route('system.login') }}" 
                   class="text-blue-300 hover:text-blue-200 transition-colors">
                    Acesso Administrativo
                </a>
                <span class="text-blue-400">•</span>
                <a href="{{ route('home') }}" 
                   class="text-blue-300 hover:text-blue-200 transition-colors">
                    Página Inicial
                </a>
            </div>
            <p class="text-xs text-blue-400">
                Sistema seguro com monitoramento de acesso • 
                <a href="#" class="hover:text-blue-300 transition-colors">Termos de Uso</a>
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>