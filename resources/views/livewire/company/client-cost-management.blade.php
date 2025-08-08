<div class="space-y-6">
    {{-- Header com estatísticas --}}
    <div class="bg-gradient-to-r from-teal-500 via-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Gestão de Custos de Clientes</h2>
                <p class="mt-2 text-teal-100">Gerencie os custos associados a clientes e tipos de manutenção</p>
            </div>
            <div class="mt-4 lg:mt-0">
                <button wire:click="openModal"
                    class="inline-flex items-center px-6 py-3 bg-white text-teal-700 rounded-lg font-semibold shadow-lg hover:bg-teal-50 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Custo de Cliente
                </button>
            </div>
        </div>

        {{-- Estatísticas --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a2 2 0 00-2-2h-3m-2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['total_costs'] }}</p>
                        <p class="text-sm text-white/80">Custos Totais</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['unique_clients'] }}</p>
                        <p class="text-sm text-white/80">Clientes Únicos</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ number_format($this->stats['avg_cost_mzn'], 2) }} MZN</p>
                        <p class="text-sm text-white/80">Custo Médio/Hora</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros e Pesquisa --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Pesquisar
                    </label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Cliente ou tipo de manutenção...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        @if ($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cliente</label>
                    <select wire:model.live="clientFilter"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Manutenção</label>
                    <select wire:model.live="maintenanceTypeFilter"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período Efetivo</label>
                    <input wire:model.live.debounce.300ms="dateRange" type="text"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="Ex: 01/01/2025 - 31/12/2025">
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar de Ações --}}
    <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 rounded-t-xl shadow border border-gray-200 dark:border-gray-700 p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="openModal"
                class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Novo
            </button>
            <button wire:click="export('excel')"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar Excel
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $clientCosts->total() }} custos de clientes encontrados
        </div>
    </div>

    {{-- Tabela de Custos de Clientes --}}
    <div class="bg-white dark:bg-gray-800 rounded-b-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" wire:model="selectAll" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('client_name')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Cliente</span>
                                @if ($sortBy === 'client_name')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('maintenance_type_name')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Tipo de Manutenção</span>
                                @if ($sortBy === 'maintenance_type_name')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('cost_mzn')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Custo (MZN)</span>
                                @if ($sortBy === 'cost_mzn')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('cost_usd')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Custo (USD)</span>
                                @if ($sortBy === 'cost_usd')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('effective_date')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Data Efetiva</span>
                                @if ($sortBy === 'effective_date')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($clientCosts as $cost)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" wire:model="selectedCosts.{{ $cost->id }}" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $cost->client->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $cost->maintenanceType->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ number_format($cost->cost_mzn, 2) }} MZN</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ number_format($cost->cost_usd, 2) }} USD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $cost->effective_date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $cost->id }})"
                                        class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300 transition-colors"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $cost->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a2 2 0 00-2-2h-3m-2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum custo de cliente encontrado</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando o seu primeiro custo de cliente.</p>
                                    <button wire:click="openModal"
                                        class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Criar custo
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bulk Actions --}}
        @if (!empty($selectedCosts))
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ count(array_filter($selectedCosts)) }} custos selecionados
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="bulkUpdateCosts(10)"
                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            Aumentar 10%
                        </button>
                        <button wire:click="bulkUpdateCosts(-10)"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Reduzir 10%
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Paginação --}}
        @if ($clientCosts->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Mostrando {{ $clientCosts->firstItem() }} a {{ $clientCosts->lastItem() }} de {{ $clientCosts->total() }} resultados
                    </div>
                    <div class="flex-1 flex justify-end">
                        {{ $clientCosts->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Criar/Editar Custo de Cliente --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-10 opacity-90 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 dark:bg-teal-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a2 2 0 00-2-2h-3m-2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ $editingId ? 'Editar Custo de Cliente' : 'Novo Custo de Cliente' }}
                                </h3>
                                <div class="mt-4">
                                    <form wire:submit.prevent="save" class="space-y-4">
                                        <div>
                                            <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                                            <select wire:model.live="client_id" id="client_id"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('client_id') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                                <option value="">Selecione um cliente</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('client_id') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="maintenance_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Manutenção</label>
                                            <select wire:model.live="maintenance_type_id" id="maintenance_type_id"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('maintenance_type_id') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                                <option value="">Selecione um tipo</option>
                                                @foreach($maintenanceTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('maintenance_type_id') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="cost_mzn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custo (MZN)</label>
                                            <input wire:model.live="cost_mzn" id="cost_mzn" type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('cost_mzn') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 500.00">
                                            @error('cost_mzn') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="cost_usd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custo (USD)</label>
                                            <input wire:model.live="cost_usd" id="cost_usd" type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('cost_usd') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 7.80">
                                            @error('cost_usd') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="effective_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Efetiva</label>
                                            <input wire:model.live="effective_date" id="effective_date" type="date"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('effective_date') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                            @error('effective_date') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

    {{-- Modal de Confirmação de Exclusão --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-10 opacity-90 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Confirmar Exclusão
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Tem certeza que deseja eliminar este custo de cliente? Esta ação não pode ser desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg wire:loading wire:target="delete" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

    {{-- Notificações --}}
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('info'))
        <div class="fixed bottom-4 right-4 z-50 bg-blue-500 text-white px-4 py-3 rounded-lg shadow-lg">
            {{ session('info') }}
        </div>
    @endif
</div>