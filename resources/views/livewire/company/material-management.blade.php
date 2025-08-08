<div wire:init="log('MaterialManagement initialized')" class="space-y-6">
    {{-- Header com estatísticas --}}
    <div class="bg-gradient-to-r from-teal-500 via-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Gestão de Materiais</h2>
                <p class="mt-2 text-teal-100">Gerencie os materiais utilizados nas ordens de reparação</p>
            </div>
            <div class="mt-4 lg:mt-0">
                <button wire:click="openModal"
                    class="inline-flex items-center px-6 py-3 bg-white text-teal-700 rounded-lg font-semibold shadow-lg hover:bg-teal-50 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Material
                </button>
            </div>
        </div>

        {{-- Estatísticas --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ number_format($this->stats['avg_cost_per_unit_mzn'], 2) }} MZN</p>
                        <p class="text-sm text-white/80">Custo Médio/Unidade</p>
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
                            placeholder="Nome ou descrição do material...">
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="1">Ativos</option>
                        <option value="0">Inativos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Custo Mínimo (MZN)</label>
                    <input wire:model.live.debounce.300ms="costRangeMin" type="number" step="0.01" min="0"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="Ex: 100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Custo Máximo (MZN)</label>
                    <input wire:model.live.debounce.300ms="costRangeMax" type="number" step="0.01" min="0"
                        class="w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="Ex: 1000">
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
            <button wire:click="bulkUpdateCosts(10)"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors"
                title="Aumentar custos em 10%">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                Aumentar 10%
            </button>
            <button wire:click="bulkUpdateCosts(-10)"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors"
                title="Reduzir custos em 10%">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                Reduzir 10%
            </button>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $materials->count() ?? 0 }} materiais encontrados
        </div>
    </div>

    {{-- Tabela de Materiais --}}
    <div class="bg-white dark:bg-gray-800 rounded-b-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" wire:model="selectAll" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('name')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Nome</span>
                                @if ($sortBy === 'name')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('description')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Descrição</span>
                                @if ($sortBy === 'description')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('unit')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Unidade</span>
                                @if ($sortBy === 'unit')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('cost_per_unit_mzn')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Custo/Unidade (MZN)</span>
                                @if ($sortBy === 'cost_per_unit_mzn')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('cost_per_unit_usd')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Custo/Unidade (USD)</span>
                                @if ($sortBy === 'cost_per_unit_usd')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <button wire:click="sortBy('stock_quantity')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
                                <span>Quantidade em Estoque</span>
                                @if ($sortBy === 'stock_quantity')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($materials as $material)
                        @php
                            // Debug: Log type of $material
                            \Illuminate\Support\Facades\Log::info('Material type in view', ['type' => is_object($material) ? get_class($material) : gettype($material)]);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" wire:model="selectedMaterials.{{ $material->id }}" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                                    @if(!is_object($material) || !isset($material->id)) disabled title="Material inválido" @endif>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $material->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ \Illuminate\Support\Str::limit($material->description ?? '', 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $material->unit ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ number_format($material->cost_per_unit_mzn ?? 0, 2) }} MZN</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ number_format($material->cost_per_unit_usd ?? 0, 2) }} USD</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $material->stock_quantity ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $material->id }})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition-colors {{ $material->is_active ? 'bg-teal-100 text-teal-800 hover:bg-teal-200 dark:bg-teal-900 dark:text-teal-200' : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' }}"
                                    @if(!is_object($material) || !isset($material->id)) disabled @endif>
                                    @if ($material->is_active)
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ativo
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Inativo
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $material->id }})"
                                        class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300 transition-colors"
                                        title="Editar"
                                        @if(!is_object($material) || !isset($material->id)) disabled @endif>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $material->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                        title="Eliminar"
                                        @if(!is_object($material) || !isset($material->id)) disabled @endif>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum material encontrado</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando o seu primeiro material.</p>
                                    <button wire:click="openModal"
                                        class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Criar material
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bulk Actions --}}
        @if (!empty($selectedMaterials))
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ count(array_filter($selectedMaterials)) }} materiais selecionados
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="bulkActivate({{ json_encode(array_keys(array_filter($selectedMaterials))) }})"
                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            Ativar
                        </button>
                        <button wire:click="bulkDeactivate({{ json_encode(array_keys(array_filter($selectedMaterials))) }})"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Desativar
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Paginação --}}
        @if ($materials && $materials->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Mostrando {{ $materials->firstItem() ?? 0 }} a {{ $materials->lastItem() ?? 0 }} de {{ $materials->total() ?? 0 }} resultados
                    </div>
                    <div class="flex-1 flex justify-end">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Criar/Editar Material --}}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ $editingId ? 'Editar Material' : 'Novo Material' }}
                                </h3>
                                <div class="mt-4">
                                    <form wire:submit.prevent="save" class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                            <input wire:model.live="name" id="name" type="text"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: Parafuso M6">
                                            @error('name') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                                            <textarea wire:model.live="description" id="description" rows="4"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('description') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: Parafuso de aço inoxidável M6 x 20mm"></textarea>
                                            @error('description') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidade</label>
                                            <input wire:model.live="unit" id="unit" type="text"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('unit') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: Unidade, Kg, Litro">
                                            @error('unit') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="cost_per_unit_mzn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custo/Unidade (MZN)</label>
                                            <input wire:model.live="cost_per_unit_mzn" id="cost_per_unit_mzn" type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('cost_per_unit_mzn') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 50.00">
                                            @error('cost_per_unit_mzn') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="cost_per_unit_usd" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custo/Unidade (USD)</label>
                                            <input wire:model.live="cost_per_unit_usd" id="cost_per_unit_usd" type="number" step="0.01" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('cost_per_unit_usd') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 0.78">
                                            @error('cost_per_unit_usd') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade em Estoque</label>
                                            <input wire:model.live="stock_quantity" id="stock_quantity" type="number" min="0"
                                                class="mt-1 w-full px-3 py-2 border {{ $errors->has('stock_quantity') ? 'border-red-300' : 'border-gray-300 dark:border-gray-600' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                placeholder="Ex: 100">
                                            @error('stock_quantity') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="flex items-center">
                                                <input wire:model.defer="is_active" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 dark:border-gray-600 rounded">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Material ativo</span>
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
                                        Tem certeza que deseja eliminar este material? Esta ação não pode ser desfeita.
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