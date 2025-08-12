<div class="space-y-8 p-4 sm:p-6">
    {{-- Header com Gradiente e Cards de Métricas --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Ordens de Reparação - Formulário 2</h2>
                <p class="mt-2 text-sm text-blue-100">Gerencie as ordens com alocação de técnicos e materiais</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="refreshData" class="px-5 py-2.5 bg-white/20 dark:bg-blue-900/30 border border-white/30 text-white rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center shadow-sm backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Atualizar
                </button>
                @if ($hasPermissionToCreate)
                    <a href="{{ route('company.orders.form2') }}" class="px-5 py-2.5 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-all duration-300 flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Ordem
                    </a>
                @endif
            </div>
        </div>
        @if ($showMetrics)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Total de Ordens</p>
                            <p class="text-2xl font-bold">{{ number_format($metrics['total_orders'] ?? 0) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">{{ $metrics['period_start'] ?? '-' }} - {{ $metrics['period_end'] ?? '-' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens com Funcionários</p>
                            <p class="text-2xl font-bold">{{ $metrics['orders_with_employees'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Ordens com funcionários alocados</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens com Materiais Adicionais</p>
                            <p class="text-2xl font-bold">{{ $metrics['orders_with_additional_materials'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Ordens com materiais não cadastrados</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens Completas</p>
                            <p class="text-2xl font-bold">{{ $metrics['completed_orders'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Ordens que passaram do Form5</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Filtros</h3>
            @if ($activeFiltersCount > 0)
                <button wire:click="clearFilters" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Limpar ({{ $activeFiltersCount }})
                </button>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" placeholder="Ordem, funcionário, material...">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Funcionário</label>
                <select wire:model.live="filterByEmployee" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todos os Funcionários</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select wire:model.live="filterByStatus" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todos os Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Localização</label>
                <select wire:model.live="filterByLocation" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todas as Localizações</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mês/Ano</label>
                <input wire:model.live="filterByMonthYear" type="text" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" placeholder="MM/YYYY">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                <input wire:model.live="filterStartDate" type="date" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                <input wire:model.live="filterEndDate" type="date" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>
            <div class="flex items-end">
                <button wire:click="toggleViewMode" class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $viewMode === 'table' ? 'M4 6h16M4 10h16M4 14h16M4 18h16' : 'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z' }}"></path>
                    </svg>
                    {{ $viewMode === 'table' ? 'Visualizar como Cards' : 'Visualizar como Tabela' }}
                </button>
            </div>
        </div>
    </div>

    {{-- Listagem --}}
    @if ($form2s->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar por página:</label>
                    <select wire:model.live="perPage" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                @if ($hasPermissionToExport)
                    <div class="flex gap-2">
                        <button wire:click="exportOrders('excel')" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar Excel
                        </button>
                        <button wire:click="exportOrders('csv')" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar CSV
                        </button>
                        <button wire:click="exportOrders('pdf')" class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Exportar PDF
                        </button>
                    </div>
                @endif
            </div>
            @if ($viewMode === 'table')
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th wire:click="sortBy('repairOrder.order_number')" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Ordem @if ($sortField === 'repairOrder.order_number') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                </th>
                                <th wire:click="sortBy('carimbo')" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Data @if ($sortField === 'carimbo') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                </th>
                                <th wire:click="sortBy('location.name')" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Localização @if ($sortField === 'location.name') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                </th>
                                <th wire:click="sortBy('status.name')" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Status @if ($sortField === 'status.name') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                </th>
                                <th wire:click="sortBy('tempo_total_horas')" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Total Horas @if ($sortField === 'tempo_total_horas') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Funcionários</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Atividade Realizada</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($form2s as $form2)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $form2->repairOrder?->order_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $form2->carimbo?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $form2->location?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $form2->status?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ number_format($form2->tempo_total_horas ?? 0, 2) }}h</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $form2->employees->isNotEmpty() ? $form2->employees->pluck('employee.name')->implode(', ') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Illuminate\Support\Str::limit($form2->actividade_realizada ?? '-', 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="viewOrder({{ $form2->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-4 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </button>
                                        <button wire:click="continueOrder({{ $form2->id }})" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                            </svg>
                                            Continuar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($form2s as $form2)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $form2->repairOrder?->order_number ?? '-' }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $form2->carimbo?->format('d/m/Y') ?? '-' }}</span>
                            </div>
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Localização:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $form2->location?->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $form2->status?->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Horas:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($form2->tempo_total_horas ?? 0, 2) }}h</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Funcionários:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $form2->employees->isNotEmpty() ? $form2->employees->pluck('employee.name')->implode(', ') : '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Atividade:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ \Illuminate\Support\Str::limit($form2->actividade_realizada ?? '-', 30) }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <button wire:click="viewOrder({{ $form2->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalhes
                                </button>
                                <button wire:click="continueOrder({{ $form2->id }})" class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-lg text-sm font-medium hover:bg-green-200 dark:hover:bg-green-900/40 transition-all duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                    Continuar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                {{ $form2s->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma ordem encontrada</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if ($activeFiltersCount > 0)
                    Ajuste os filtros para encontrar ordens no Formulário 2.
                @else
                    Nenhuma ordem avançou para o Formulário 2. Crie ou continue uma ordem.
                @endif
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                @if ($activeFiltersCount > 0)
                    <button wire:click="clearFilters" class="px-5 py-2.5 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 shadow-sm">Limpar Filtros</button>
                @endif
                @if ($hasPermissionToCreate)
                    <a href="{{ route('company.orders.form2') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Continuar Ordem
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex wire:target="search,filterByEmployee,filterByStatus,filterByLocation,filterByMonthYear,filterStartDate,filterEndDate,sortBy,continueOrder,viewOrder,refreshData,exportOrders" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-200 font-bold">Carregando...</span>
        </div>
    </div>

    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('funcionando');
                
                // Refresh automático a cada 5 minutos
                setInterval(function() {
                    @this.call('refreshData');
                }, 300000);

                // Atalhos de teclado
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        const searchInput = document.querySelector('input[wire\\:model\\.live\\.debounce\\.300ms="search"]');
                        if (searchInput) searchInput.focus();
                    }
                    if (e.key === 'Escape') {
                        @this.call('clearFilters');
                    }
                });

                // Notificação de refresh
                window.addEventListener('refresh-complete', function() {
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg';
                    toast.textContent = 'Dados atualizados com sucesso!';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                });

                // Modal de detalhes
                window.addEventListener('show-order-details', function(event) {
                    const data = event.detail[0].form2Data;
                    console.log('Detalhes da Ordem:', event.detail[0].form2Data);
                    
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full shadow-xl max-h-[80vh] overflow-y-auto">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detalhes da Ordem #${data.order_number || data.id}</h3>
                            <div class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                                <p><strong>Ordem:</strong> ${data.repair_order?.order_number || '-'}</p>
                                <p><strong>Data:</strong> ${data.carimbo ? new Date(data.carimbo).toLocaleString('pt-BR') : '-'}</p>
                                <p><strong>Localização:</strong> ${data.location?.name || '-'}</p>
                                <p><strong>Status:</strong> ${data.status?.name || '-'}</p>
                                <p><strong>Total Horas:</strong> ${data.tempo_total_horas ? Number(data.tempo_total_horas).toFixed(2) + 'h' : '-'}</p>
                                <p><strong>Funcionários:</strong> ${data.employees?.length ? data.employees.map(e => e.employee?.name || '-').join(', ') : '-'}</p>
                                <p><strong>Materiais:</strong> ${data.materials?.length ? data.materials.map(m => m.material?.name + ' (' + m.quantidade + ')').join(', ') : '-'}</p>
                                <p><strong>Materiais Adicionais:</strong> ${data.additional_materials?.length ? data.additional_materials.map(m => m.nome_material + ' (' + m.quantidade + ', ' + m.custo_unitario + ')').join(', ') : '-'}</p>
                                <p><strong>Atividade Realizada:</strong> ${data.actividade_realizada || '-'}</p>
                            </div>
                            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300" onclick="this.closest('.fixed').remove()">Fechar</button>
                        </div>
                    `;
                    document.body.appendChild(modal);
                });
            });
        </script>
    @endsection