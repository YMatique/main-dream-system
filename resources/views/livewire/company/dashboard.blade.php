<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    
    {{-- CONTAINER PRINCIPAL --}}
    <div class="">
        <div class="max-w-[1600px] mx-auto">
            
            {{-- ===== HEADER MELHORADO ===== --}}
            <div class="mb-8">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard da Empresa</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Visão geral das operações e métricas</p>
                        </div>
                    </div>
                    
                    {{-- FILTROS AVANÇADOS --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select 
                            wire:model.live="selectedPeriod"
                            class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="today">Hoje</option>
                            <option value="last_7_days">Últimos 7 dias</option>
                            <option value="last_30_days">Últimos 30 dias</option>
                            <option value="this_week">Esta semana</option>
                            <option value="current_month">Mês Atual</option>
                            <option value="last_month">Mês Anterior</option>
                            <option value="quarter">Trimestre</option>
                            <option value="year">Ano</option>
                            <option value="custom">Período customizado</option>
                        </select>
                        
                        {{-- CAMPOS DE DATA CUSTOMIZADA --}}
                        @if($selectedPeriod === 'custom')
                            <div class="flex gap-2">
                                <input 
                                    type="date" 
                                    wire:model.live="customStartDate"
                                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                    placeholder="Data inicial"
                                >
                                <input 
                                    type="date" 
                                    wire:model.live="customEndDate"
                                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                    placeholder="Data final"
                                >
                            </div>
                        @endif
                        
                        <button 
                            wire:click="exportReport"
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 shadow-lg transition-all duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Exportar Relatório</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===== WORKFLOW STATUS (NOVO) ===== --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Status do Workflow
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4">
                    {{-- FORM 1 --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-blue-700 dark:text-blue-300">FORM 1</span>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form1'] ?? 0 }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400">Inicial</p>
                    </div>

                    {{-- FORM 2 --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-xl border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-green-700 dark:text-green-300">FORM 2</span>
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form2'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">Técnicos</p>
                    </div>

                    {{-- FORM 3 --}}
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-orange-700 dark:text-orange-300">FORM 3</span>
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form3'] ?? 0 }}</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400">Faturação</p>
                    </div>

                    {{-- FORM 4 --}}
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-purple-700 dark:text-purple-300">FORM 4</span>
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form4'] ?? 0 }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Máquina</p>
                    </div>

                    {{-- FORM 5 --}}
                    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-4 rounded-xl border border-red-200 dark:border-red-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-red-700 dark:text-red-300">FORM 5</span>
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $dashboardData['workflow_metrics']['orders_by_stage']['form5'] ?? 0 }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400">Final</p>
                    </div>

                    {{-- COMPLETAS --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 p-4 rounded-xl border border-emerald-200 dark:border-emerald-700">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-emerald-700 dark:text-emerald-300">COMPLETAS</span>
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ $dashboardData['workflow_metrics']['orders_completed'] ?? 0 }}</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400">Finalizadas</p>
                    </div>

                    {{-- PENDENTES --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800/50 dark:to-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">PENDENTES</span>
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $dashboardData['workflow_metrics']['orders_pending'] ?? 0 }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Em Andamento</p>
                    </div>
                </div>

                {{-- MÉTRICAS ADICIONAIS DO WORKFLOW --}}
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Taxa de Conclusão</span>
                            <span class="text-xs {{ ($dashboardData['workflow_metrics']['completion_rate'] ?? 0) >= 80 ? 'text-green-600 bg-green-100' : 'text-orange-600 bg-orange-100' }} px-2 py-1 rounded-full">
                                {{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%
                            </span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tempo Médio</span>
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">dias</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Criadas Hoje</span>
                            <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">hoje</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['workflow_metrics']['orders_created_today'] ?? 0 }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Ordens</span>
                            <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">período</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dashboardData['metrics']['orders']['current_period'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            {{-- ===== TOP 3 DEPARTAMENTOS (NOVO) ===== --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Top 3 Departamentos por Produtividade
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($dashboardData['top_departments'] ?? [] as $index => $dept)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm
                            {{ $index === 0 ? 'ring-2 ring-yellow-500 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20' : '' }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 
                                        {{ $index === 0 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 
                                           ($index === 1 ? 'bg-gradient-to-r from-gray-400 to-gray-500' : 
                                           'bg-gradient-to-r from-amber-600 to-orange-600') }} 
                                        text-white rounded-full flex items-center justify-center text-lg font-bold shadow-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $dept['name'] }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $dept['total_employees'] }} funcionários</p>
                                    </div>
                                </div>
                                @if($index === 0)
                                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="space-y-3">
                                {{-- SCORE DE PRODUTIVIDADE --}}
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Score Produtividade</span>
                                        <span class="text-2xl font-bold 
                                            {{ $dept['productivity_score'] >= 80 ? 'text-green-600' : 
                                               ($dept['productivity_score'] >= 60 ? 'text-blue-600' : 'text-orange-600') }}">
                                            {{ $dept['productivity_score'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="h-3 rounded-full transition-all duration-500
                                            {{ $dept['productivity_score'] >= 80 ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
                                               ($dept['productivity_score'] >= 60 ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 
                                               'bg-gradient-to-r from-orange-500 to-red-500') }}"
                                            style="width: {{ $dept['productivity_score'] }}%"></div>
                                    </div>
                                </div>

                                {{-- MÉTRICAS DETALHADAS --}}
                                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Performance</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dept['avg_performance'] }}%</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Horas/Funcionário</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dept['hours_per_employee'] }}h</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Horas</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dept['total_hours'] }}h</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Ordens</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $dept['orders_worked'] }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- BADGE DE CLASSIFICAÇÃO --}}
                            <div class="mt-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $dept['productivity_score'] >= 90 ? 'text-green-700 bg-green-100 dark:bg-green-900/20 dark:text-green-400' : 
                                       ($dept['productivity_score'] >= 80 ? 'text-blue-700 bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400' : 
                                       ($dept['productivity_score'] >= 70 ? 'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400' : 
                                       'text-red-700 bg-red-100 dark:bg-red-900/20 dark:text-red-400')) }}">
                                    {{ $dept['productivity_score'] >= 90 ? '🏆 Excelente' : 
                                       ($dept['productivity_score'] >= 80 ? '🥈 Muito Bom' : 
                                       ($dept['productivity_score'] >= 70 ? '🥉 Bom' : '📈 Precisa Melhorar')) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">Nenhum departamento encontrado</p>
                            <p class="text-xs">Configure departamentos para ver as métricas</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ===== FATURAÇÃO DETALHADA COM MATERIAIS ===== --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Faturação Detalhada
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    
                    {{-- FATURAÇÃO HH --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Faturação HH</h3>
                            <span class="bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 text-xs px-2 py-1 rounded-full font-medium">
                                {{ $dashboardData['metrics']['billing']['hh']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['hh']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['hh']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-xs text-center">
                                    <span class="text-gray-500">Preço Sistema</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO ESTIMADA --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Faturação Estimada</h3>
                            <span class="bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">
                                {{ $dashboardData['metrics']['billing']['estimated']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['metrics']['billing']['estimated']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($dashboardData['metrics']['billing']['estimated']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-xs text-center">
                                    <span class="text-gray-500">Preço Ajustável</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO REAL --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm ring-2 ring-green-500 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                Faturação Real
                                <svg class="w-4 h-4 ml-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </h3>
                            <span class="bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 text-xs px-2 py-1 rounded-full font-medium">
                                {{ $dashboardData['metrics']['billing']['real']['count'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">MZN:</span>
                                <span class="font-bold text-green-900 dark:text-green-100">
                                    {{ number_format($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">USD:</span>
                                <span class="font-bold text-green-900 dark:text-green-100">
                                    ${{ number_format($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0, 0, '.', ',') }}
                                </span>
                            </div>
                            <div class="pt-2 border-t border-green-200 dark:border-green-700">
                                <div class="text-xs text-center">
                                    <span class="text-green-600 font-medium">Receita Confirmada</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FATURAÇÃO DE MATERIAIS DETALHADA --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Materiais</h3>
                            <span class="bg-amber-100 dark:bg-amber-900/20 text-amber-800 dark:text-amber-400 text-xs px-2 py-1 rounded-full font-medium">
                                {{ $dashboardData['materials_breakdown']['summary']['total_materials_types'] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Cadastrados:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['materials_breakdown']['totals']['registered_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Adicionais:</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboardData['materials_breakdown']['totals']['additional_mzn'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between font-bold">
                                    <span class="text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-amber-600">{{ number_format($dashboardData['materials_breakdown']['totals']['grand_total_mzn'] ?? 0, 0, ',', '.') }} MZN</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RESUMO FINANCEIRO TOTAL --}}
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl border border-indigo-200 dark:border-indigo-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-indigo-900 dark:text-indigo-100">💰 Total Geral</h3>
                            <span class="bg-indigo-100 dark:bg-indigo-900/20 text-indigo-800 dark:text-indigo-400 text-xs px-2 py-1 rounded-full font-medium">
                                SOMA
                            </span>
                        </div>
                        @php
                            $totalMzn = ($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) + 
                                       ($dashboardData['materials_breakdown']['totals']['grand_total_mzn'] ?? 0);
                            $totalUsd = ($dashboardData['metrics']['billing']['real']['total_usd'] ?? 0);
                        @endphp
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-indigo-700 dark:text-indigo-300">MZN:</span>
                                <span class="font-bold text-lg text-indigo-900 dark:text-indigo-100">
                                    {{ number_format($totalMzn, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-indigo-700 dark:text-indigo-300">USD:</span>
                                <span class="font-bold text-lg text-indigo-900 dark:text-indigo-100">
                                    ${{ number_format($totalUsd, 0, '.', ',') }}
                                </span>
                            </div>
                            <div class="pt-2 border-t border-indigo-200 dark:border-indigo-700">
                                <div class="text-xs text-center">
                                    <span class="text-indigo-600 font-medium">Receita + Materiais</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== MATERIAIS BREAKDOWN DETALHADO ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- TOP MATERIAIS CADASTRADOS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Materiais Cadastrados</h3>
                            <span class="text-sm text-gray-500">{{ $dashboardData['materials_breakdown']['totals']['registered_percentage'] ?? 0 }}% do total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['materials_breakdown']['registered'] ?? [] as $material)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $material->name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ number_format($material->total_qty, 1) }} {{ $material->unit }} • {{ $material->orders_count }} ordens
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                                            {{ number_format($material->total_mzn, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500">MZN</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <p class="text-sm">Nenhum material utilizado</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TOP MATERIAIS ADICIONAIS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Materiais Adicionais</h3>
                            <span class="text-sm text-gray-500">{{ $dashboardData['materials_breakdown']['totals']['additional_percentage'] ?? 0 }}% do total</span>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['materials_breakdown']['additional'] ?? [] as $material)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $material->nome_material }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ number_format($material->total_qty, 1) }} unid. • {{ number_format($material->avg_unit_cost, 2) }} MZN/unid • {{ $material->orders_count }} ordens
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                                            {{ number_format($material->total_cost, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500">MZN</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <p class="text-sm">Nenhum material adicional</p>
                                </div>
                            @endforelse
                        </div>

                        @if($dashboardData['materials_breakdown']['totals']['additional_percentage'] > 30)
                            <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                        Alto uso de materiais não cadastrados ({{ $dashboardData['materials_breakdown']['totals']['additional_percentage'] }}%)
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===== GRÁFICOS E DADOS EXISTENTES ===== --}}
            <div class="mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    {{-- EVOLUÇÃO MENSAL --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
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

                    {{-- ORDENS RECENTES MELHORADAS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ordens Recentes</h3>
                            <a href="{{ route('company.orders.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Ver todas →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse($dashboardData['recent_orders'] ?? [] as $order)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $order['id'] }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $order['status'] === 'Concluída' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 
                                                   ($order['status'] === 'Em Andamento' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : 
                                                   'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400') }}">
                                                {{ $order['status'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                            <span class="font-medium">{{ $order['client'] }}</span>
                                        </p>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $order['technician'] }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $order['days_ago'] }} dia(s)
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs
                                                {{ $order['priority'] === 'high' ? 'bg-red-100 text-red-800' : 
                                                   ($order['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($order['priority']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Nenhuma ordem recente</p>
                                    <p class="text-xs">Crie uma nova ordem para começar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== CENTRO DE COMANDO OPERACIONAL (NOVO) ===== --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Centro de Comando Operacional
                        </h3>
                        <div class="flex space-x-2">
                            <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Relatórios</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- SITUAÇÃO ATUAL --}}
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <h4 class="font-semibold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Situação Atual
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Ordens Ativas:</span>
                                    <span class="font-bold">{{ $dashboardData['workflow_metrics']['orders_pending'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Funcionários Ativos:</span>
                                    <span class="font-bold">{{ $dashboardData['metrics']['employees']['total_active'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Taxa Conclusão:</span>
                                    <span class="font-bold">{{ $dashboardData['workflow_metrics']['completion_rate'] ?? 0 }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tempo Médio:</span>
                                    <span class="font-bold">{{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }} dias</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- AÇÕES URGENTES --}}
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <h4 class="font-semibold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Ações Urgentes
                            </h4>
                            <div class="space-y-2">
                                @if(count($dashboardData['alerts'] ?? []) > 0)
                                    @foreach($dashboardData['alerts'] as $alert)
                                        @if($alert['type'] === 'warning')
                                            <div class="flex items-center justify-between p-2 bg-red-500/20 rounded border-l-4 border-red-400">
                                                <span class="text-sm">{{ str_replace($alert['count'], '', $alert['message']) }}</span>
                                                <span class="bg-red-400 text-white px-2 py-1 rounded text-xs font-bold">{{ $alert['count'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-2">
                                        <div class="text-green-300 text-sm">✅ Tudo em ordem</div>
                                        <div class="text-xs text-white/70">Nenhuma ação urgente necessária</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- MÉTRICAS DE PERFORMANCE --}}
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <h4 class="font-semibold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Performance Global
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Performance Média:</span>
                                        <span class="font-bold">{{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-green-400 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $dashboardData['metrics']['employees']['avg_performance'] ?? 0 }}%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Receita MZN:</span>
                                        <span class="font-bold">{{ number_format(($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) / 1000, 0) }}k</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ min(100, ($dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0) / 10000) }}%"></div>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-white/20">
                                    <div class="flex justify-between text-xs">
                                        <span>Avaliações Pendentes:</span>
                                        <span class="font-bold">{{ $dashboardData['metrics']['employees']['evaluations_pending'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SEÇÃO DE INSIGHTS E PREVISÕES ===== --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Insights Inteligentes
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    
                    {{-- INSIGHT 1: EFICIÊNCIA --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 dark:text-blue-100">Eficiência</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Tempo vs Meta</p>
                            </div>
                        </div>
                        @php
                            $efficiency = ($dashboardData['workflow_metrics']['avg_completion_time'] ?? 0) <= 5 ? 'Excelente' : 
                                         (($dashboardData['workflow_metrics']['avg_completion_time'] ?? 0) <= 10 ? 'Boa' : 'Precisa melhorar');
                            $efficiencyColor = $efficiency === 'Excelente' ? 'text-green-600' : 
                                              ($efficiency === 'Boa' ? 'text-blue-600' : 'text-orange-600');
                        @endphp
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Tempo médio de {{ $dashboardData['workflow_metrics']['avg_completion_time'] ?? 0 }} dias está 
                            <span class="font-bold {{ $efficiencyColor }}">{{ strtolower($efficiency) }}</span>
                        </p>
                        <div class="text-xs text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded">
                            💡 {{ $efficiency === 'Excelente' ? 'Continue assim!' : ($efficiency === 'Boa' ? 'Manter consistência' : 'Revisar processos') }}
                        </div>
                    </div>

                    {{-- INSIGHT 2: MATERIAIS --}}
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl border border-amber-200 dark:border-amber-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-amber-900 dark:text-amber-100">Materiais</h4>
                                <p class="text-sm text-amber-700 dark:text-amber-300">Cadastro vs Adicional</p>
                            </div>
                        </div>
                        @php
                            $additionalPercent = $dashboardData['materials_breakdown']['totals']['additional_percentage'] ?? 0;
                            $materialInsight = $additionalPercent > 40 ? 'Alto uso não cadastrado' : 
                                             ($additionalPercent > 20 ? 'Uso moderado não cadastrado' : 'Boa gestão de estoque');
                        @endphp
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ $additionalPercent }}% são materiais não cadastrados
                        </p>
                        <div class="text-xs text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded">
                            💡 {{ $additionalPercent > 30 ? 'Considere cadastrar materiais frequentes' : 'Controle adequado de materiais' }}
                        </div>
                    </div>

                    {{-- INSIGHT 3: DEPARTAMENTOS --}}
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-green-900 dark:text-green-100">Departamentos</h4>
                                <p class="text-sm text-green-700 dark:text-green-300">Performance Global</p>
                            </div>
                        </div>
                        @php
                            $topDept = $dashboardData['top_departments'][0] ?? null;
                            $deptScore = $topDept['productivity_score'] ?? 0;
                        @endphp
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            @if($topDept)
                                <span class="font-bold">{{ $topDept['name'] }}</span> lidera com {{ $deptScore }}%
                            @else
                                Nenhum departamento ativo
                            @endif
                        </p>
                        <div class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded">
                            💡 {{ $deptScore >= 80 ? 'Excelente liderança!' : 'Oportunidade de melhoria' }}
                        </div>
                    </div>

                    {{-- INSIGHT 4: TENDÊNCIA --}}
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl border border-purple-200 dark:border-purple-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-purple-900 dark:text-purple-100">Tendência</h4>
                                <p class="text-sm text-purple-700 dark:text-purple-300">Próximo Período</p>
                            </div>
                        </div>
                        @php
                            $changePercent = $dashboardData['metrics']['orders']['percentage_change'] ?? 0;
                            $trend = $changePercent > 10 ? 'Crescimento forte' : 
                                    ($changePercent > 0 ? 'Crescimento moderado' : 
                                    ($changePercent > -10 ? 'Estável' : 'Declínio'));
                        @endphp
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ $changePercent > 0 ? '+' : '' }}{{ $changePercent }}% vs período anterior
                        </p>
                        <div class="text-xs text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-2 py-1 rounded">
                            💡 {{ $trend }} - {{ $changePercent > 0 ? 'Preparar recursos' : 'Revisar estratégias' }}
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
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        }, {
                            label: 'Faturação (mil MZN)',
                            data: @json(collect($dashboardData['charts']['monthly_orders'] ?? [])->pluck('billing_mzn')),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#10B981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
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
                                    padding: 20,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: true,
                                callbacks: {
                                    title: function(context) {
                                        return `Mês: ${context[0].label}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        weight: 'bold'
                                    }
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

        // Animação de números contadores
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.innerHTML = value.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Executar animações quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Animar valores grandes
            setTimeout(() => {
                const numberElements = document.querySelectorAll('[data-animate]');
                numberElements.forEach(el => {
                    const endValue = parseInt(el.getAttribute('data-animate'));
                    if (endValue > 0) {
                        animateValue(el, 0, endValue, 1500);
                    }
                });
            }, 300);
        });
    </script>
    @endsection
</div>