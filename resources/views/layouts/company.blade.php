{{-- resources/views/layouts/company.blade.php --}}
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - {{ Auth::user()->company->name ?? 'Empresa' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col flex-grow bg-white pt-5 pb-4 overflow-y-auto border-r border-gray-200">
                    
                    <!-- Logo/Company Info -->
                    <div class="flex items-center flex-shrink-0 px-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <h2 class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->company->name ?? 'Empresa' }}</h2>
                                <p class="text-xs text-gray-500">Sistema de Reparações</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="mt-8 flex-1 flex flex-col divide-y divide-gray-200 overflow-y-auto">
                        
                        <!-- Main Section -->
                        <div class="pb-4 space-y-1 px-3">
                            <!-- Dashboard -->
                            <a href="{{ route('company.dashboard') }}" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                      {{ request()->routeIs('company.dashboard') 
                                         ? 'bg-blue-50 text-blue-700' 
                                         : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                Dashboard
                            </a>

                            <!-- Ordens de Reparação -->
                            <div class="space-y-1">
                                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ordens de Reparação</h3>
                                
                                <!-- Formulários -->
                                <a href="{{ route('company.repair-orders.form1') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.repair-orders.form1') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Formulário 1 - Inicial
                                </a>

                                <!-- Listagens -->
                                <a href="{{ route('company.orders.form1-list') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.orders.*-list') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Listagens
                                </a>

                                <!-- Listagem Avançada -->
                                <a href="{{ route('company.orders.advanced-list') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.orders.advanced-list') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Listagem Avançada
                                </a>
                            </div>

                            <!-- Faturação -->
                            <div class="space-y-1 pt-4">
                                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Faturação</h3>
                                
                                <a href="{{ route('company.billing.real') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.billing.real') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Faturação Real
                                </a>

                                <a href="{{ route('company.billing.estimated') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.billing.estimated') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Faturação Estimada
                                </a>
                            </div>

                            <!-- Gestão -->
                            <div class="space-y-1 pt-4">
                                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestão</h3>
                                
                                <a href="{{ route('company.manage.employees') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.manage.employees') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Funcionários
                                </a>

                                <a href="{{ route('company.manage.clients') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.manage.clients') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Clientes
                                </a>

                                <a href="{{ route('company.manage.materials') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150
                                          {{ request()->routeIs('company.manage.materials') 
                                             ? 'bg-blue-50 text-blue-700' 
                                             : 'text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Materiais
                                </a>
                            </div>
                        </div>

                        <!-- User section at bottom -->
                        <div class="pt-4 mt-auto">
                            <div class="px-3 space-y-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">
                                                @if(Auth::user()->user_type === 'company_admin')
                                                    Administrador
                                                @else
                                                    Usuário
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Portal do Funcionário (se aplicável) -->
                                @if(Auth::user()->company->employees()->where('email', Auth::user()->email)->exists())
                                    <a href="{{ route('employee.portal') }}" 
                                       class="w-full group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Portal do Funcionário
                                    </a>
                                @endif
                                
                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Terminar Sessão
                                    </button>
                                </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar (similar ao sistema) -->
        <div id="mobile-sidebar" class="fixed inset-0 flex z-40 lg:hidden" style="display: none;">
            <!-- Overlay -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            
            <!-- Sidebar panel -->
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button id="close-sidebar" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile navigation content (simplified) -->
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <span class="text-xl font-bold text-gray-900">{{ Auth::user()->company->name ?? 'Empresa' }}</span>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        <a href="{{ route('company.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('company.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            Dashboard
                        </a>
                        <!-- Add other mobile menu items here -->
                    </nav>
                </div>
                
                <!-- Mobile logout -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">
                            Terminar Sessão
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow border-b border-gray-200">
                <!-- Mobile menu button -->
                <button id="open-sidebar" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>

                <!-- Page title -->
                <div class="flex-1 px-4 flex justify-between items-center">
                    <div class="flex-1">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $title ?? 'Dashboard' }}
                            </h1>
                        @endisset
                    </div>
                    
                    <!-- Company info in header -->
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="hidden sm:block">{{ Auth::user()->company->name ?? 'Empresa' }}</span>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Flash Messages -->
                        @if (session()->has('message'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('message') }}</span>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Page content -->
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    
    <!-- Simple JavaScript for mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openSidebar = document.getElementById('open-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (openSidebar) {
                openSidebar.addEventListener('click', function() {
                    mobileSidebar.style.display = 'flex';
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    mobileSidebar.style.display = 'none';
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    mobileSidebar.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>