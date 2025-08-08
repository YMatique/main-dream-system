<div class="space-y-6">
    {{-- Header com estatísticas --}}
    <div class="bg-gradient-to-r from-teal-500 via-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Gestão de Tipos de Manutenção</h2>
                <p class="mt-2 text-teal-100">Gerencie os tipos de manutenção e suas taxas</p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-col lg:flex-row gap-4">
                <button wire:click="openModal"
                    class="inline-flex items-center px-6 py-3 bg-white text-teal-700 rounded-lg font-semibold shadow-lg hover:bg-teal-50 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Tipo de Manutenção
                </button>
                <button wire:click="createDefaultMaintenanceTypes"
                    class="inline-flex items-center px-6 py-3 bg-white text-teal-700 rounded-lg font-semibold shadow-lg hover:bg-teal-50 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Criar Tipos Padrão
                </button>
            </div>
        </div>

        {{-- Estatísticas --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['total'] }}</p>
                        <p class="text-sm text-white/80">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['active'] }}</p>
                        <p class="text-sm text-white/80">Ativos</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                        </path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['inactive'] }}</p>
                        <p class="text-sm text-white/80">Inativos</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ number_format($this->stats['avg_hourly_rate_mzn'], 2) }} MZN
                        </p>
                        <p class="text-sm text-white/80">Taxa Média/Hora</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribuição de Taxas (Chart) --}}
    {{-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribuição de Taxas Horárias (MZN)</h3>
        ```chartjs
        {
            "type": "bar",
            "data": {
                "labels": ["0-300 MZN", "301-500 MZN", "501-700 MZN", "701+ MZN"],
                "datasets": [{
                    "label": "Tipos de Manutenção",
                    "data": [
                        {{ $this->rateDistribution['0-300'] }},
                        {{ $this->rateDistribution['301-500'] }},
                        {{ $this->rateDistribution['501-700'] }},
                        {{ $this->rateDistribution['701+'] }}
                    ],
                    "backgroundColor": [
                        "rgba(94, 234, 212, 0.6)",
                        "rgba(59, 130, 246, 0.6)",
                        "rgba(99, 102, 241, 0.6)",
                        "rgba(45, 212, 191, 0.6)"
                    ],
                    "borderColor": [
                        "rgb(94, 234, 212)",
                        "rgb(59, 130, 246)",
                        "rgb(99, 102, 241)",
                        "rgb(45, 212, 191)"
                    ],
                    "borderWidth": 1
                }]
            },
            "options": {
                "scales": {
                    "y": {
                        "beginAtZero": true,
                        "title": {
                            "display": true,
                            "text": "Número de Tipos"
                        }
                    },
                    "x": {
                        "title": {
                            "display": true,
                            "text": "Faixa de Taxa Horária (MZN)"
                        }
                    }
                },
                "plugins": {
                    "legend": {
                        "display": false
                    }
                }
            }
        }
        ```
    </div> --}}

    {{-- Filtros e Pesquisa --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Pesquisar
                    </label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Nome ou descrição...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        @if ($search)
                            <button wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="1">Ativos</option>
                        <option value="0">Inativos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Taxa Mínima
                        (MZN)</label>
                    <input wire:model.live.debounce.300ms="rateRangeMin" type="number" step="0.01"
                        min="0"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="Ex: 100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Taxa Máxima
                        (MZN)</label>
                    <input wire:model.live.debounce.300ms="rateRangeMax" type="number" step="0.01"
                        min="0"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="Ex: 1000">
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar de Ações --}}
    <div
        class="sticky top-0 z-10 bg-white dark:bg-gray-800 rounded-t-xl shadow border border-gray-200 dark:border-gray-700 p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="openModal"
                class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Novo
            </button>
            <button wire:click="export('excel')"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Exportar Excel
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Imprimir
            </button>
            <button wire:click="bulkUpdateRates(10, 'all')"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors"
                title="Aumentar taxas em 10%">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                Aumentar 10%
            </button>
            <button wire:click="bulkUpdateRates(-10, 'all')"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors"
                title="Reduzir taxas em 10%">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                Reduzir 10%
            </button>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $maintenanceTypes->total() }} tipos de manutenção encontrados
        </div>
    </div>

    {{-- Tabela de Tipos de Manutenção --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-b-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" wire:model="selectAll"
                                class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('name')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Nome</span>
                                @if ($sortBy === 'name')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Descrição</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('hourly_rate_mzn')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Taxa/Hora (MZN)</span>
                                @if ($sortBy === 'hourly_rate_mzn')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('hourly_rate_usd')"
                                class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Taxa/Hora (USD)</span>
                                @if ($sortBy === 'hourly_rate_usd')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Custos de Clientes</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($maintenanceTypes as $type)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" wire:model="selectedTypes.{{ $type->id }}"
                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $type->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($type->description, 50, '...') ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ number_format($type->hourly_rate_mzn, 2) }} MZN</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ number_format($type->hourly_rate_usd, 2) }} USD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="viewClientRates({{ $type->id }})"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    {{ $type->client_costs_count }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $type->id }})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors {{ $type->is_active ? 'bg-teal-100 text-teal-800 hover:bg-teal-200 dark:bg-teal-900 dark:text-teal-200' : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' }}">
                                    @if ($type->is_active)
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ativo
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Inativo
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $type->id }})"
                                        class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300 transition-colors"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="duplicateMaintenanceType({{ $type->id }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                        title="Duplicar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $type->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors {{ $type->client_costs_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        title="{{ $type->client_costs_count > 0 ? 'Não pode ser eliminado devido a custos de clientes associados' : 'Eliminar' }}"
                                        {{ $type->client_costs_count > 0 ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum tipo de
                                        manutenção encontrado</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando o seu primeiro tipo
                                        de manutenção ou use os tipos padrão.</p>
                                    <div class="flex gap-2">
                                        <button wire:click="openModal"
                                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Criar tipo
                                        </button>
                                        <button wire:click="createDefaultMaintenanceTypes"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Tipos Padrão
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bulk Actions --}}
        @if (!empty($selectedTypes))
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ count(array_filter($selectedTypes)) }} tipos selecionados
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="bulkActivate({{ json_encode(array_keys(array_filter($selectedTypes))) }})"
                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            Ativar
                        </button>
                        <button
                            wire:click="bulkDeactivate({{ json_encode(array_keys(array_filter($selectedTypes))) }})"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Desativar
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Paginação --}}
        @if ($maintenanceTypes->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Mostrando {{ $maintenanceTypes->firstItem() }} a {{ $maintenanceTypes->lastItem() }} de
                        {{ $maintenanceTypes->total() }} resultados
                    </div>
                    <div class="flex-1 flex justify-end">
                        {{ $maintenanceTypes->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Criar/Editar Tipo de Manutenção --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-10 opacity-90 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 dark:bg-teal-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-teal-600 dark:text-teal-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    {{ $editingId ? 'Editar Tipo de Manutenção' : 'Novo Tipo de Manutenção' }}
                                </h3>
                                <div class="mt-4">
                                    <form wire:submit.prevent="save" class="space-y-4">
                                        <div>
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                            <input wire:model.live="name" id="name" type="text"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: Manutenção Preventiva">
                                            @error('name')
                                                <span
                                                    class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição
                                                (opcional)</label>
                                            <textarea wire:model.live="description" id="description"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('description') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Descreva o tipo de manutenção..." rows="4"></textarea>
                                            @error('description')
                                                <span
                                                    class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="hourly_rate_mzn"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taxa
                                                Horária Padrão (MZN)</label>
                                            <input wire:model.live="hourly_rate_mzn" id="hourly_rate_mzn"
                                                type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('hourly_rate_mzn') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 500.00">
                                            @error('hourly_rate_mzn')
                                                <span
                                                    class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="hourly_rate_usd"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taxa
                                                Horária Padrão (USD)</label>
                                            <input wire:model.live="hourly_rate_usd" id="hourly_rate_usd"
                                                type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('hourly_rate_usd') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 7.80">
                                            @error('hourly_rate_usd')
                                                <span
                                                    class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input wire:model.defer="is_active" type="checkbox"
                                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 dark:border-gray-600 rounded">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tipo
                                                    ativo</span>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            {{ $editingId ? 'Atualizar' : 'Criar' }}
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Custos de Clientes --}}
    @if ($showRatesModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-10 opacity-90 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Custos de Clientes para {{ $this->maintenanceTypeInfo->name }}
                                </h3>
                                <div class="mt-4">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Cliente</th>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Taxa (MZN)</th>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Taxa (USD)</th>
                                                    <th
                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Data Efetiva</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($this->clientRates as $rate)
                                                    <tr>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                            {{ $rate->client->name }}</td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                            {{ number_format($rate->cost_mzn, 2) }} MZN</td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                            {{ number_format($rate->cost_usd, 2) }} USD</td>
                                                        <td
                                                            class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $rate->effective_date->format('d/m/Y') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4"
                                                            class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                            Nenhum custo de cliente associado.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeRatesModal" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Confirmação de Exclusão --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-10 opacity-90 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Confirmar Exclusão
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Tem certeza que deseja eliminar este tipo de manutenção? Esta ação não pode ser
                                        desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg wire:loading wire:target="delete" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Eliminar
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <x-notification-toast />
</div>
