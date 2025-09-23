<div class="space-y-8 p-4 sm:p-6">
    {{-- Header com Gradiente e Cards de Métricas --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Ordens de Reparação - Formulário 1</h2>
                <p class="mt-2 text-sm text-blue-100">Gerencie as ordens iniciais da sua empresa com facilidade</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="refreshData"
                    class="px-5 py-2.5 bg-white/20 dark:bg-blue-900/30 border border-white/30 text-white rounded-lg hover:bg-white/30 transition-all duration-300 flex items-center shadow-sm backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Atualizar
                </button>
                @if ($this->hasPermissionToCreate)
                    <a href="{{ route('company.orders.form1-list') }}"
                        class="px-5 py-2.5 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-all duration-300 flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Ordem
                    </a>
                @endif
            </div>
        </div>
        {{-- Métricas dentro do Gradiente --}}
        @if ($showMetrics)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Total de Ordens</p>
                            <p class="text-2xl font-bold">{{ number_format($metrics['total_orders']) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">{{ $metrics['period_start'] }} - {{ $metrics['period_end'] }}
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens por Tipo</p>
                            <p class="text-2xl font-bold">{{ $metrics['maintenance_types_count'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4m-12 0l4-4-4-4"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Tipos de manutenção ativos</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens Avançadas</p>
                            <p class="text-2xl font-bold">{{ $metrics['advanced_orders'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-xs text-white/60">Ordens que passaram para Form2+</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Ordens Completas</p>
                            <p class="text-2xl font-bold">{{ $metrics['completed_orders'] ?? 0 }}</p>
                        </div>
                        <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
            @if ($this->activeFiltersCount > 0)
                <button wire:click="clearFilters"
                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Limpar ({{ $this->activeFiltersCount }})
                </button>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                        placeholder="Ordem, cliente, máquina...">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                <select wire:model.live="filterByClient"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todos os Clientes</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de
                    Manutenção</label>
                <select wire:model.live="filterByMaintenanceType"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todos os Tipos</option>
                    @foreach ($maintenanceTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mês/Ano</label>
                <input wire:model.live="filterByMonthYear" type="text"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                    placeholder="MM/YYYY">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Máquina</label>
                <select wire:model.live="filterByMachine"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                    <option value="">Todas as Máquinas</option>
                    @foreach ($machineNumbers as $machine)
                        <option value="{{ $machine->id }}">{{ $machine->number }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                <input wire:model.live="filterStartDate" type="date"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                <input wire:model.live="filterEndDate" type="date"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
            </div>
            <div class="flex items-end">
                <button wire:click="toggleViewMode"
                    class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{ $viewMode === 'table' ? 'M4 6h16M4 10h16M4 14h16M4 18h16' : 'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z' }}">
                        </path>
                    </svg>
                    {{ $viewMode === 'table' ? 'Visualizar como Cards' : 'Visualizar como Tabela' }}
                </button>
            </div>
        </div>
    </div>

    {{-- Listagem --}}
    @if ($orders->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar por página:</label>
                    <select wire:model.live="perPage"
                        class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                @if ($this->hasPermissionToExport)
                    <div class="flex gap-2">
                        {{-- <button wire:click="exportOrders('excel')"
                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Exportar Excel
                        </button>
                        <button wire:click="exportOrders('csv')"
                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Exportar CSV
                        </button> --}}
                        <button wire:click="exportOrders('pdf')"
                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 shadow-sm flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
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
                                <th wire:click="sortBy('order_number')"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Ordem @if ($sortField === 'order_number')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th wire:click="sortBy('form1.client.name')"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Cliente @if ($sortField === 'form1.client.name')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th wire:click="sortBy('form1.maintenanceType.name')"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Tipo Manutenção @if ($sortField === 'form1.maintenanceType.name')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th wire:click="sortBy('form1.machineNumber.number')"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Máquina @if ($sortField === 'form1.machineNumber.number')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th wire:click="sortBy('form1.carimbo')"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                                    Data @if ($sortField === 'form1.carimbo')
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Descrição Avaria</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $order->order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->form1?->client?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->form1?->maintenanceType?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->form1?->machineNumber?->number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->form1?->carimbo->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ Str::limit($order->form1?->descricao_avaria ?? '-', 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <!-- Botão Ver -->
                                        <button wire:click="viewOrder({{ $order->id }})"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-4 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Ver
                                        </button>

                                        <!-- Botão Editar -->
                                        <button wire:click="editOrder({{ $order->id }})"
                                            class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 mr-4 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Editar
                                        </button>

                                        <!-- Botão Continuar -->
                                        <button wire:click="continueOrder({{ $order->id }})"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
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
                    @foreach ($orders as $order)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-center mb-4">
                                <span
                                    class="text-lg font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400">{{ $order->form1?->carimbo->format('d/m/Y') ?? '-' }}</span>
                            </div>
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Cliente:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->form1?->client?->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Tipo Manutenção:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->form1?->maintenanceType?->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Máquina:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->form1?->machineNumber?->number ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Descrição:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ Str::limit($order->form1?->descricao_avaria ?? '-', 30) }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <button wire:click="viewOrder({{ $order->id }})"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium transition-colors duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Ver Detalhes
                                </button>
                                <button wire:click="continueOrder({{ $order->id }})"
                                    class="px-4 py-2 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-lg text-sm font-medium hover:bg-green-200 dark:hover:bg-green-900/40 transition-all duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                    Continuar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma ordem encontrada</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                @if ($this->activeFiltersCount > 0)
                    Ajuste os filtros para encontrar mais resultados.
                @else
                    Comece criando sua primeira ordem de reparação.
                @endif
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                @if ($this->activeFiltersCount > 0)
                    <button wire:click="clearFilters"
                        class="px-5 py-2.5 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 shadow-sm">Limpar
                        Filtros</button>
                @endif
                @if ($this->hasPermissionToCreate)
                    <a href="{{ route('company.orders.form1-list') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Criar Primeira Ordem
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex
        wire:target="search,filterByClient,filterByMaintenanceType,filterByMonthYear,filterByMachine,filterStartDate,filterEndDate,sortBy,continueOrder,viewOrder,refreshData,exportOrders"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700 dark:text-gray-200 font-bold">Carregando...</span>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Refresh automático a cada 5 minutos
            setInterval(function() {
                @this.call('refreshData');
            }, 300000);

            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector(
                        'input[wire\\:model\\.live\\.debounce\\.300ms="search"]');
                    if (searchInput) searchInput.focus();
                }
                if (e.key === 'Escape') {
                    @this.call('clearFilters');
                }
            });

            // Notificação de refresh
            window.addEventListener('refresh-complete', function() {
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg';
                toast.textContent = 'Dados atualizados com sucesso!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });

            // Modal de detalhes (exemplo)
            window.addEventListener('show-order-details', function(event) {

                const data = event.detail[0].orderData;
                console.log(data);


                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center';
                modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full shadow-xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detalhes da Ordem #${data.order_number}</h3>
            
            <!-- Status -->
            <div class="mb-4 p-3 rounded-lg bg-${data.visual_status.color}-100 dark:bg-gray-700 flex items-center gap-2">
                <span class="text-xl">${data.visual_status.icon}</span>
                <span class="font-medium">${data.visual_status.label}</span>
                <span class="ml-auto text-sm">${data.progress_percentage}% concluído</span>
            </div>
            
            <!-- Informações Básicas -->
            <div class="mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Informações Gerais</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="text-gray-600 dark:text-gray-300">Data de Criação:</div>
                    <div class="text-gray-900 dark:text-white">${data.created_at}</div>
                    
                    <div class="text-gray-600 dark:text-gray-300">Tipo de Manutenção:</div>
                    <div class="text-gray-900 dark:text-white">${data.form1.maintenance_type}</div>
                    
                    <div class="text-gray-600 dark:text-gray-300">Máquina:</div>
                    <div class="text-gray-900 dark:text-white">${data.form1.machine_number}</div>
                </div>
            </div>
            
            <!-- Formulários Completados -->
            <div class="mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Progresso</h4>
                <div class="flex gap-1 mb-2">
                    ${Object.entries(data.completed_forms).map(([form, completed]) => `
                                <div class="flex-1 h-2 rounded-full ${completed ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'}"></div>
                            `).join('')}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    ${Object.keys(data.completed_forms).filter(f => data.completed_forms[f]).length} de ${Object.keys(data.completed_forms).length} formulários completados
                </div>
            </div>
            
            <!-- Detalhes do Cliente -->
            <div class="mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Cliente</h4>
                <div class="text-sm">
                    <div class="text-gray-900 dark:text-white font-medium">${data.form1.client}</div>
                    <div class="text-gray-600 dark:text-gray-300">${data.form1.month_year}</div>
                </div>
            </div>
            
            <!-- Descrição -->
            <div class="mb-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Descrição</h4>
                <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                    ${data.form1.description || 'Nenhuma descrição fornecida.'}
                </p>
            </div>
            
            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="this.closest('.fixed').remove()">
                Fechar
            </button>
        </div>
    `;
                document.body.appendChild(modal);
            });

            // Scroll suave para resultados
            window.addEventListener('livewire:updated', function() {
                const resultsSection = document.querySelector(
                    '[class*="bg-white"][class*="rounded-xl"]:has(table, .grid)');
                if (resultsSection && window.pageYOffset > resultsSection.offsetTop) {
                    resultsSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });

            // Persistência de filtros
            function saveFiltersState() {
                const filters = {
                    search: @this.search,
                    filterByClient: @this.filterByClient,
                    filterByMaintenanceType: @this.filterByMaintenanceType,
                    filterByMonthYear: @this.filterByMonthYear,
                    filterByMachine: @this.filterByMachine,
                    viewMode: @this.viewMode,
                    perPage: @this.perPage
                };
                localStorage.setItem('form1_orders_filters', JSON.stringify(filters));
            }

            window.addEventListener('livewire:updated', saveFiltersState);

            // Carregar filtros salvos
            const savedFilters = localStorage.getItem('form1_orders_filters');
            if (savedFilters) {
                const filters = JSON.parse(savedFilters);
                @this.set('search', filters.search || '');
                @this.set('filterByClient', filters.filterByClient || '');
                @this.set('filterByMaintenanceType', filters.filterByMaintenanceType || '');
                @this.set('filterByMonthYear', filters.filterByMonthYear || '');
                @this.set('filterByMachine', filters.filterByMachine || '');
                @this.set('viewMode', filters.viewMode || 'table');
                @this.set('perPage', filters.perPage || 15);
            }
        });
    </script>
@endsection
