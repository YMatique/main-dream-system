<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    
    {{-- CONTAINER PRINCIPAL --}}
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-[1600px] mx-auto">
            
            {{-- ===== HEADER ===== --}}
            <div class="mb-8">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-600 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard da Empresa</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visão geral das operações e métricas</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select 
                            wire:model.live="selectedPeriod"
                            class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="current_month">Mês Atual</option>
                            <option value="last_month">Mês Anterior</option>
                            <option value="quarter">Trimestre</option>
                            <option value="year">Ano</option>
                        </select>
                        <button 
                            wire:click="exportReport"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Exportar Relatório</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===== ALERTAS ===== --}}
            @if(!empty($dashboardData['alerts']))
                <div class="mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        @foreach($dashboardData['alerts'] as $alert)
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 
                                {{ $alert['type'] === 'warning' ? 'border-l-4 border-l-amber-500' : 
                                   ($alert['type'] === 'info' ? 'border-l-4 border-l-blue-500' : 
                                   'border-l-4 border-l-green-500') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 rounded-lg 
                                            {{ $alert['type'] === 'warning' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400' : 
                                               ($alert['type'] === 'info' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 
                                               'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400') }}">
                                            <span class="text-sm">{{ $alert['icon'] }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $alert['message'] }}</p>
                                    </div>
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $alert['count'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== MÉTRICAS PRINCIPAIS ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    
                    {{-- ORDENS DE REPARAÇÃO --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ordens do Período</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['orders']['current_period'] ?? 0 }}</p>
                            <div class="flex items-center text-sm">
                                @php
                                    $change = $dashboardData['metrics']['orders']['percentage_change'] ?? 0;
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $change > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 
                                       'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                    {{ $change > 0 ? '+' : '' }}{{ $change }}%
                                </span>
                                <span class="text-gray-500 ml-2">vs anterior</span>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO TOTAL --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Faturação Total</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                {{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 2, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mb-2">MZN</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                ${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 2, '.', ',') }}
                            </p>
                        </div>
                    </div>

                    {{-- FUNCIONÁRIOS ATIVOS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Funcionários Ativos</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['employees']['total_active'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500">{{ $dashboardData['metrics']['employees']['total_inactive'] ?? 0 }} inativos</p>
                        </div>
                    </div>

                    {{-- PERFORMANCE MÉDIA --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-amber-100 dark:bg-amber-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Performance Média</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%</p>
                            <p class="text-sm text-gray-500">{{ $dashboardData['metrics']['employees']['evaluations_pending'] ?? 0 }} avaliações pendentes</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== FATURAÇÃO DETALHADA ===== --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Faturação por Tipo</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    
                    {{-- FATURAÇÃO HH --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Faturação HH</h3>
                            <span class="bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 text-xs px-2 py-1 rounded-full">
                                {{ $dashboardData['metrics']['billing']['hh']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['hh']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['hh']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO ESTIMADA --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Faturação Estimada</h3>
                            <span class="bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 text-xs px-2 py-1 rounded-full">
                                {{ $dashboardData['metrics']['billing']['estimated']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['estimated']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['estimated']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO REAL --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Faturação Real</h3>
                            <span class="bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-400 text-xs px-2 py-1 rounded-full">
                                {{ $dashboardData['metrics']['billing']['real']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO DE MATERIAIS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Materiais</h3>
                            <span class="bg-amber-100 dark:bg-amber-900/20 text-amber-800 dark:text-amber-400 text-xs px-2 py-1 rounded-full">
                                {{ $dashboardData['metrics']['billing']['materials']['orders_count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['materials']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['materials']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== GRÁFICOS E DADOS ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- EVOLUÇÃO MENSAL --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Evolução Mensal</h3>
                            <div class="flex items-center space-x-4 text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Ordens</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Faturação</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-[300px]">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    {{-- ORDENS RECENTES --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ordens Recentes</h3>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['recent_orders'] ?? [] as $order)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $order['id'] }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $order['status'] === 'Concluída' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 
                                                   ($order['status'] === 'Em Andamento' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : 
                                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400') }}">
                                                {{ $order['status'] }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ $order['client'] }}</p>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $order['technician'] }}</span>
                                            <span>{{ $order['days_ago'] }} dia(s) atrás</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <p class="text-sm">Nenhuma ordem recente encontrada</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4 text-center border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('company.orders.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Ver todas as ordens →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== PERFORMANCE POR DEPARTAMENTO ===== --}}
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance por Departamento</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @forelse($dashboardData['charts']['department_performance'] ?? [] as $dept)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm">{{ $dept['department'] }}</h4>
                                    <span class="text-xs text-gray-500 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">
                                        {{ $dept['employees'] }} funcionários
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dept['avg_score'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            {{ $dept['avg_score'] >= 90 ? 'bg-green-500' : 
                                               ($dept['avg_score'] >= 80 ? 'bg-blue-500' : 
                                               ($dept['avg_score'] >= 70 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                            style="width: {{ $dept['avg_score'] }}%"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-center px-2 py-1 rounded 
                                    {{ $dept['avg_score'] >= 90 ? 'text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400' : 
                                       ($dept['avg_score'] >= 80 ? 'text-blue-700 bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400' : 
                                       ($dept['avg_score'] >= 70 ? 'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400' : 
                                       'text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400')) }}">
                                    {{ $dept['avg_score'] >= 90 ? 'Excelente' : 
                                       ($dept['avg_score'] >= 80 ? 'Muito Bom' : 
                                       ($dept['avg_score'] >= 70 ? 'Bom' : 'Precisa Melhorar')) }}
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-6 text-gray-500">
                                <p class="text-sm">Nenhum departamento encontrado</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ===== TOP CLIENTES E COMPARAÇÃO ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- TOP CLIENTES --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 5 Clientes</h3>
                            <span class="text-sm text-gray-500">Por número de ordens</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['charts']['top_clients'] ?? [] as $index => $client)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-medium">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $client['name'] }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $client['orders'] }} ordens</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">
                                            {{ number_format($client['billing'], 0, ',', '.') }} MZN
                                        </p>
                                        @if(isset($client['growth']))
                                            <div class="text-xs {{ $client['growth'] > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                                {{ $client['growth'] > 0 ? '+' : '' }}{{ $client['growth'] }}%
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <p class="text-sm">Nenhum cliente encontrado</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- COMPARAÇÃO DE FATURAÇÃO --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Comparação de Faturação</h3>
                            <span class="text-sm text-gray-500">Todos os tipos</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['charts']['billing_comparison'] ?? [] as $billing)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full 
                                            {{ $billing['type'] === 'HH' ? 'bg-blue-500' : 
                                               ($billing['type'] === 'Estimada' ? 'bg-green-500' : 
                                               ($billing['type'] === 'Real' ? 'bg-purple-500' : 'bg-amber-500')) }}"></div>
                                        <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $billing['type'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">
                                            {{ number_format($billing['mzn'], 0, ',', '.') }} MZN
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            ${{ number_format($billing['usd'], 0, '.', ',') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <p class="text-sm">Nenhum dado de faturação disponível</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- SEÇÃO 1: INDICADORES OPERACIONAIS -->
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Indicadores Operacionais</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        
        <!-- TEMPO MÉDIO DE RESOLUÇÃO -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">-15%</span>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tempo Médio Resolução</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">4.2h</p>
                <p class="text-xs text-gray-500">Meta: 5.0h</p>
            </div>
        </div>

        <!-- TAXA DE CONCLUSÃO NO PRAZO -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">+3%</span>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Conclusão no Prazo</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">87%</p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 87%"></div>
                </div>
            </div>
        </div>

        <!-- EFICIÊNCIA DE TÉCNICOS -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">-2%</span>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Eficiência Técnicos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">92%</p>
                <p class="text-xs text-gray-500">Horas planejadas vs reais</p>
            </div>
        </div>

        <!-- TAXA DE RETRABALHO -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">+1%</span>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Taxa Retrabalho</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">3.2%</p>
                <p class="text-xs text-gray-500">Meta: &lt;2%</p>
            </div>
        </div>
    </div>
</div>

<!-- SEÇÃO 2: ANÁLISE DE MÁQUINAS E EQUIPAMENTOS -->
<div class="mb-8">
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- TOP MÁQUINAS COM MAIS FALHAS -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Máquinas com Mais Falhas</h3>
                <span class="text-sm text-gray-500">Últimos 30 dias</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">MAQ-001</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Setor A - Linha 1</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">12 falhas</p>
                        <p class="text-xs text-red-600">Crítico</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">MAQ-007</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Setor B - Linha 2</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">8 falhas</p>
                        <p class="text-xs text-yellow-600">Atenção</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">MAQ-003</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Setor A - Linha 3</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">5 falhas</p>
                        <p class="text-xs text-blue-600">Normal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- DISPONIBILIDADE DE EQUIPAMENTOS -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Disponibilidade por Setor</h3>
                <span class="text-sm text-gray-500">Tempo real</span>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Setor A</span>
                        <span class="text-sm text-green-600 font-medium">94.2%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 94.2%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Setor B</span>
                        <span class="text-sm text-yellow-600 font-medium">87.8%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: 87.8%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Setor C</span>
                        <span class="text-sm text-red-600 font-medium">76.5%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-500 h-3 rounded-full" style="width: 76.5%"></div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-blue-800 dark:text-blue-200">Meta global: 90%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEÇÃO 3: ANÁLISE FINANCEIRA AVANÇADA -->
<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Análise de Custos por Tipo de Manutenção</h3>
            <div class="flex space-x-2">
                <button class="bg-blue-100 text-blue-800 px-3 py-1 rounded-lg text-sm">Preventiva</button>
                <button class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm">Corretiva</button>
                <button class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm">Preditiva</button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="h-32 w-32 mx-auto mb-4">
                    <canvas id="maintenanceTypeChart"></canvas>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Distribuição de Custos</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Preventiva:</span>
                    <span class="font-medium text-gray-900 dark:text-white">45%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Corretiva:</span>
                    <span class="font-medium text-gray-900 dark:text-white">40%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Preditiva:</span>
                    <span class="font-medium text-gray-900 dark:text-white">15%</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Economia Potencial</p>
                    <p class="text-lg font-bold text-green-900 dark:text-green-100">35,000 MZN</p>
                    <p class="text-xs text-green-600 dark:text-green-400">Aumentando preventiva</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEÇÃO 4: PREVISÕES E TENDÊNCIAS -->
<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Previsões para Próximo Mês</h3>
            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">IA Preditiva</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">145</p>
                <p class="text-sm text-blue-700 dark:text-blue-300">Ordens Previstas</p>
                <p class="text-xs text-blue-600 dark:text-blue-400">+12% vs atual</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <p class="text-2xl font-bold text-green-900 dark:text-green-100">850k</p>
                <p class="text-sm text-green-700 dark:text-green-300">Faturação MZN</p>
                <p class="text-xs text-green-600 dark:text-green-400">+8% vs atual</p>
            </div>
            
            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">MAQ-001</p>
                <p class="text-sm text-yellow-700 dark:text-yellow-300">Risco de Falha</p>
                <p class="text-xs text-yellow-600 dark:text-yellow-400">85% probabilidade</p>
            </div>
            
            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">15h</p>
                <p class="text-sm text-purple-700 dark:text-purple-300">Tempo Médio</p>
                <p class="text-xs text-purple-600 dark:text-purple-400">-20% vs atual</p>
            </div>
        </div>
    </div>
</div>

<!-- SEÇÃO 5: CENTRO DE COMANDO EXECUTIVO -->
<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Centro de Comando</h3>
            <div class="flex space-x-2">
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Nova Ordem</span>
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Relatório Executivo</span>
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- AÇÕES URGENTES -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Ações Urgentes</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-2 bg-red-50 dark:bg-red-900/20 rounded border-l-4 border-red-500">
                        <span class="text-sm text-red-800 dark:text-red-200">MAQ-001 parada há 4h</span>
                        <button class="text-red-600 hover:text-red-800 text-xs">Atribuir</button>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded border-l-4 border-yellow-500">
                        <span class="text-sm text-yellow-800 dark:text-yellow-200">Material em falta: Motor XYZ</span>
                        <button class="text-yellow-600 hover:text-yellow-800 text-xs">Comprar</button>
                    </div>
                </div>
            </div>
            
            <!-- STATUS DA EQUIPE -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Status da Equipe</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Disponíveis:</span>
                        <span class="font-medium text-green-600">8 técnicos</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Em campo:</span>
                        <span class="font-medium text-blue-600">12 técnicos</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Sobrecarregados:</span>
                        <span class="font-medium text-red-600">2 técnicos</span>
                    </div>
                </div>
            </div>
            
            <!-- METAS DO MÊS -->
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Metas do Mês</h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Ordens:</span>
                            <span class="font-medium">87/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 87%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Faturação:</span>
                            <span class="font-medium">92/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
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
                            borderWidth: 2,
                            fill: false,
                            tension: 0.1
                        }, {
                            label: 'Faturação (mil MZN)',
                            data: @json(collect($dashboardData['charts']['monthly_orders'] ?? [])->pluck('billing_mzn')),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.1,
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
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280'
                                }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                grid: {
                                    color: 'rgba(107, 114, 128, 0.1)'
                                },
                                ticks: {
                                    color: '#6B7280'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    color: '#6B7280'
                                }
                            }
                        },
                        elements: {
                            point: {
                                radius: 4,
                                hoverRadius: 6
                            }
                        }
                    }
                });
            }
        });

        // Atualizar gráficos quando dados mudarem
        Livewire.on('dashboard-updated', () => {
            location.reload();
        });
    </script>
    @endsection
</div>