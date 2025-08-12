<div class="space-y-6">
    {{-- Header com título e ações principais --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ordens de Reparação</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Gerencie e acompanhe todas as ordens de reparação da empresa
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Botão de refresh --}}
            <button wire:click="refreshData"
                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Atualizar
            </button>

            {{-- Botão de criar nova ordem --}}
            @if ($this->hasPermissionToCreate)
                <a href="{{ route('company.orders.form1') }}"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Ordem
                </a>
            @endif
        </div>
    </div>

    {{-- Cards de Métricas --}}
    @if ($showMetrics)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total de Ordens --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Ordens</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['total_orders']) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                @if (isset($metrics['period_start']) && isset($metrics['period_end']))
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ $metrics['period_start'] }} - {{ $metrics['period_end'] }}
                    </p>
                @endif
            </div>

            {{-- Ordens Concluídas --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Concluídas</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($metrics['completed_orders']) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                @if ($metrics['total_orders'] > 0)
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ number_format(($metrics['completed_orders'] / $metrics['total_orders']) * 100, 1) }}% do
                        total
                    </p>
                @endif
            </div>

            {{-- Em Andamento --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Em Andamento</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                            {{ number_format($metrics['in_progress_orders']) }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Precisam de atenção
                </p>
            </div>

            {{-- Tempo Médio --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tempo Médio</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $metrics['avg_completion_days'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Dias para conclusão
                </p>
            </div>
        </div>

        {{-- Mini gráfico de distribuição por formulário --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribuição por Formulário</h3>
            <div class="grid grid-cols-5 gap-4">
                <div class="text-center">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-blue-600 h-2 rounded-full"
                            style="width: {{ $metrics['total_orders'] > 0 ? ($metrics['form1_count'] / $metrics['total_orders']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Form 1</p>
                    <p class="text-lg font-bold text-blue-600">{{ $metrics['form1_count'] }}</p>
                </div>
                <div class="text-center">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-green-600 h-2 rounded-full"
                            style="width: {{ $metrics['total_orders'] > 0 ? ($metrics['form2_count'] / $metrics['total_orders']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Form 2</p>
                    <p class="text-lg font-bold text-green-600">{{ $metrics['form2_count'] }}</p>
                </div>
                <div class="text-center">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-orange-600 h-2 rounded-full"
                            style="width: {{ $metrics['total_orders'] > 0 ? ($metrics['form3_count'] / $metrics['total_orders']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Form 3</p>
                    <p class="text-lg font-bold text-orange-600">{{ $metrics['form3_count'] }}</p>
                </div>
                <div class="text-center">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-purple-600 h-2 rounded-full"
                            style="width: {{ $metrics['total_orders'] > 0 ? ($metrics['form4_count'] / $metrics['total_orders']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Form 4</p>
                    <p class="text-lg font-bold text-purple-600">{{ $metrics['form4_count'] }}</p>
                </div>
                <div class="text-center">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                        <div class="bg-red-600 h-2 rounded-full"
                            style="width: {{ $metrics['total_orders'] > 0 ? ($metrics['form5_count'] / $metrics['total_orders']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Form 5</p>
                    <p class="text-lg font-bold text-red-600">{{ $metrics['form5_count'] }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Filtros
                    @if ($this->activeFiltersCount > 0)
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $this->activeFiltersCount }} ativo(s)
                        </span>
                    @endif
                </h3>

                <div class="flex gap-2">
                    {{-- Toggle de modo de visualização --}}
                    <button wire:click="toggleViewMode"
                        class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        @if ($viewMode === 'table')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        @endif
                    </button>

                    {{-- Limpar filtros --}}
                    @if ($this->activeFiltersCount > 0)
                        <button wire:click="clearFilters"
                            class="px-3 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                {{-- Busca geral --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Busca</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Ordem, cliente, máquina..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filtro por formulário --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formulário</label>
                    <select wire:model.live="filterByForm"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        @foreach ($currentFormOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cliente</label>
                    <select wire:model.live="filterByClient"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos os clientes</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por período --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período</label>
                    <select wire:model.live="filterByPeriod"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="7">Últimos 7 dias</option>
                        <option value="30">Últimos 30 dias</option>
                        <option value="60">Últimos 60 dias</option>
                        <option value="90">Últimos 90 dias</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>

                {{-- Datas personalizadas --}}
                @if ($filterByPeriod === 'custom')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data
                            Início</label>
                        <input type="date" wire:model.live="filterStartDate"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Fim</label>
                        <input type="date" wire:model.live="filterEndDate"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Links rápidos para listagens específicas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Listagens Específicas</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('company.orders.form1-list') }}"
                class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/40">
                    <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">1</span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Formulário 1</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metrics['form1_count'] }} ordens</span>
            </a>

            <a href="{{ route('company.orders.form2-list') }}"
                class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-green-200 dark:group-hover:bg-green-900/40">
                    <span class="text-green-600 dark:text-green-400 font-semibold text-sm">2</span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Formulário 2</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metrics['form2_count'] }} ordens</span>
            </a>

            <a href="{{ route('company.orders.form3-list') }}"
                class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-orange-200 dark:group-hover:bg-orange-900/40">
                    <span class="text-orange-600 dark:text-orange-400 font-semibold text-sm">3</span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Formulário 3</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metrics['form3_count'] }} ordens</span>
            </a>

            <a href="{{ route('company.orders.form4-list') }}"
                class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/40">
                    <span class="text-purple-600 dark:text-purple-400 font-semibold text-sm">4</span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Formulário 4</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metrics['form4_count'] }} ordens</span>
            </a>

            <a href="{{ route('company.orders.form5-list') }}"
                class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-red-200 dark:group-hover:bg-red-900/40">
                    <span class="text-red-600 dark:text-red-400 font-semibold text-sm">5</span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Formulário 5</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $metrics['form5_count'] }} ordens</span>
            </a>

            <a href="{{ route('company.orders.advanced-list') }}"
                class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors group">
                <div
                    class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center mb-2 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/40">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                        </path>
                    </svg>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 text-center">Avançada</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Customizável</span>
            </a>
        </div>
    </div>

    {{-- Tabela/Cards de resultados --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        {{-- Header da tabela --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Resultados ({{ $orders->total() }})
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Mostrando {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} de
                        {{ $orders->total() }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Exportar --}}
                    @if ($this->hasPermissionToExport)
                        <button
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Exportar
                        </button>
                    @endif

                    {{-- Items per page --}}
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Mostrar:</label>
                        <select wire:model.live="perPage"
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Conteúdo da tabela/cards --}}
        <div class="p-6">
            @if ($orders->count() > 0)
                @if ($viewMode === 'table')
                    {{-- Modo Tabela --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th wire:click="sortBy('order_number')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <div class="flex items-center">
                                            Ordem
                                            @if ($sortField === 'order_number')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Máquina
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Formulário Atual
                                    </th>
                                    <th wire:click="sortBy('created_at')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <div class="flex items-center">
                                            Data Criação
                                            @if ($sortField === 'created_at')
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $order->order_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $order->form1?->client?->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $order->form1?->machineNumber?->number ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @php
                                                    $formColors = [
                                                        'form1' => 'blue',
                                                        'form2' => 'green',
                                                        'form3' => 'orange',
                                                        'form4' => 'purple',
                                                        'form5' => 'red',
                                                    ];
                                                    $color = $formColors[$order->current_form] ?? 'gray';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/20 dark:text-{{ $color }}-400">
                                                    {{ strtoupper($order->current_form) }}
                                                </span>
                                                <div class="ml-2 w-24 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                                    <div class="bg-{{ $color }}-600 h-2 rounded-full"
                                                        style="width: {{ $order->progress_percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($order->is_completed)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    ✓ Concluída
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                                    🔄 Em andamento
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Ver detalhes --}}
                                                <button wire:click="viewOrder({{ $order->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                {{-- Continuar/Editar --}}
                                                @if (!$order->is_completed)
                                                    <button wire:click="continueOrder({{ $order->id }})"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Modo Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($orders as $order)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition-shadow">
                                {{-- Header do card --}}
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    @if ($order->is_completed)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            ✓ Concluída
                                        </span>
                                    @else
                                        @php
                                            $formColors = [
                                                'form1' => 'blue',
                                                'form2' => 'green',
                                                'form3' => 'orange',
                                                'form4' => 'purple',
                                                'form5' => 'red',
                                            ];
                                            $color = $formColors[$order->current_form] ?? 'gray';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/20 dark:text-{{ $color }}-400">
                                            {{ strtoupper($order->current_form) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Informações principais --}}
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Cliente:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $order->form1?->client?->name ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Máquina:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $order->form1?->machineNumber?->number ?? '-' }}
                                        </span>
                                    </div>
                                    @if ($order->form1?->maintenanceType)
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Tipo:</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $order->form1->maintenanceType->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Progress bar --}}
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Progresso</span>
                                        <span
                                            class="text-xs text-gray-600 dark:text-gray-400">{{ $order->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div class="bg-{{ $color }}-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $order->progress_percentage }}%"></div>
                                    </div>
                                </div>

                                {{-- Ações --}}
                                <div class="flex justify-between items-center">
                                    <button wire:click="viewOrder({{ $order->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                        Ver Detalhes
                                    </button>

                                    @if (!$order->is_completed)
                                        <button wire:click="continueOrder({{ $order->id }})"
                                            class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 rounded-md text-sm font-medium hover:bg-green-200 dark:hover:bg-green-900/40 transition-colors">
                                            Continuar
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Finalizada</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Paginação --}}
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                {{-- Estado vazio --}}
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma ordem encontrada</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        @if ($this->activeFiltersCount > 0)
                            Tente ajustar os filtros para encontrar mais resultados.
                        @else
                            Comece criando sua primeira ordem de reparação.
                        @endif
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                        @if ($this->activeFiltersCount > 0)
                            <button wire:click="clearFilters"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Limpar Filtros
                            </button>
                        @endif

                        @if ($this->hasPermissionToCreate)
                            <a href="{{ route('company.orders.form1') }}"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Criar Primeira Ordem
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>


    {{-- Loading overlay --}}
