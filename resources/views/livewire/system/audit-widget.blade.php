<div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
    <!-- Header do Widget -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Atividades Recentes</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Últimas ações no sistema</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <button wire:click="loadData" class="p-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('system.audit-logs') }}" class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                Ver Todos →
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-zinc-50 dark:bg-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Ações Hoje</p>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $todayStats['total_actions'] }}</p>
                </div>
                <div class="p-1.5 bg-blue-100 dark:bg-blue-900/30 rounded">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-50 dark:bg-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Usuários Ativos</p>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $todayStats['unique_users'] }}</p>
                </div>
                <div class="p-1.5 bg-green-100 dark:bg-green-900/30 rounded">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-50 dark:bg-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Empresas</p>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $todayStats['companies_active'] }}</p>
                </div>
                <div class="p-1.5 bg-purple-100 dark:bg-purple-900/30 rounded">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-50 dark:bg-zinc-700/50 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Críticas</p>
                    <p class="text-lg font-bold text-{{ $todayStats['critical_actions'] > 0 ? 'red' : 'green' }}-600">{{ $todayStats['critical_actions'] }}</p>
                </div>
                <div class="p-1.5 bg-{{ $todayStats['critical_actions'] > 0 ? 'red' : 'green' }}-100 dark:bg-{{ $todayStats['critical_actions'] > 0 ? 'red' : 'green' }}-900/30 rounded">
                    <svg class="w-4 h-4 text-{{ $todayStats['critical_actions'] > 0 ? 'red' : 'green' }}-600 dark:text-{{ $todayStats['critical_actions'] > 0 ? 'red' : 'green' }}-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Atividades -->
    <div class="space-y-3">
        @forelse($recentActivities as $activity)
            <div class="flex items-start space-x-3 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-700/30 hover:bg-zinc-100 dark:hover:bg-zinc-700/50 transition-colors">
                <!-- Ícone da ação -->
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($activity['icon'] === 'plus-circle')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @elseif($activity['icon'] === 'pencil')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            @elseif($activity['icon'] === 'trash')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            @endif
                        </svg>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                            <span class="font-bold">{{ $activity['user_name'] }}</span>
                            <span class="font-normal text-zinc-600 dark:text-zinc-400">{{ $activity['action_label'] }}</span>
                            <span class="font-normal text-zinc-500 dark:text-zinc-400">{{ $activity['model_label'] }}</span>
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 flex-shrink-0 ml-2">
                            {{ $activity['time_ago'] }}
                        </p>
                    </div>
                    
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-800 dark:bg-{{ $activity['color'] }}-900/30 dark:text-{{ $activity['color'] }}-300">
                            {{ $activity['action_label'] }}
                        </span>
                        
                        @if($activity['company_name'] !== 'Sistema')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-zinc-100 text-zinc-700 dark:bg-zinc-600 dark:text-zinc-300">
                                {{ $activity['company_name'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">Nenhuma atividade</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Não há atividades registradas ainda.</p>
            </div>
        @endforelse
    </div>
</div>