{{-- resources/views/livewire/system/activity-logs-management.blade.php --}}

<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Logs de Actividade</h1>
            <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">
                Monitorize todas as actividades do sistema em tempo real
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
            <button wire:click="exportLogs" 
                    class="inline-flex items-center px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar Logs
            </button>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">Hoje</dt>
                            <dd class="text-lg font-medium text-zinc-900 dark:text-white">{{ number_format($stats['total_today']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">Esta Semana</dt>
                            <dd class="text-lg font-medium text-zinc-900 dark:text-white">{{ number_format($stats['total_week']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">Este Mês</dt>
                            <dd class="text-lg font-medium text-zinc-900 dark:text-white">{{ number_format($stats['total_month']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400 truncate">Erros Hoje</dt>
                            <dd class="text-lg font-medium text-zinc-900 dark:text-white">{{ number_format($stats['errors_today']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-zinc-800 shadow rounded-lg">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                <!-- Pesquisa -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Pesquisar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Pesquisar logs..."
                               class="block w-full pl-10 pr-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Usuário -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Usuário</label>
                    <select wire:model.live="userFilter" 
                            class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Empresa -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Empresa</label>
                    <select wire:model.live="companyFilter" 
                            class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas as empresas</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Categoria -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Categoria</label>
                    <select wire:model.live="categoryFilter" 
                            class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nível -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nível</label>
                    <select wire:model.live="levelFilter" 
                            class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos os níveis</option>
                        @foreach($levels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filtros de data -->
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Data Inicial</label>
                    <input wire:model.live="dateFrom" 
                           type="date" 
                           class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Data Final</label>
                    <input wire:model.live="dateTo" 
                           type="date" 
                           class="block w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button wire:click="clearFilters" 
                            class="w-full px-4 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Logs -->
    <div class="bg-white dark:bg-zinc-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white">
                    Actividades Recentes
                </h3>
                <div class="flex items-center space-x-2">
                    <select wire:model.live="perPage" 
                            class="text-sm border border-zinc-300 dark:border-zinc-600 rounded px-3 py-1 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-zinc-700 dark:text-zinc-300">por página</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Data/Hora
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Acção
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Descrição
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Categoria
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Nível
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            Acções
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-zinc-300 dark:bg-zinc-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                                                {{ substr($log->user_name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                            {{ $log->user_name }}
                                        </div>
                                        @if($log->company)
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $log->company->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                <code class="px-2 py-1 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">
                                    {{ $log->action }}
                                </code>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-900 dark:text-white">
                                <div class="max-w-xs truncate">
                                    {{ $log->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $this->getCategoryBadgeClass($log->category) }}">
                                    {{ $categories[$log->category] ?? $log->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $this->getLevelBadgeClass($log->level) }}">
                                    {{ $levels[$log->level] ?? $log->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="showDetails({{ $log->id }})" 
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    Ver Detalhes
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                <svg class="mx-auto h-12 w-12 text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Nenhuma actividade encontrada com os filtros seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Detalhes -->
    @if($showDetailsModal && $selectedLog)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-zinc-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-white">Detalhes da Actividade</h3>
                        <button wire:click="closeModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Informações Básicas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Data/Hora</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Usuário</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->user_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Empresa</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->company_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">IP</label>
                                <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->ip_address ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Acção e Categoria -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Acção</label>
                                <p class="mt-1">
                                    <code class="px-2 py-1 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">{{ $selectedLog->action }}</code>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Categoria</label>
                                <p class="mt-1">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $this->getCategoryBadgeClass($selectedLog->category) }}">
                                        {{ $categories[$selectedLog->category] ?? $selectedLog->category }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Descrição</label>
                            <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->description }}</p>
                        </div>

                        <!-- Modelo Afectado (se aplicável) -->
                        @if($selectedLog->model)
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">Modelo</label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ class_basename($selectedLog->model) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400">ID do Modelo</label>
                                    <p class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $selectedLog->model_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Valores Antigos e Novos -->
                        @if($selectedLog->old_values || $selectedLog->new_values)
                            <div class="space-y-4">
                                @if($selectedLog->old_values)
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">Valores Anteriores</label>
                                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                            <pre class="text-xs text-red-800 dark:text-red-300 whitespace-pre-wrap">{{ json_encode($selectedLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif

                                @if($selectedLog->new_values)
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">Valores Novos</label>
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                                            <pre class="text-xs text-green-800 dark:text-green-300 whitespace-pre-wrap">{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Metadata -->
                        @if($selectedLog->metadata)
                            <div>
                                <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">Metadata</label>
                                <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg p-3">
                                    <pre class="text-xs text-zinc-800 dark:text-zinc-300 whitespace-pre-wrap">{{ json_encode($selectedLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif

                        <!-- Informações Técnicas -->
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <h4 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-3">Informações Técnicas</h4>
                            <div class="grid grid-cols-1 gap-2 text-xs">
                                @if($selectedLog->route)
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500 dark:text-zinc-400">Rota:</span>
                                        <span class="text-zinc-900 dark:text-white font-mono">{{ $selectedLog->route }}</span>
                                    </div>
                                @endif
                                @if($selectedLog->method)
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500 dark:text-zinc-400">Método:</span>
                                        <span class="text-zinc-900 dark:text-white font-mono">{{ $selectedLog->method }}</span>
                                    </div>
                                @endif
                                @if($selectedLog->user_agent)
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500 dark:text-zinc-400">User Agent:</span>
                                        <span class="text-zinc-900 dark:text-white font-mono text-right max-w-xs truncate">{{ $selectedLog->user_agent }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeModal" 
                                class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>