<div wire:loading.flex
    wire:target="search,filterByForm,filterByClient,filterByPeriod,filterStartDate,filterEndDate,sortBy,continueOrder,viewOrder,refreshData"
    class="fixed inset-0 bg-black opacity-75 z-50 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
        <div class="flex items-center">
            <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300">Carregando...</span>
        </div>
    </div>
</div>
</div>



{{-- Scripts para funcionalidades extras --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh a cada 5 minutos (opcional)
            setInterval(function() {
                @this.call('refreshData');
            }, 300000); // 5 minutos

            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K para focar na busca
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector(
                        'input[wire\\:model\\.live\\.debounce\\.300ms="search"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }

                // Escape para limpar filtros
                if (e.key === 'Escape') {
                    @this.call('clearFilters');
                }
            });

            // Listener para refresh completo
            window.addEventListener('refresh-complete', function() {
                // Mostrar toast de sucesso (opcional)
                console.log('Dados atualizados com sucesso');
            });

            // Listener para mostrar detalhes da ordem (modal)
            window.addEventListener('show-order-details', function(event) {
                // TODO: Implementar modal de detalhes
                console.log('Mostrar detalhes da ordem:', event.detail.orderId);

                // Por enquanto, redirecionar para visualização
                // window.location.href = `/company/orders/${event.detail.orderId}/view`;
            });

            // Smooth scroll para resultados quando filtrar
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

            // Persistir estado dos filtros no localStorage
            function saveFiltersState() {
                const filters = {
                    search: @this.search,
                    filterByForm: @this.filterByForm,
                    filterByClient: @this.filterByClient,
                    filterByPeriod: @this.filterByPeriod,
                    viewMode: @this.viewMode,
                    perPage: @this.perPage
                };
                localStorage.setItem('repair_orders_filters', JSON.stringify(filters));
            }

            // Salvar estado quando os filtros mudarem
            window.addEventListener('livewire:updated', saveFiltersState);
        });

        // Função para exportar dados (chamada do botão exportar)
        function exportOrders() {
            const params = new URLSearchParams({
                search: @this.search,
                filterByForm: @this.filterByForm,
                filterByClient: @this.filterByClient,
                filterStartDate: @this.filterStartDate,
                filterEndDate: @this.filterEndDate,
                format: 'excel' // ou 'csv', 'pdf'
            });
                        

        }

        // Função para preview rápido (hover)
        function showQuickPreview(orderId) {
            // TODO: Implementar tooltip com informações básicas
            console.log('Preview rápido da ordem:', orderId);
        }
    </script>
@endpush
