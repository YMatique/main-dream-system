<div>

   

     <div class="bg-gradient-to-r from-cyan-600 via-teal-700 to-emerald-800 rounded-xl shadow-lg p-6 text-white mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Gestão de Clientes</h2>
                <p class="mt-2 text-emerald-100">Gerencie os clientes da sua empresa e suas informações de contacto</p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-col lg:flex-row gap-4">
                <!-- View Mode Toggle -->
                <button wire:click="toggleViewMode" 
                        class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg font-semibold shadow-lg hover:bg-white/20 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    @if($viewMode === 'grid')
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Vista de Tabela
                    @else
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Vista de Grelha
                    @endif
                </button>

                <!-- Export Button -->
                <button wire:click="export" 
                        class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg font-semibold shadow-lg hover:bg-white/20 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar
                </button>

                <button wire:click="create"
                    class="inline-flex items-center px-6 py-3 bg-white text-emerald-700 rounded-lg font-semibold shadow-lg hover:bg-emerald-50 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Cliente
                </button>
            </div>
        </div>
        {{-- Estatísticas --}}
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['total'] }}</p>
                        <p class="text-sm text-white/80">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['inactive'] }}</p>
                        <p class="text-sm text-white/80">Inativos</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-2xl font-bold">{{ $this->stats['with_costs'] }}</p>
                        <p class="text-sm text-white/80">Com Custos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Search -->
                <div class="space-y-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesquisar</label>
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           id="search"
                           placeholder="Nome, descrição, email..."
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div class="space-y-2">
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.live="statusFilter" 
                            id="statusFilter"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos os status</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div class="space-y-2">
                    <label for="perPage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Itens por página</label>
                    <select wire:model.live="perPage" 
                            id="perPage"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">&nbsp;</label>
                    <button wire:click="$set('search', ''); $set('statusFilter', '')" 
                            class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-lg font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-gray-500">
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    @if($viewMode === 'grid')
        <!-- Grid View -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($clients as $client)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($client->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $client->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->client_costs_count }} custo(s)</p>
                                </div>
                            </div>
                            <button wire:click="toggleStatus({{ $client->id }})"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $client->is_active 
                                        ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                            </button>
                        </div>

                        @if($client->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $client->description }}</p>
                        @endif

                        <div class="space-y-2 mb-4">
                            @if($client->email)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate" title="{{ $client->email }}">{{ $client->email }}</span>
                                </div>
                            @endif
                            @if($client->phone)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $client->phone }}</span>
                                </div>
                            @endif
                            @if($client->address)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate" title="{{ $client->address }}">{{ Str::limit($client->address, 30) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $client->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </button>
                                @if($client->client_costs_count > 0)
                                    <button wire:click="viewCosts({{ $client->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-yellow-300 dark:border-yellow-600 rounded-md text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900 hover:bg-yellow-100 dark:hover:bg-yellow-800 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Custos
                                    </button>
                                @endif
                            </div>
                            <button wire:click="confirmDelete({{ $client->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-600 rounded-md text-sm font-medium text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900 hover:bg-red-100 dark:hover:bg-red-800 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Nenhum cliente encontrado</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comece criando o seu primeiro cliente.</p>
                        <div class="mt-6">
                            <button wire:click="openModal" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-sm text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Criar primeiro cliente
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <!-- Table View -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Contacto
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Endereço
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Custos
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($clients as $client)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">{{ substr($client->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                @if($client->description)
                                                    {{ Str::limit($client->description, 50) }}
                                                @else
                                                    Sem descrição
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        @if($client->email)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="truncate max-w-xs" title="{{ $client->email }}">{{ $client->email }}</span>
                                            </div>
                                        @endif
                                        @if($client->phone)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span>{{ $client->phone }}</span>
                                            </div>
                                        @endif
                                        @if(!$client->email && !$client->phone)
                                            <span class="text-sm text-gray-400 italic">Sem contacto</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate" title="{{ $client->address }}">
                                        {{ $client->address ?: 'Sem endereço' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($client->client_costs_count > 0)
                                        <button wire:click="viewCosts({{ $client->id }})"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            {{ $client->client_costs_count }} custo(s)
                                        </button>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Sem custos
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $client->id }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $client->is_active 
                                                ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' 
                                                : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' }}">
                                        <div class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $client->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                        {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="edit({{ $client->id }})" 
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                                title="Editar cliente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="duplicateClient({{ $client->id }})" 
                                                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 transition-colors"
                                                title="Duplicar cliente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $client->id }})" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Eliminar cliente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Nenhum cliente encontrado</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comece criando o seu primeiro cliente.</p>
                                        <div class="mt-6">
                                            <button wire:click="create" 
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-sm text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Criar primeiro cliente
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} de {{ $clients->total() }} resultados
                        </div>
                        <div class="flex-1 flex justify-end">
                            {{ $clients->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-70 opacity-85 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                             <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="save">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        {{ $editingId ? 'Editar Cliente' : 'Novo Cliente' }}
                                    </h3>
                                    <div class="mt-6 space-y-4">
                                        <!-- Name -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
                                            <input wire:model="name" 
                                                   type="text" 
                                                   id="name" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            @error('name') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                                            <textarea wire:model="description" 
                                                      id="description" 
                                                      rows="3" 
                                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                            @error('description') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                            <input wire:model="email" 
                                                   type="email" 
                                                   id="email" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            @error('email') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                                            <input wire:model="phone" 
                                                   type="text" 
                                                   id="phone" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            @error('phone') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Address -->
                                        <div>
                                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                                            <textarea wire:model="address" 
                                                      id="address" 
                                                      rows="2" 
                                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                            @error('address') 
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Active Status -->
                                        <div class="flex items-center">
                                            <input wire:model="is_active" 
                                                   id="is_active" 
                                                   type="checkbox" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                                            <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                Cliente ativo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                {{ $editingId ? 'Atualizar' : 'Criar' }}
                            </button>
                            <button wire:click="closeModal" 
                                    type="button" 
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-700 bg-opacity-70 opacity-85 dark:bg-zinc-900 dark:bg-opacity-80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Eliminar Cliente
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Tem a certeza que deseja eliminar este cliente? Esta ação não pode ser desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="delete" 
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Eliminar
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" 
                                type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Costs Modal -->
    @if($showCostsModal && $viewingCostsId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCostsModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-start justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Custos do Cliente
                            </h3>
                            <button wire:click="closeCostsModal" 
                                    class="ml-3 flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-6">
                            @if($this->clientCosts->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tipo de Manutenção
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Custo MZN
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Custo USD
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Data Vigência
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($this->clientCosts as $cost)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $cost->maintenanceType->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ number_format($cost->cost_mzn, 2) }} MZN
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ number_format($cost->cost_usd, 2) }} USD
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $cost->effective_date->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">Nenhum custo cadastrado</h3>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Este cliente ainda não tem custos associados.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeCostsModal" 
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading States -->
    <div wire:loading.flex class="fixed inset-0 z-50 items-center justify-center bg-zinc-500 opacity-75">
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 flex items-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <div class="text-gray-900 dark:text-white font-medium">A processar...</div>
        </div>
    </div>


     @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('info') }}</span>
                <button @click="show = false" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('warning'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 4500)"
            class="fixed top-4 right-4 z-50 bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
                <span>{{ session('warning') }}</span>
                <button @click="show = false" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif



    <!-- Estilos CSS adicionais -->
<style>
    /* Animações suaves para transições */
    .transition-colors {
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    
    .transition-shadow {
        transition: box-shadow 0.15s ease-in-out;
    }
    
    /* Truncate text com tooltip */
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Line clamp para descrições */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Loading spinner customizado */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Hover effects para cards */
    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Custom scrollbar para modal de custos */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Dark mode scrollbar */
    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: #374151;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #6b7280;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    </style>
</div>