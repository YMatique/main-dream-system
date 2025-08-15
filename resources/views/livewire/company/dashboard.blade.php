<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">
    
    {{-- CONTAINER PRINCIPAL COM GRID ORGANIZADO --}}
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-[1600px] mx-auto">
            
            {{-- ===== ROW 1: HEADER ===== --}}
            <div class="mb-8">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent dark:from-white dark:to-gray-300">
                                Dashboard da Empresa
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-300 font-medium">Visão geral das operações e métricas</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select 
                            wire:model.live="selectedPeriod"
                            class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 shadow-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                        >
                            <option value="current_month">Mês Atual</option>
                            <option value="last_month">Mês Anterior</option>
                            <option value="quarter">Trimestre</option>
                            <option value="year">Ano</option>
                        </select>
                        <button 
                            wire:click="exportReport"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl flex items-center space-x-2 shadow-lg transition-all duration-200 transform hover:scale-105"
                        >
                            <span>📊</span>
                            <span class="font-medium">Exportar Relatório</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 2: ALERTAS ===== --}}
            @if(!empty($dashboardData['alerts']))
                <div class="mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        @foreach($dashboardData['alerts'] as $alert)
                            <div class="relative overflow-hidden rounded-2xl p-6 shadow-lg backdrop-blur-sm border transition-all duration-200 hover:shadow-xl hover:scale-105 
                                {{ $alert['type'] === 'warning' ? 'bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200 dark:from-amber-900/20 dark:to-orange-900/20' : 
                                   ($alert['type'] === 'info' ? 'bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200 dark:from-blue-900/20 dark:to-indigo-900/20' : 
                                   'bg-gradient-to-r from-emerald-50 to-teal-50 border-emerald-200 dark:from-emerald-900/20 dark:to-teal-900/20') }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-xl 
                                            {{ $alert['type'] === 'warning' ? 'bg-amber-100 text-amber-700' : 
                                               ($alert['type'] === 'info' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            <span class="text-lg">{{ $alert['icon'] }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $alert['message'] }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm font-bold text-white 
                                        {{ $alert['type'] === 'warning' ? 'bg-amber-500' : 
                                           ($alert['type'] === 'info' ? 'bg-blue-500' : 'bg-emerald-500') }}">
                                        {{ $alert['count'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== ROW 3: MÉTRICAS PRINCIPAIS (4 COLUNAS) ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    
                    {{-- ORDENS DE REPARAÇÃO --}}
                    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-indigo-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Ordens do Período</p>
                                <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['orders']['current_period'] ?? 0 }}</p>
                                <div class="flex items-center space-x-2">
                                    @php
                                        $change = $dashboardData['metrics']['orders']['percentage_change'] ?? 0;
                                    @endphp
                                    <div class="flex items-center space-x-1 px-2 py-1 rounded-full text-sm font-medium 
                                        {{ $change > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        <span>{{ $change > 0 ? '↗️' : '↘️' }}</span>
                                        <span>{{ abs($change) }}%</span>
                                    </div>
                                    <span class="text-sm text-gray-500">vs anterior</span>
                                </div>
                            </div>
                        </div>
                    </div>

                      {{-- FATURAÇÃO TOTAL --}}
                    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/10 to-teal-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Faturação Total</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                    {{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 2, ',', '.') }} MZN
                                </p>
                                <p class="text-lg text-gray-500 mb-2">
                                    ${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 2, '.', ',') }}
                                </p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FUNCIONÁRIOS ATIVOS --}}
                    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-pink-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Funcionários</p>
                                <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['employees']['total_active'] ?? 0 }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ $dashboardData['metrics']['employees']['total_inactive'] ?? 0 }} inativos</span>
                                    <div class="flex space-x-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <div class="w-2 h-2 rounded-full {{ $i < 4 ? 'bg-purple-500' : 'bg-gray-300' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PERFORMANCE MÉDIA --}}
                    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/10 to-orange-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Performance</p>
                                <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ $dashboardData['metrics']['employees']['evaluations_pending'] ?? 0 }} pendentes</span>
                                    <div class="w-12 h-12 relative">
                                        <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f59e0b" stroke-width="3" stroke-dasharray="{{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}, 100"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 4: FATURAÇÃO DETALHADA (4 COLUNAS) ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    
                    {{-- FATURAÇÃO HH --}}
                    <div class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl shadow-xl p-6 border border-blue-200/50 dark:border-blue-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-indigo-600/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Faturação HH</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Sistema automático</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $dashboardData['metrics']['billing']['hh']['count'] ?? 0 }}
                                    </span>
                                    <span class="text-blue-600 text-2xl">⚡</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">MZN:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        {{ number_format($dashboardData['metrics']['billing']['hh']['total_mzn'] ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">USD:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        ${{ number_format($dashboardData['metrics']['billing']['hh']['total_usd'] ?? 0, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="pt-2">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600 dark:text-gray-400">Distribuição de moedas</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $dashboardData['metrics']['billing']['hh']['currency_split']['mzn'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $dashboardData['metrics']['billing']['hh']['currency_split']['mzn'] ?? 0 }}% MZN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO ESTIMADA --}}
                    <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl shadow-xl p-6 border border-emerald-200/50 dark:border-emerald-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-400/20 to-teal-600/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Faturação Estimada</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Previsão ajustável</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $dashboardData['metrics']['billing']['estimated']['count'] ?? 0 }}
                                    </span>
                                    <span class="text-emerald-600 text-2xl">📊</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">MZN:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        {{ number_format($dashboardData['metrics']['billing']['estimated']['total_mzn'] ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">USD:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        ${{ number_format($dashboardData['metrics']['billing']['estimated']['total_usd'] ?? 0, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="pt-2">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600 dark:text-gray-400">Distribuição de moedas</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $dashboardData['metrics']['billing']['estimated']['currency_split']['mzn'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $dashboardData['metrics']['billing']['estimated']['currency_split']['mzn'] ?? 0 }}% MZN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO REAL --}}
                    <div class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl shadow-xl p-6 border border-purple-200/50 dark:border-purple-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-400/20 to-pink-600/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Faturação Real</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Valores finalizados</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $dashboardData['metrics']['billing']['real']['count'] ?? 0 }}
                                    </span>
                                    <span class="text-purple-600 text-2xl">💎</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">MZN:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        {{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">USD:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        ${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="pt-2">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600 dark:text-gray-400">Distribuição de moedas</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $dashboardData['metrics']['billing']['real']['currency_split']['mzn'] ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $dashboardData['metrics']['billing']['real']['currency_split']['mzn'] ?? 0 }}% MZN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO DE MATERIAIS --}}
                    <div class="group relative overflow-hidden bg-gradient-to-br from-amber-50 to-orange-100 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl shadow-xl p-6 border border-amber-200/50 dark:border-amber-700/50 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-400/20 to-orange-600/20 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Faturação Materiais</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Custos de materiais</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $dashboardData['metrics']['billing']['materials']['orders_count'] ?? 0 }}
                                    </span>
                                    <span class="text-amber-600 text-2xl">🔧</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">MZN:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        {{ number_format($dashboardData['metrics']['billing']['materials']['total_mzn'] ?? 0, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">USD:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">
                                        ${{ number_format($dashboardData['metrics']['billing']['materials']['total_usd'] ?? 0, 2, '.', ',') }}
                                    </span>
                                </div>
                                <div class="pt-2">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600 dark:text-gray-400">Materiais diferentes</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>{{ $dashboardData['metrics']['billing']['materials']['materials_count'] ?? 0 }} tipos</span>
                                        <span>{{ $dashboardData['metrics']['billing']['materials']['orders_count'] ?? 0 }} ordens</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== ROW 5: GRÁFICOS PRINCIPAIS (2 COLUNAS) ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- EVOLUÇÃO MENSAL --}}
                    <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Evolução Mensal</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Ordens e faturação</p>
                            </div>
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                        </div>
                        <div class="h-[350px]">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    {{-- ORDENS RECENTES --}}
                    <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Ordens Recentes</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Atividade recente</p>
                            </div>
                            <div class="text-3xl">⚡</div>
                        </div>
                        <div class="space-y-4">
                            @forelse($dashboardData['recent_orders'] ?? [] as $order)
                                <div class="relative overflow-hidden flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl hover:shadow-md transition-all duration-200 hover:scale-105">
                                    <div class="w-1 h-full absolute left-0 top-0 
                                        {{ $order['priority'] === 'high' ? 'bg-red-500' : 
                                           ($order['priority'] === 'medium' ? 'bg-yellow-500' : 'bg-green-500') }}"></div>
                                    <div class="flex-1 ml-3">
                                        <div class="flex items-center justify-between">
                                            <p class="font-bold text-gray-900 dark:text-white">{{ $order['id'] }}</p>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $order['status'] === 'Concluída' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' : 
                                                   ($order['status'] === 'Em Andamento' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : 
                                                   'bg-amber-100 text-amber-800 dark:bg-amber-900/20 dark:text-amber-400') }}">
                                                {{ $order['status'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $order['client'] }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <p class="text-xs text-gray-500">👤 {{ $order['technician'] }}</p>
                                            <p class="text-xs text-gray-500">🕒 {{ $order['days_ago'] }} dia(s) atrás</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p>Nenhuma ordem recente encontrada</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('company.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium transition-colors duration-200">
                                Ver todas as ordens →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
              {{-- ===== ROW 6: PERFORMANCE POR DEPARTAMENTO (FULL WIDTH) ===== --}}
            <div class="mb-8">
                <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Performance por Departamento</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Avaliação média dos funcionários por área</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-3xl">📈</div>
                            <button class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-indigo-600 hover:to-purple-700 transition-all duration-200">
                                Relatório Detalhado
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($dashboardData['charts']['department_performance'] ?? [] as $dept)
                            <div class="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 hover:shadow-lg transition-all duration-200 hover:scale-105">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $dept['department'] }}</h4>
                                    <span class="text-2xl 
                                        {{ $dept['trend'] === 'up' ? 'text-emerald-500' : 
                                           ($dept['trend'] === 'down' ? 'text-red-500' : 'text-gray-400') }}">
                                        {{ $dept['trend'] === 'up' ? '📈' : ($dept['trend'] === 'down' ? '📉' : '➡️') }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $dept['avg_score'] }}%</span>
                                        <span class="text-sm text-gray-500 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                                            {{ $dept['employees'] }} funcionários
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div class="h-3 rounded-full transition-all duration-500 
                                            {{ $dept['avg_score'] >= 90 ? 'bg-gradient-to-r from-emerald-400 to-emerald-600' : 
                                               ($dept['avg_score'] >= 80 ? 'bg-gradient-to-r from-blue-400 to-blue-600' : 
                                               ($dept['avg_score'] >= 70 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 
                                               'bg-gradient-to-r from-red-400 to-red-600')) }}"
                                            style="width: {{ $dept['avg_score'] }}%"></div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium px-3 py-1 rounded-full text-center 
                                    {{ $dept['avg_score'] >= 90 ? 'text-emerald-700 bg-emerald-100' : 
                                       ($dept['avg_score'] >= 80 ? 'text-blue-700 bg-blue-100' : 
                                       ($dept['avg_score'] >= 70 ? 'text-yellow-700 bg-yellow-100' : 
                                       'text-red-700 bg-red-100')) }}">
                                    {{ $dept['avg_score'] >= 90 ? 'Excelente' : 
                                       ($dept['avg_score'] >= 80 ? 'Muito Bom' : 
                                       ($dept['avg_score'] >= 70 ? 'Bom' : 'Precisa Melhorar')) }}
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                <p>Nenhum departamento encontrado</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ===== ROW 7: TOP CLIENTES + ESTATÍSTICAS ADICIONAIS (2 COLUNAS) ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- TOP CLIENTES --}}
                    <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Top 5 Clientes</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Por número de ordens</p>
                            </div>
                            <div class="text-3xl">🏆</div>
                        </div>
                        <div class="space-y-4">
                            @forelse($dashboardData['charts']['top_clients'] ?? [] as $index => $client)
                                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full font-bold text-sm mr-4">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $client['name'] }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $client['orders'] }} ordens</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white">{{ number_format($client['billing'], 2, ',', '.') }} MZN</p>
                                        @if(isset($client['growth']))
                                            <div class="flex items-center text-xs {{ $client['growth'] > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                <span>{{ $client['growth'] > 0 ? '↗️' : '↘️' }}</span>
                                                <span class="ml-1">{{ abs($client['growth']) }}%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p>Nenhum cliente encontrado</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- COMPARAÇÃO DE FATURAÇÃO --}}
                    <div class="bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Comparação de Faturação</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Todos os tipos</p>
                            </div>
                            <div class="text-3xl">📊</div>
                        </div>
                        <div class="space-y-4">
                            @forelse($dashboardData['charts']['billing_comparison'] ?? [] as $billing)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full 
                                            {{ $billing['type'] === 'HH' ? 'bg-blue-500' : 
                                               ($billing['type'] === 'Estimada' ? 'bg-emerald-500' : 
                                               ($billing['type'] === 'Real' ? 'bg-purple-500' : 'bg-amber-500')) }}"></div>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $billing['type'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white">
                                            {{ number_format($billing['mzn'], 2, ',', '.') }} MZN
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            ${{ number_format($billing['usd'], 2, '.', ',') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p>Nenhum dado de faturação disponível</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Scripts para gráficos --}}
    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:init', function () {
            // Gráfico de Evolução Mensal
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: @json(collect($dashboardData['charts']['monthly_orders'] ?? [])->pluck('month')),
                        datasets: [{
                            label: 'Ordens',
                            data: @json(collect($dashboardData['charts']['monthly_orders'] ?? [])->pluck('orders')),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Faturação (mil MZN)',
                            data: @json(collect($dashboardData['charts']['monthly_orders'] ?? [])->pluck('billing_mzn')),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                            }
                        }
                    }
                });
            }
        });

        // Atualizar gráficos quando dados mudarem
        Livewire.on('dashboard-updated', () => {
            // Recriar gráficos se necessário
            location.reload(); // Solução simples - pode ser otimizada
        });
    </script>
    @endsection
</div>