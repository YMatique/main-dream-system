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
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- ============ SIDEBAR MELHORADA ============ -->
        <div id="sidebar" class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-72"> <!-- Aumentado de w-64 para w-72 -->
                <div class="flex flex-col flex-grow bg-white dark:bg-gray-800 shadow-xl overflow-y-auto">
                    
                    <!-- Logo/Company Header -->
                    <div class="flex items-center justify-center h-20 px-6 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center ring-2 ring-white/30">
                                <span class="text-white font-bold text-xl">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-white font-bold text-lg truncate">{{ Auth::user()->company->name ?? 'Empresa' }}</h2>
                                <p class="text-blue-100 text-sm">Sistema de Reparações</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    @if(Auth::user()->user_type === 'company_admin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Usuário
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Hierarchy -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        
                        <!-- Dashboard -->
                        <a href="{{ route('company.dashboard') }}" 
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200
                                  {{ request()->routeIs('company.dashboard') 
                                     ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-sm dark:bg-blue-900/20 dark:text-blue-300' 
                                     : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- ORDENS DE REPARAÇÃO (Collapsible Section) -->
                        <div class="space-y-1">
                            <div class="px-4 py-2">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ordens de Reparação
                                </h3>
                            </div>
                            
                            <!-- Submenu: Formulários -->
                            <div class="ml-4 space-y-1">
                                <div class="text-xs font-medium text-gray-500 px-4 py-1">Formulários</div>
                                
                                <a href="{{ route('company.repair-orders.form1') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form1') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.repair-orders.form1') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Formulário 1 - Inicial</span>
                                </a>
                                
                                <a href="{{ route('company.repair-orders.form2') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form2') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.repair-orders.form2') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Formulário 2 - Técnicos</span>
                                </a>
                                
                                <a href="{{ route('company.repair-orders.form3') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form3') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.repair-orders.form3') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Formulário 3 - Faturação</span>
                                </a>
                            </div>

                            <!-- Submenu: Listagens -->
                            <div class="ml-4 space-y-1 pt-2">
                                <div class="text-xs font-medium text-gray-500 px-4 py-1">Listagens</div>
                                
                                <a href="{{ route('company.orders.form1-list') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.orders.*-list') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-purple-500 {{ request()->routeIs('company.orders.*-list') ? 'bg-purple-500' : '' }}"></div>
                                    <span>Por Formulário</span>
                                </a>
                                
                                <a href="{{ route('company.orders.advanced-list') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.orders.advanced-list') 
                                             ? 'bg-purple-50 text-purple-700 border-l-2 border-purple-500 dark:bg-purple-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-purple-500 {{ request()->routeIs('company.orders.advanced-list') ? 'bg-purple-500' : '' }}"></div>
                                    <span>Listagem Avançada</span>
                                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800">Pro</span>
                                </a>
                            </div>
                        </div>

                        <!-- FATURAÇÃO -->
                        <div class="space-y-1 pt-4">
                            <div class="px-4 py-2">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Faturação
                                </h3>
                            </div>
                            
                            <div class="ml-4 space-y-1">
                                <a href="{{ route('company.billing.real') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.real') 
                                             ? 'bg-green-50 text-green-700 border-l-2 border-green-500 dark:bg-green-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-green-500 {{ request()->routeIs('company.billing.real') ? 'bg-green-500' : '' }}"></div>
                                    <span>Faturação Real</span>
                                </a>
                                
                                <a href="{{ route('company.billing.estimated') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.estimated') 
                                             ? 'bg-yellow-50 text-yellow-700 border-l-2 border-yellow-500 dark:bg-yellow-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-yellow-500 {{ request()->routeIs('company.billing.estimated') ? 'bg-yellow-500' : '' }}"></div>
                                    <span>Faturação Estimada</span>
                                </a>
                                
                                <a href="{{ route('company.billing.hh') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.hh') 
                                             ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 dark:bg-indigo-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-indigo-500 {{ request()->routeIs('company.billing.hh') ? 'bg-indigo-500' : '' }}"></div>
                                    <span>Faturação HH</span>
                                </a>
                            </div>
                        </div>

                        <!-- GESTÃO -->
                        <div class="space-y-1 pt-4">
                            <div class="px-4 py-2">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gestão
                                </h3>
                            </div>
                            
                            <div class="ml-4 space-y-1">
                                <a href="{{ route('company.manage.employees') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.employees') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.manage.employees') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Funcionários</span>
                                </a>
                                
                                <a href="{{ route('company.manage.clients') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.clients') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.manage.clients') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Clientes</span>
                                </a>
                                
                                <a href="{{ route('company.manage.materials') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.materials') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.manage.materials') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Materiais</span>
                                </a>

                                <a href="{{ route('company.manage.departments') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.departments') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full bg-gray-300 mr-3 group-hover:bg-blue-500 {{ request()->routeIs('company.manage.departments') ? 'bg-blue-500' : '' }}"></div>
                                    <span>Departamentos</span>
                                </a>
                            </div>
                        </div>

                        <!-- PORTAL DO FUNCIONÁRIO -->
                        @if(Auth::user()->company->employees()->where('email', Auth::user()->email)->exists())
                        <div class="pt-4">
                            <a href="{{ route('employee.portal') }}" 
                               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200
                                      {{ request()->routeIs('employee.*') 
                                         ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600 shadow-sm dark:bg-purple-900/20' 
                                         : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                <svg class="mr-3 w-5 h-5 {{ request()->routeIs('employee.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Portal do Funcionário</span>
                            </a>
                        </div>
                        @endif
                    </nav>

                    <!-- Logout Button -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full group flex items-center px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20 transition-all duration-200">
                                <svg class="mr-3 w-5 h-5 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Terminar Sessão</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ MAIN CONTENT ============ -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            
            <!-- ============ HEADER MELHORADO ============ -->
            <header class="relative z-10 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    
                    <!-- Left Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button id="open-sidebar" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>

                        <!-- Page title -->
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $title ?? 'Dashboard' }}
                            </h1>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        
                        <!-- Quick Actions -->
                        <div class="hidden md:flex items-center space-x-2">
                            <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Nova Ordem">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            
                            <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Buscar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Notificações">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <!-- Notification Badge -->
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-white font-bold">3</span>
                                </span>
                            </button>
                        </div>

                        <!-- Settings -->
                        <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Configurações">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>

                        <!-- Company Info Badge -->
                        <div class="hidden sm:flex items-center space-x-3 pl-4 border-l border-gray-200 dark:border-gray-600">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->company->name ?? 'Empresa' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if(Auth::user()->company->hasActiveSubscription())
                                        <span class="text-green-600">Subscrição Ativa</span>
                                    @else
                                        <span class="text-red-600">Subscrição Expirada</span>
                                    @endif
                                </p>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============ BREADCRUMBS ============ -->
                @if(isset($breadcrumbs) || request()->route())
                <div class="border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/30 px-4 sm:px-6 lg:px-8 py-3">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <!-- Home -->
                            <li>
                                <a href="{{ route('company.dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                </a>
                            </li>
                            
                            @php
                                $routeName = request()->route()->getName();
                                $breadcrumbMap = [
                                    'company.dashboard' => [['Dashboard', null]],
                                    'company.manage.employees' => [['Gestão', null], ['Funcionários', null]],
                                    'company.manage.clients' => [['Gestão', null], ['Clientes', null]],
                                    'company.manage.materials' => [['Gestão', null], ['Materiais', null]],
                                    'company.repair-orders.form1' => [['Ordens', null], ['Formulário 1', null]],
                                    'company.repair-orders.form2' => [['Ordens', null], ['Formulário 2', null]],
                                    'company.billing.real' => [['Faturação', null], ['Real', null]],
                                    'company.billing.estimated' => [['Faturação', null], ['Estimada', null]],
                                    'company.orders.advanced-list' => [['Ordens', null], ['Listagem Avançada', null]],
                                ];
                                $currentBreadcrumbs = $breadcrumbMap[$routeName] ?? [];
                            @endphp

                            @foreach($currentBreadcrumbs as $index => $breadcrumb)
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    @if($index === count($currentBreadcrumbs) - 1)
                                        <span class="text-gray-900 dark:text-white font-medium">{{ $breadcrumb[0] }}</span>
                                    @else
                                        <a href="{{ $breadcrumb[1] ?? '#' }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors">{{ $breadcrumb[0] }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
                @endif
            </header>

            <!-- ============ MAIN CONTENT AREA ============ -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-50 dark:bg-gray-900">
                <div class="py-8">
                    <div class=" mx-auto px-4 sm:px-6 lg:px-8">
                        
                        <!-- Flash Messages -->
                        @if (session()->has('message'))
                            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 dark:text-green-200 font-medium">{{ session('message') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700 dark:text-red-200 font-medium">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('warning'))
                            <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 dark:text-yellow-200 font-medium">{{ session('warning') }}</p>
                                    </div>
                                </div>
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
    
    <!-- Enhanced JavaScript for mobile menu and interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openSidebar = document.getElementById('open-sidebar');
            const mobileSidebar = document.getElementById('mobile-sidebar');

            // Mobile sidebar functionality
            if (openSidebar) {
                openSidebar.addEventListener('click', function() {
                    // Create mobile sidebar if it doesn't exist
                    if (!mobileSidebar) {
                        createMobileSidebar();
                    } else {
                        mobileSidebar.style.display = 'flex';
                    }
                });
            }

            function createMobileSidebar() {
                const sidebar = document.getElementById('sidebar');
                const mobileSidebarHTML = `
                    <div id="mobile-sidebar" class="fixed inset-0 flex z-50 lg:hidden">
                        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
                        <div class="relative flex-1 flex flex-col max-w-xs w-full">
                            ${sidebar.innerHTML}
                            <div class="absolute top-4 right-4">
                                <button id="close-sidebar" class="p-2 rounded-md text-white bg-black bg-opacity-20 hover:bg-opacity-30">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', mobileSidebarHTML);
                
                // Add event listeners to new mobile sidebar
                const newMobileSidebar = document.getElementById('mobile-sidebar');
                const closeSidebar = document.getElementById('close-sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');

                if (closeSidebar) {
                    closeSidebar.addEventListener('click', function() {
                        newMobileSidebar.style.display = 'none';
                    });
                }

                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', function() {
                        newMobileSidebar.style.display = 'none';
                    });
                }
            }

            // Dark mode toggle (if implemented)
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
                });
            }

            // Apply saved dark mode preference
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>