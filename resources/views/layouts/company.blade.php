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
    @yield('styles')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered
        {
            padding: 10px;
        }
        .select2-container .select2-selection--single 
        {
            height: unset;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder
        {
            padding-left: 28px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- ============ SIDEBAR MELHORADA COM DROPDOWNS ============ -->
        <div id="sidebar" class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-80"> <!-- Aumentado para w-80 -->
                <div class="flex flex-col flex-grow bg-white dark:bg-gray-800 shadow-xl overflow-y-auto">
                    
                    <!-- Logo/Company Header -->
                    <div class="flex items-center justify-center h-20 px-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700">
                        <div class="flex items-center space-x-3 w-full">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center ring-2 ring-white/30 shadow-lg">
                                <span class="text-white font-bold text-xl">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-white font-bold text-lg truncate">{{ Auth::user()->company->name ?? 'Empresa' }}</h2>
                                <p class="text-blue-100 text-sm flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Sistema de Reparações
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white dark:border-gray-700 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    @if(Auth::user()->user_type === 'company_admin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900 dark:to-emerald-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 dark:from-blue-900 dark:to-indigo-900 dark:text-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
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
                                     ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border-l-4 border-blue-600 shadow-sm dark:from-blue-900/20 dark:to-indigo-900/20 dark:text-blue-300' 
                                     : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                            <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Principal</span>
                        </a>

                        <!-- ORDENS DE REPARAÇÃO (Dropdown) -->
                        <div class="space-y-1" x-data="{ ordersOpen: {{ request()->routeIs('company.repair-orders.*') || request()->routeIs('company.orders.*') ? 'true' : 'false' }} }">
                            <button @click="ordersOpen = !ordersOpen" 
                                    class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700
                                           {{ request()->routeIs('company.repair-orders.*') || request()->routeIs('company.orders.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 dark:from-blue-900/20 dark:to-indigo-900/20 dark:text-blue-300' : '' }}">
                                <div class="flex items-center">
                                    <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.repair-orders.*') || request()->routeIs('company.orders.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Ordens de Reparação</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">5</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="ordersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            
                            <!-- Submenu: Formulários -->
                            <div x-show="ordersOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-6 space-y-1">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-2 flex items-center">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-1a1 1 0 00-1-1H9a1 1 0 00-1 1v1a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Formulários
                                </div>
                                
                                <a href="{{ route('company.repair-orders.form1') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form1') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.repair-orders.form1') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <span class="flex-1">Formulário 1 - Inicial</span>
                                    <span class="text-xs text-gray-400">F1</span>
                                </a>
                                
                                <a href="{{ route('company.repair-orders.form2') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form2') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.repair-orders.form2') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <span class="flex-1">Formulário 2 - Técnicos</span>
                                    <span class="text-xs text-gray-400">F2</span>
                                </a>
                                
                                <a href="{{ route('company.repair-orders.form3') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form3') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.repair-orders.form3') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <span class="flex-1">Formulário 3 - Faturação</span>
                                    <span class="text-xs text-gray-400">F3</span>
                                </a>

                                <a href="{{ route('company.repair-orders.form4') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form4') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.repair-orders.form4') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <span class="flex-1">Formulário 4 - Máquina</span>
                                    <span class="text-xs text-gray-400">F4</span>
                                </a>

                                <a href="{{ route('company.repair-orders.form5') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.repair-orders.form5') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.repair-orders.form5') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <span class="flex-1">Formulário 5 - Equipamento</span>
                                    <span class="text-xs text-gray-400">F5</span>
                                </a>

                                <!-- Submenu: Listagens -->
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-2 mt-3 flex items-center">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Listagens
                                </div>
                                
                                <a href="{{ route('company.orders.index') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.orders.*-list') 
                                             ? 'bg-purple-50 text-purple-700 border-l-2 border-purple-500 dark:bg-purple-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.orders.*-list') ? 'bg-purple-500' : 'bg-gray-300 group-hover:bg-purple-500' }}"></div>
                                    <span class="flex-1">Por Formulário</span>
                                    <span class="text-xs text-gray-400">Lista</span>
                                </a>
                                
                                <a href="{{ route('company.orders.advanced-list') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.orders.advanced-list') 
                                             ? 'bg-purple-50 text-purple-700 border-l-2 border-purple-500 dark:bg-purple-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.orders.advanced-list') ? 'bg-purple-500' : 'bg-gray-300 group-hover:bg-purple-500' }}"></div>
                                    <span class="flex-1">Listagem Avançada</span>
                                    <span class="text-xs bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 px-2 py-0.5 rounded-full">Pro</span>
                                </a>
                            </div>
                        </div>

                        <!-- FATURAÇÃO (Dropdown) -->
                        <div class="space-y-1" x-data="{ billingOpen: {{ request()->routeIs('company.billing.*') ? 'true' : 'false' }} }">
                            <button @click="billingOpen = !billingOpen" 
                                    class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700
                                           {{ request()->routeIs('company.billing.*') ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 dark:from-green-900/20 dark:to-emerald-900/20 dark:text-green-300' : '' }}">
                                <div class="flex items-center">
                                    <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.billing.*') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <span>Faturação</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">3</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="billingOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            
                            <div x-show="billingOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-6 space-y-1">
                                <a href="{{ route('company.billing.real') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.real') 
                                             ? 'bg-green-50 text-green-700 border-l-2 border-green-500 dark:bg-green-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.billing.real') ? 'bg-green-500' : 'bg-gray-300 group-hover:bg-green-500' }}"></div>
                                    <span class="flex-1">Faturação Real</span>
                                    <span class="text-xs text-green-600 font-medium">MT</span>
                                </a>
                                
                                <a href="{{ route('company.billing.estimated') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.estimated') 
                                             ? 'bg-yellow-50 text-yellow-700 border-l-2 border-yellow-500 dark:bg-yellow-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.billing.estimated') ? 'bg-yellow-500' : 'bg-gray-300 group-hover:bg-yellow-500' }}"></div>
                                    <span class="flex-1">Faturação Estimada</span>
                                    <span class="text-xs text-yellow-600 font-medium">EST</span>
                                </a>
                                
                                <a href="{{ route('company.billing.hh') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.billing.hh') 
                                             ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 dark:bg-indigo-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.billing.hh') ? 'bg-indigo-500' : 'bg-gray-300 group-hover:bg-indigo-500' }}"></div>
                                    <span class="flex-1">Faturação HH</span>
                                    <span class="text-xs text-indigo-600 font-medium">HH</span>
                                </a>
                            </div>
                        </div>

                        <!-- GESTÃO (Dropdown) -->
                        <div class="space-y-1" x-data="{ manageOpen: {{ request()->routeIs('company.manage.*') ? 'true' : 'false' }} }">
                            <button @click="manageOpen = !manageOpen" 
                                    class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700
                                           {{ request()->routeIs('company.manage.*') ? 'bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 dark:from-blue-900/20 dark:to-cyan-900/20 dark:text-blue-300' : '' }}">
                                <div class="flex items-center">
                                    <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.manage.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Gestão</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">9</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="manageOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            
                            <div x-show="manageOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-6 space-y-1">
                                <a href="{{ route('company.manage.employees') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.employees') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.employees') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <span class="flex-1">Funcionários</span>
                                    <span class="text-xs text-gray-400">{{ \App\Models\Company\Employee::where('company_id', auth()->user()->company_id)->count() ?? 0 }}</span>
                                </a>
                                
                                <a href="{{ route('company.manage.clients') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.clients') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.clients') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="flex-1">Clientes</span>
                                    <span class="text-xs text-gray-400">{{ \App\Models\Company\Client::where('company_id', auth()->user()->company_id)->count() ?? 0 }}</span>
                                </a>
                                
                                <a href="{{ route('company.manage.materials') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.materials') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.materials') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span class="flex-1">Materiais</span>
                                    <span class="text-xs text-gray-400">{{ \App\Models\Company\Material::where('company_id', auth()->user()->company_id)->count() ?? 0 }}</span>
                                </a>

                                <a href="{{ route('company.manage.departments') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.departments') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.departments') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="flex-1">Departamentos</span>
                                    <span class="text-xs text-gray-400">{{ \App\Models\Company\Department::where('company_id', auth()->user()->company_id)->count() ?? 0 }}</span>
                                </a>

                                <a href="{{ route('company.manage.maintenance-types') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.maintenance-types') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.maintenance-types') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="flex-1">Tipos de Manutenção</span>
                                </a>

                                <a href="{{ route('company.manage.client-costs') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.client-costs') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.client-costs') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <span class="flex-1">Custos de Clientes</span>
                                </a>

                                <a href="{{ route('company.manage.machine-numbers') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.machine-numbers') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.machine-numbers') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="flex-1">Números de Máquina</span>
                                </a>

                                <a href="{{ route('company.manage.requesters') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.requesters') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.requesters') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="flex-1">Solicitantes</span>
                                </a>

                                <a href="{{ route('company.manage.statuses') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.statuses') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.statuses') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="flex-1">Estados & Localização</span>
                                </a>

                                 <a href="{{ route('company.manage.users-permissions') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.manage.users-permissions') 
                                             ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500 dark:bg-blue-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.manage.users-permissions') ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-blue-500' }}"></div>
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="flex-1">Usuários e Permissões</span>
                                </a>

                                
                            </div>
                        </div>

                        <!-- AVALIAÇÃO (Dropdown) -->
                        <div class="space-y-1" x-data="{ evaluationOpen: {{ request()->routeIs('company.perfomance.*') ? 'true' : 'false' }} }">
                            <button @click="evaluationOpen = !evaluationOpen" 
                                    class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700
                                           {{ request()->routeIs('company.performance.*') ? 'bg-gradient-to-r from-orange-50 to-red-50 text-orange-700 dark:from-orange-900/20 dark:to-red-900/20 dark:text-orange-300' : '' }}">
                                <div class="flex items-center">
                                    <svg class="mr-3 w-5 h-5 {{ request()->routeIs('company.performance.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Avaliação</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded-full">3</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="evaluationOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            
                            <div x-show="evaluationOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-6 space-y-1">
                                <a href="{{ route('company.performance.metrics') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.performance.metrics') 
                                             ? 'bg-orange-50 text-orange-700 border-l-2 border-orange-500 dark:bg-orange-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.performance.metrics') ? 'bg-orange-500' : 'bg-gray-300 group-hover:bg-orange-500' }}"></div>
                                    <span class="flex-1">Gestão de Métricas</span>
                                    <span class="text-xs text-orange-600 font-medium">Cfg</span>
                                </a>
                                
                                <a href="{{ route('company.performance.evaluations') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.performance.evaluations') 
                                             ? 'bg-orange-50 text-orange-700 border-l-2 border-orange-500 dark:bg-orange-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.performance.evaluations') ? 'bg-orange-500' : 'bg-gray-300 group-hover:bg-orange-500' }}"></div>
                                    <span class="flex-1">Avaliar Funcionários</span>
                                    <span class="text-xs text-orange-600 font-medium">Eval</span>
                                </a>
                                <a href="{{ route('company.performance.evaluations.approvals') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.performance.evaluations.approvals') 
                                             ? 'bg-orange-50 text-orange-700 border-l-2 border-orange-500 dark:bg-orange-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.performance.evaluations.approvals') ? 'bg-orange-500' : 'bg-gray-300 group-hover:bg-orange-500' }}"></div>
                                    <span class="flex-1">Aprovação de Funcionários</span>
                                    <span class="text-xs text-orange-600 font-medium">Apr</span>
                                </a>
                                <a href="{{ route('company.performance.reports') }}" 
                                   class="group flex items-center px-4 py-2.5 text-sm rounded-lg transition-all duration-200
                                          {{ request()->routeIs('company.performance.reports') 
                                             ? 'bg-orange-50 text-orange-700 border-l-2 border-orange-500 dark:bg-orange-900/20' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <div class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('company.performance.reports') ? 'bg-orange-500' : 'bg-gray-300 group-hover:bg-orange-500' }}"></div>
                                    <span class="flex-1">Relatórios</span>
                                    <span class="text-xs text-orange-600 font-medium">Rep</span>
                                </a>
                            </div>
                        </div>

                        <!-- PORTAL DO FUNCIONÁRIO -->
                        @if(Auth::user()->company->employees()->where('email', Auth::user()->email)->exists())
                        <div class="pt-4">
                            <a href="{{ route('employee.portal') }}" 
                               class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200
                                      {{ request()->routeIs('employee.*') 
                                         ? 'bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border-l-4 border-purple-600 shadow-sm dark:from-purple-900/20 dark:to-pink-900/20 dark:text-purple-300' 
                                         : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                <svg class="mr-3 w-5 h-5 {{ request()->routeIs('employee.*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="flex-1">Portal do Funcionário</span>
                                <span class="text-xs bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 px-2 py-0.5 rounded-full">Portal</span>
                            </a>
                        </div>
                        @endif
                    </nav>

                    <!-- Logout Button -->
                    <div class="p-6 border-t border-gray-200 dark:border-gray-600">
                        <form method="POST" action="{{ route('company.logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full group flex items-center px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20 transition-all duration-200 border border-gray-200 dark:border-gray-600 hover:border-red-200">
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
            
            <!-- ============ HEADER MELHORADO COM TOOLTIPS ============ -->
            <header class="relative  bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-20 px-6 lg:px-8">
                    
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
                    <div class="flex items-center space-x-3">
                        
                        <!-- Quick Actions -->
                        <div class="hidden md:flex items-center space-x-2">
                            <div class="relative" x-data="{ tooltip: false }">
                                <button @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                                        onclick="window.location.href='{{ route('company.repair-orders.form1') }}'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <div x-show="tooltip" x-transition 
                                     class="absolute top-full mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                                    Nova Ordem de Reparação
                                </div>
                            </div>
                            
                            <div class="relative" x-data="{ tooltip: false }">
                                <button @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                        class="p-2 text-gray-400 hover:text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors"
                                        onclick="window.location.href='{{ route('company.orders.advanced-list') }}'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                                <div x-show="tooltip" x-transition 
                                     class="absolute top-full mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                                    Pesquisa Avançada
                                </div>
                            </div>
                        </div>

                        <!-- Notifications with Dropdown -->
                        <div class="relative" x-data="{ open: false, tooltip: false }">
                            <button @click="open = !open" @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <!-- Notification Badge -->
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-white font-bold">3</span>
                                </span>
                            </button>
                            
                            <!-- Tooltip -->
                            <div x-show="tooltip && !open" x-transition 
                                 class="absolute top-full mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                                3 Notificações Novas
                            </div>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notificações</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- Notification Items -->
                                    <div class="p-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 dark:text-white">Nova ordem de reparação #1234</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliente: Empresa XYZ - Há 2 horas</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 dark:text-white">Faturação aprovada</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Ordem #1230 - Há 1 dia</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 dark:text-white">Material em baixo stock</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Parafusos M8 - Há 2 dias</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Ver todas as notificações</a>
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="relative" x-data="{ tooltip: false }">
                            <button @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                                    class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                            <div x-show="tooltip" x-transition 
                                 class="absolute top-full mb-2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                                Configurações
                            </div>
                        </div>

                        <!-- Company Info Badge -->
                        <div class="hidden sm:flex items-center space-x-3 pl-4 border-l border-gray-200 dark:border-gray-600">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->company->name ?? 'Empresa' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                    @if(Auth::user()->company->hasActiveSubscription())
                                        <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-green-600 font-medium">Subscrição Ativa</span>
                                    @else
                                        <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-red-600 font-medium">Subscrição Expirada</span>
                                    @endif
                                </p>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============ BREADCRUMBS COM PADDING CONSISTENTE ============ -->
                @if(isset($breadcrumbs) || request()->route())
                <div class="border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/30 px-6 lg:px-8 py-3">
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
                                    'company.manage.departments' => [['Gestão', null], ['Departamentos', null]],
                                    'company.manage.maintenance-types' => [['Gestão', null], ['Tipos de Manutenção', null]],
                                    'company.manage.client-costs' => [['Gestão', null], ['Custos de Clientes', null]],
                                    'company.manage.machine-numbers' => [['Gestão', null], ['Números de Máquina', null]],
                                    'company.manage.requesters' => [['Gestão', null], ['Solicitantes', null]],
                                    'company.manage.statuses' => [['Gestão', null], ['Estados & Localização', null]],
                                    'company.manage.users-permissions'=> [['Gestão', null], ['Utilizadores & Permissões', null]],
                                    'company.orders.index' => [['Ordens de Reparação', null], ['Listagem', null]],
                                    'company.orders.form1' => [['Ordens', null], ['Formulário 1', null]],
                                    'company.orders.form2' => [['Ordens', null], ['Formulário 2', null]],
                                    'company.orders.form3' => [['Ordens', null], ['Formulário 3', null]],
                                    'company.orders.form4' => [['Ordens', null], ['Formulário 4', null]],
                                    'company.orders.form5' => [['Ordens', null], ['Formulário 5', null]],
                                    'company.billing.real' => [['Faturação', null], ['Real', null]],
                                    'company.billing.estimated' => [['Faturação', null], ['Estimada', null]],
                                    'company.billing.hh' => [['Faturação', null], ['HH', null]],
                                    'company.orders.advanced-list' => [['Ordens', null], ['Listagem Avançada', null]],
                                    'company.performance.metrics' => [['Avaliação', null], ['Gestão de Métricas', null]],
                                    'company.performance.perform' => [['Avaliação', null], ['Avaliar Funcionários', null]],
                                    'company.evaluation.reports' => [['Avaliação', null], ['Relatórios', null]],
                                    'employee.portal' => [['Portal do Funcionário', null]],
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
                                        <a href="{{ $breadcrumb[1] ?? '#' }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors">
                                            {{ $breadcrumb[0] }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
                @endif
            </header>

            <!-- ============ MAIN CONTENT AREA ============ -->
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-gray-50 dark:bg-gray-900">
                <div class="px-6 lg:px-8 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden">
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 flex flex-col w-80 bg-white dark:bg-gray-800 shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out">
            <!-- Same sidebar content as desktop but with close button -->
            <div class="flex items-center justify-between h-16 px-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center ring-2 ring-white/30 shadow-lg">
                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->company->name ?? 'E', 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg">{{ Auth::user()->company->name ?? 'Empresa' }}</h2>
                        <p class="text-blue-100 text-sm">Sistema de Reparações</p>
                    </div>
                </div>
                <button id="close-sidebar" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Copy the rest of the sidebar navigation here -->
        </div>
    </div>

    <!-- JavaScript for mobile sidebar -->
    <script>
        document.getElementById('open-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar-overlay').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('mobile-sidebar').classList.remove('-translate-x-full');
            }, 10);
        });

        document.getElementById('close-sidebar').addEventListener('click', function() {
            document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
            setTimeout(() => {
                document.getElementById('sidebar-overlay').classList.add('hidden');
            }, 300);
        });

        document.getElementById('sidebar-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
                setTimeout(() => {
                    document.getElementById('sidebar-overlay').classList.add('hidden');
                }, 300);
            }
        });
    </script>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
    @yield('scripts')
</body>
</html>