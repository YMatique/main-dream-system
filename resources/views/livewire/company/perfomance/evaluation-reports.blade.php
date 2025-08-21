<div x-data="{ showFilters: false }">
    <!-- Header Melhorado -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Relatórios de Avaliação
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Análise detalhada do desempenho dos funcionários
                    </p>
                </div>

                <!-- Actions Agrupadas -->
                <div class="flex items-center gap-3 mt-4 lg:mt-0">
                    <!-- Período -->
                    <select wire:model.live="period" 
                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm font-medium">
                        <option value="last_month">Último Mês</option>
                        <option value="last_3_months">Últimos 3 Meses</option>
                        <option value="last_6_months">Últimos 6 Meses</option>
                        <option value="last_year">Último Ano</option>
                        <option value="custom">Personalizado</option>
                    </select>

                    <!-- Export -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Exportar
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                            <div class="py-2">
                                <button wire:click="$set('exportFormat', 'xlsx'); exportReport(); open = false" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    📊 Excel (.xlsx)
                                </button>
                                <button wire:click="$set('exportFormat', 'csv'); exportReport(); open = false" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    📄 CSV (.csv)
                                </button>
                                <button wire:click="$set('exportFormat', 'pdf'); exportReport(); open = false" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    📋 PDF (.pdf)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Tipo de Relatório -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-wrap bg-gray-100 dark:bg-gray-700 rounded-lg p-1 mb-6">
            <button wire:click="$set('reportType', 'overview')" 
                    class="flex-1 min-w-0 px-4 py-3 rounded-md text-sm font-medium transition-colors
                           {{ $reportType === 'overview' ? 'bg-white dark:bg-gray-800 shadow text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-lg">📋</span>
                    <span class="hidden sm:inline">Overview</span>
                </div>
            </button>
            
            <button wire:click="$set('reportType', 'performance')" 
                    class="flex-1 min-w-0 px-4 py-3 rounded-md text-sm font-medium transition-colors
                           {{ $reportType === 'performance' ? 'bg-white dark:bg-gray-800 shadow text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-lg">🏆</span>
                    <span class="hidden sm:inline">Performance</span>
                </div>
            </button>
            
            <button wire:click="$set('reportType', 'department')" 
                    class="flex-1 min-w-0 px-4 py-3 rounded-md text-sm font-medium transition-colors
                           {{ $reportType === 'department' ? 'bg-white dark:bg-gray-800 shadow text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-lg">🏢</span>
                    <span class="hidden sm:inline">Departamentos</span>
                </div>
            </button>
            
            <button wire:click="$set('reportType', 'employee')" 
                    class="flex-1 min-w-0 px-4 py-3 rounded-md text-sm font-medium transition-colors
                           {{ $reportType === 'employee' ? 'bg-white dark:bg-gray-800 shadow text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-lg">👤</span>
                    <span class="hidden sm:inline">Funcionário</span>
                </div>
            </button>
            
            <button wire:click="$set('reportType', 'trends')" 
                    class="flex-1 min-w-0 px-4 py-3 rounded-md text-sm font-medium transition-colors
                           {{ $reportType === 'trends' ? 'bg-white dark:bg-gray-800 shadow text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-lg">📈</span>
                    <span class="hidden sm:inline">Tendências</span>
                </div>
            </button>
        </div>

        <!-- Filtros Secundários (Collapsible) -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <button @click="showFilters = !showFilters" 
                    class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mb-3">
                <svg class="w-4 h-4 transition-transform" :class="showFilters ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span x-show="!showFilters">Mostrar Filtros Avançados</span>
                <span x-show="showFilters">Ocultar Filtros</span>
            </button>
            
            <div x-show="showFilters" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 max-h-0"
                 x-transition:enter-end="opacity-100 max-h-96"
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 overflow-hidden">
                
                <!-- Departamento -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Departamento</label>
                    <select wire:model.live="departmentFilter" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Funcionário -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Funcionário</label>
                    <select wire:model.live="employeeFilter" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        <option value="approved">Aprovado</option>
                        <option value="submitted">Submetido</option>
                        <option value="rejected">Rejeitado</option>
                    </select>
                </div>

                <!-- Performance -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Performance</label>
                    <select wire:model.live="performanceFilter" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="all">Todas</option>
                        <option value="excellent">Excelente (≥90%)</option>
                        <option value="good">Bom (70-89%)</option>
                        <option value="satisfactory">Satisfatório (50-69%)</option>
                        <option value="poor">Péssimo (<50%)</option>
                    </select>
                </div>
            </div>

            <!-- Data Customizada (se período = custom) -->
            @if ($period === 'custom')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Data Início</label>
                        <input type="date" wire:model.live="startDate" 
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Data Fim</label>
                        <input type="date" wire:model.live="endDate" 
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading.delay class="flex justify-center items-center py-12">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm text-blue-600 dark:text-blue-400">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Gerando relatório...
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div wire:loading.remove>
        <!-- OVERVIEW REPORT -->
        @if ($reportType === 'overview')
            <div class="space-y-6">
                <!-- Statistics Cards Interativas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total de Avaliações -->
                    <div wire:click="filterQuick('all')" 
                         class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white cursor-pointer transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total de Avaliações</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['total_evaluations'] ?? 0 }}</p>
                                @if(isset($stats['growth_rate']))
                                    <div class="flex items-center mt-2">
                                        @if($stats['growth_rate'] > 0)
                                            <svg class="w-3 h-3 text-green-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg>
                                            <span class="text-xs text-green-300">+{{ $stats['growth_rate'] }}%</span>
                                        @elseif($stats['growth_rate'] < 0)
                                            <svg class="w-3 h-3 text-red-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                            <span class="text-xs text-red-300">{{ $stats['growth_rate'] }}%</span>
                                        @else
                                            <span class="text-xs text-blue-300">Estável</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Aprovadas -->
                    <div wire:click="filterQuick('approved')" 
                         class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white cursor-pointer transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Aprovadas</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['approved_evaluations'] ?? 0 }}</p>
                                <div class="mt-2">
                                    <span class="text-xs bg-green-400 text-white px-2 py-1 rounded-full">
                                        {{ $stats['total_evaluations'] > 0 ? round(($stats['approved_evaluations'] / $stats['total_evaluations']) * 100, 1) : 0 }}% do total
                                    </span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pendentes -->
                    <div wire:click="filterQuick('submitted')" 
                         class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white cursor-pointer transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">Pendentes</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['pending_evaluations'] ?? 0 }}</p>
                                @if(($stats['pending_evaluations'] ?? 0) > 0)
                                    <div class="mt-2">
                                        <span class="text-xs bg-yellow-400 text-white px-2 py-1 rounded-full animate-pulse">
                                            Requer atenção
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Média -->
                    <div wire:click="filterQuick('performance')" 
                         class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white cursor-pointer transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Performance Média</p>
                                <p class="text-3xl font-bold mt-1">{{ $stats['average_performance'] ?? 0 }}%</p>
                                <div class="mt-2">
                                    @if(($stats['average_performance'] ?? 0) >= 80)
                                        <span class="text-xs bg-green-400 text-white px-2 py-1 rounded-full">Excelente</span>
                                    @elseif(($stats['average_performance'] ?? 0) >= 70)
                                        <span class="text-xs bg-blue-400 text-white px-2 py-1 rounded-full">Bom</span>
                                    @elseif(($stats['average_performance'] ?? 0) >= 50)
                                        <span class="text-xs bg-yellow-400 text-white px-2 py-1 rounded-full">Regular</span>
                                    @else
                                        <span class="text-xs bg-red-400 text-white px-2 py-1 rounded-full">Crítico</span>
                                    @endif
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Distribution Chart -->
                @if (isset($chartData['performance_chart']))
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribuição de Performance</h3>
                            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Período:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $period)) }}
                                </span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                @endif

                <!-- Department Performance Table - Responsiva -->
                @if (isset($reportData['department_performance']) && $reportData['department_performance']->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance por Departamento</h3>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Departamento
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Avaliações
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Performance Média
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Abaixo do Limite
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['department_performance'] as $dept)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" 
                                            wire:click="filterByDepartment('{{ $dept['department'] }}')">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $dept['department'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $dept['total_evaluations'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span class="mr-2 font-semibold">{{ number_format($dept['average_performance'], 1) }}%</span>
                                                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                             style="width: {{ $dept['average_performance'] }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if ($dept['below_threshold'] > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                        {{ $dept['below_threshold'] }}
                                                    </span>
                                                @else
                                                    <span class="text-green-600 dark:text-green-400 font-medium">0</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden p-4 space-y-4">
                            @foreach ($reportData['department_performance'] as $dept)
                                <div wire:click="filterByDepartment('{{ $dept['department'] }}')" 
                                     class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $dept['department'] }}</h4>
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($dept['average_performance'], 1) }}%
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div class="text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Avaliações:</span> {{ $dept['total_evaluations'] }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Críticas:</span> 
                                            @if ($dept['below_threshold'] > 0)
                                                <span class="text-red-600 dark:text-red-400 font-medium">{{ $dept['below_threshold'] }}</span>
                                            @else
                                                <span class="text-green-600 dark:text-green-400 font-medium">0</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $dept['average_performance'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        <!-- PERFORMANCE REPORT -->
        @elseif($reportType === 'performance')
            <div class="space-y-6">
                <!-- Performance Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Maior Pontuação</div>
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['highest_score'] ?? 0 }}%</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Menor Pontuação</div>
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['lowest_score'] ?? 0 }}%</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Mediana</div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['median_score'] ?? 0 }}%</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Desvio Padrão</div>
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['std_deviation'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                @if (isset($reportData['top_performers']) && $reportData['top_performers']->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-green-600 dark:text-green-400 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                Top 10 Melhores Performances
                            </h3>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posição</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Funcionário</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Departamento</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['top_performers'] as $index => $evaluation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if ($index < 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-white font-bold
                                                        {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                                        {{ $index + 1 }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 font-bold">
                                                        {{ $index + 1 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $evaluation->employee->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $evaluation->employee->department->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-bold text-green-600 dark:text-green-400 mr-2">
                                                        {{ $evaluation->final_percentage }}%
                                                    </span>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        {{ $evaluation->performance_class }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $evaluation->evaluation_period->format('m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden p-4 space-y-4">
                            @foreach ($reportData['top_performers'] as $index => $evaluation)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            @if ($index < 3)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-white font-bold text-sm
                                                    {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 font-bold text-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $evaluation->employee->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $evaluation->employee->department->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $evaluation->final_percentage }}%</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $evaluation->evaluation_period->format('m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        <!-- DEPARTMENT REPORT -->
        @elseif($reportType === 'department')
            <div class="space-y-6">
                @if (isset($reportData['departments']) && $reportData['departments']->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Relatório por Departamento
                            </h3>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Departamento</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Funcionários</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avaliados</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance Média</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distribuição</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['departments'] as $dept)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $dept['name'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $dept['total_employees'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $dept['evaluated_employees'] }} / {{ $dept['total_evaluations'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-bold mr-2 text-gray-900 dark:text-white">{{ $dept['average_performance'] }}%</span>
                                                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $dept['average_performance'] }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex flex-wrap gap-1">
                                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">E: {{ $dept['excellent_count'] }}</span>
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">B: {{ $dept['good_count'] }}</span>
                                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">S: {{ $dept['satisfactory_count'] }}</span>
                                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full">P: {{ $dept['poor_count'] }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile/Tablet Cards -->
                        <div class="lg:hidden p-4 space-y-4">
                            @foreach ($reportData['departments'] as $dept)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $dept['name'] }}</h4>
                                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $dept['average_performance'] }}%</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Funcionários:</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">{{ $dept['total_employees'] }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Avaliações:</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white ml-1">{{ $dept['total_evaluations'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $dept['average_performance'] }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">Excelente: {{ $dept['excellent_count'] }}</span>
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">Bom: {{ $dept['good_count'] }}</span>
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">Satisfatório: {{ $dept['satisfactory_count'] }}</span>
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full">Péssimo: {{ $dept['poor_count'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum dado encontrado</h3>
                        <p class="text-gray-500 dark:text-gray-400">Não há dados de avaliação para o período selecionado.</p>
                    </div>
                @endif
            </div>

        <!-- EMPLOYEE REPORT -->
        @elseif($reportType === 'employee')
            <div class="space-y-6">
                @if (isset($reportData['message']))
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Selecione um Funcionário</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">{{ $reportData['message'] }}</p>
                        <button @click="showFilters = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Abrir Filtros
                        </button>
                    </div>
                @elseif(isset($reportData['employee']))
                    <!-- Employee Info Card -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-2xl font-bold">{{ substr($reportData['employee']->name, 0, 2) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportData['employee']->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">{{ $reportData['employee']->department->name }}</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Código: {{ $reportData['employee']->code }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-6 text-center">
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $reportData['total_evaluations'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Avaliações</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $reportData['average_performance'] }}%</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Média</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Chart -->
                    @if (isset($chartData['progress_chart']))
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Evolução da Performance
                            </h3>
                            <div class="h-64">
                                <canvas id="progressChart"></canvas>
                            </div>
                        </div>
                    @endif

                    <!-- Evaluations History -->
                    @if ($reportData['evaluations']->count() > 0)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Histórico de Avaliações
                                </h3>
                            </div>
                            
                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Classificação</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avaliador</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($reportData['evaluations'] as $evaluation)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                                    {{ $evaluation->evaluation_period->format('m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $evaluation->final_percentage }}%</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if ($evaluation->performance_class === 'Excelente') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                        @elseif($evaluation->performance_class === 'Bom') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                        @elseif($evaluation->performance_class === 'Satisfatório') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                        @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                                        {{ $evaluation->performance_class }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $evaluation->evaluator->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div class="md:hidden p-4 space-y-4">
                                @foreach ($reportData['evaluations'] as $evaluation)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $evaluation->evaluation_period->format('m/Y') }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $evaluation->evaluator->name }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $evaluation->final_percentage }}%</span>
                                                <div class="mt-1">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if ($evaluation->performance_class === 'Excelente') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                        @elseif($evaluation->performance_class === 'Bom') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                        @elseif($evaluation->performance_class === 'Satisfatório') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                        @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                                        {{ $evaluation->performance_class }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        <!-- TRENDS REPORT -->
        @elseif($reportType === 'trends')
            <div class="space-y-6">
                @if (isset($reportData['monthly_trends']) && $reportData['monthly_trends']->count() > 0)
                    <!-- Trends Chart -->
                    @if (isset($chartData['trends_chart']))
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Tendências Mensais
                            </h3>
                            <div class="h-80">
                                <canvas id="trendsChart"></canvas>
                            </div>
                        </div>
                    @endif

                    <!-- Monthly Data Table -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Dados Mensais
                            </h3>
                        </div>
                        
                        <!-- Desktop Table -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Avaliações</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance Média</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Abaixo do Limite</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% Abaixo do Limite</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reportData['monthly_trends'] as $trend)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $trend['period'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $trend['total_evaluations'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center">
                                                    <span class="mr-2 font-semibold text-gray-900 dark:text-white">{{ $trend['avg_performance'] }}%</span>
                                                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $trend['avg_performance'] }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($trend['below_threshold_count'] > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                        {{ $trend['below_threshold_count'] }}
                                                    </span>
                                                @else
                                                    <span class="text-green-600 dark:text-green-400 font-medium">0</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="font-semibold {{ $trend['below_threshold_percentage'] > 20 ? 'text-red-600 dark:text-red-400' : ($trend['below_threshold_percentage'] > 10 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                                    {{ $trend['below_threshold_percentage'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="lg:hidden p-4 space-y-4">
                            @foreach ($reportData['monthly_trends'] as $trend)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $trend['period'] }}</h4>
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $trend['avg_performance'] }}%</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Total:</span>
                                            <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $trend['total_evaluations'] }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Críticas:</span>
                                            <span class="font-medium ml-1 {{ $trend['below_threshold_count'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $trend['below_threshold_count'] }} ({{ $trend['below_threshold_percentage'] }}%)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $trend['avg_performance'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma tendência encontrada</h3>
                        <p class="text-gray-500 dark:text-gray-400">Não há dados suficientes para gerar tendências no período selecionado.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Performance Chart (Overview)
            @if ($reportType === 'overview' && isset($chartData['performance_chart']))
                const performanceCtx = document.getElementById('performanceChart');
                if (performanceCtx) {
                    new Chart(performanceCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartData['performance_chart']['labels']),
                            datasets: [{
                                data: @json($chartData['performance_chart']['data']),
                                backgroundColor: @json($chartData['performance_chart']['colors']),
                                borderWidth: 3,
                                borderColor: '#fff',
                                hoverBorderWidth: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            @endif

            // Progress Chart (Employee)
            @if ($reportType === 'employee' && isset($chartData['progress_chart']))
                const progressCtx = document.getElementById('progressChart');
                if (progressCtx) {
                    new Chart(progressCtx, {
                        type: 'line',
                        data: {
                            labels: @json($chartData['progress_chart']['labels']),
                            datasets: [{
                                label: 'Performance (%)',
                                data: @json($chartData['progress_chart']['data']),
                                borderColor: @json($chartData['progress_chart']['borderColor']),
                                backgroundColor: @json($chartData['progress_chart']['backgroundColor']),
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#3B82F6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            return 'Performance: ' + context.parsed.y + '%';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                }
            @endif

            // Trends Chart
            @if ($reportType === 'trends' && isset($chartData['trends_chart']))
                const trendsCtx = document.getElementById('trendsChart');
                if (trendsCtx) {
                    new Chart(trendsCtx, {
                        type: 'line',
                        data: {
                            labels: @json($chartData['trends_chart']['labels']),
                            datasets: @json($chartData['trends_chart']['datasets'])
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    beginAtZero: true,
                                    max: 100,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return Math.round(value);
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                }
            @endif
        });

        // Export functionality
        document.addEventListener('livewire:init', () => {
            Livewire.on('export-started', (event) => {
                // Show export notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Preparando exportação...
                    </div>`;
                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);
            });
        });
    </script>
</div>