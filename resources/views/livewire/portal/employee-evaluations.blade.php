{{-- resources/views/livewire/portal/employee-evaluations.blade.php --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Minhas Avaliações</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Histórico completo das suas avaliações de desempenho</p>
                </div>
                <div class="flex space-x-3">
                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Relatório
                    </button>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros de Busca</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ano</label>
                        <select wire:model.live="yearFilter" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Todos os anos</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mês</label>
                        <select wire:model.live="monthFilter" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Todos os meses</option>
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model.live="statusFilter" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Todos os status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Por página</label>
                        <select wire:model.live="perPage" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button wire:click="resetFilters" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lista de Avaliações --}}
        <div class="space-y-6">
            @if($evaluations->count() > 0)
                @foreach($evaluations as $evaluation)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Header da Avaliação --}}
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="p-3 rounded-full bg-{{ $evaluation->performance_color }}-100 dark:bg-{{ $evaluation->performance_color }}-900/20">
                                        <svg class="w-6 h-6 text-{{ $evaluation->performance_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Avaliação {{ $evaluation->evaluation_period_formatted }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                                            Avaliado por: <span class="font-medium">{{ $evaluation->evaluator->name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-{{ $evaluation->performance_color }}-600">
                                        {{ number_format($evaluation->final_percentage, 1) }}%
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $evaluation->performance_color }}-100 text-{{ $evaluation->performance_color }}-800 dark:bg-{{ $evaluation->performance_color }}-900/20">
                                        {{ $evaluation->performance_class }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Status e Estágios de Aprovação --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Status Atual --}}
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Status da Avaliação</h4>
                                    <div class="flex items-center space-x-3">
                                        @php
                                            $statusConfig = [
                                                'draft' => ['color' => 'gray', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
                                                'submitted' => ['color' => 'blue', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                                                'in_approval' => ['color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'approved' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'rejected' => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
                                            ];
                                            $config = $statusConfig[$evaluation->status] ?? $statusConfig['draft'];
                                        @endphp
                                        
                                        <div class="p-2 rounded-lg bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/20">
                                            <svg class="w-5 h-5 text-{{ $config['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $evaluation->status_display }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $evaluation->approved_at?->format('d/m/Y H:i') ?? ($evaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estágios de Aprovação --}}
                                @if($evaluation->status === 'in_approval' || $evaluation->status === 'approved' || $evaluation->status === 'rejected')
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Estágios de Aprovação</h4>
                                    <div class="space-y-3">
                                        @foreach($evaluation->approvals as $approval)
                                            <div class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                                @php
                                                    $stageStatusConfig = [
                                                        'pending' => ['color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'approved' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'rejected' => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'waiting' => ['color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'cancelled' => ['color' => 'gray', 'icon' => 'M6 18L18 6M6 6l12 12']
                                                    ];
                                                    $stageConfig = $stageStatusConfig[$approval->status] ?? $stageStatusConfig['waiting'];
                                                @endphp
                                                
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full bg-{{ $stageConfig['color'] }}-100 dark:bg-{{ $stageConfig['color'] }}-900/20 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-{{ $stageConfig['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stageConfig['icon'] }}"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            Estágio {{ $approval->stage_number }}: {{ $approval->stage_name }}
                                                        </p>
                                                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-{{ $stageConfig['color'] }}-100 text-{{ $stageConfig['color'] }}-800 dark:bg-{{ $stageConfig['color'] }}-900/20">
                                                            {{ ucfirst($approval->status) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $approval->approver->name ?? 'Aprovador não definido' }}
                                                    </p>
                                                    @if($approval->comments)
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 italic">
                                                            "{{ $approval->comments }}"
                                                        </p>
                                                    @endif
                                                    @if($approval->reviewed_at)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ $approval->reviewed_at->format('d/m/Y H:i') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Recomendações (se houver) --}}
                            @if($evaluation->recommendations)
                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Recomendações</h5>
                                <p class="text-blue-800 dark:text-blue-200">{{ $evaluation->recommendations }}</p>
                            </div>
                            @endif

                            {{-- Ações --}}
                            <div class="mt-6 flex items-center justify-end space-x-3">
                                <button wire:click="viewDetails({{ $evaluation->id }})" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalhes
                                </button>
                                
                                @if($evaluation->status === 'approved')
                                    <button wire:click="printEvaluation({{ $evaluation->id }})" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Imprimir
                                    </button>
                                    
                                    <button wire:click="downloadEvaluation({{ $evaluation->id }})" 
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download PDF
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Paginação --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4">
                    {{ $evaluations->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma avaliação encontrada</h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                            Não foram encontradas avaliações com os filtros selecionados. Ajuste os filtros ou aguarde novas avaliações.
                        </p>
                        <button wire:click="resetFilters" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Limpar Filtros
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de Detalhes --}}
    @if($showDetailsModal && $selectedEvaluation)
    <div class="fixed inset-0 bg-zinc-800/75   flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="sticky top-0 bg-white dark:bg-gray-800 p-6 border-b border-gray-200 dark:border-gray-700 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Detalhes da Avaliação - {{ $selectedEvaluation->evaluation_period_formatted }}
                    </h3>
                    <button wire:click="closeDetailsModal" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-8">
                {{-- Resumo da Avaliação --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="text-center p-6 bg-gradient-to-r from-{{ $selectedEvaluation->performance_color }}-50 to-{{ $selectedEvaluation->performance_color }}-100 dark:from-{{ $selectedEvaluation->performance_color }}-900/20 dark:to-{{ $selectedEvaluation->performance_color }}-800/20 rounded-xl">
                        <div class="text-4xl font-bold text-{{ $selectedEvaluation->performance_color }}-600 mb-2">
                            {{ number_format($selectedEvaluation->final_percentage, 1) }}%
                        </div>
                        <div class="text-sm font-medium text-{{ $selectedEvaluation->performance_color }}-700 dark:text-{{ $selectedEvaluation->performance_color }}-300">Performance Final</div>
                    </div>
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $selectedEvaluation->performance_class }}
                        </div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Classificação</div>
                    </div>
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                            {{ $selectedEvaluation->status_display }}
                        </div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</div>
                    </div>

                      <div>
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Avaliado por:</span>
        <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
            {{ $selectedEvaluation->evaluator->name ?? 'N/A' }}
        </span>
    </div>
    <div>
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Horas Trabalhadas no Período:</span>
        <span class="block text-lg font-semibold text-blue-600 dark:text-blue-400 mt-1">
            @php
                $period = $selectedEvaluation->evaluation_period;
                $monthlyHours = $selectedEvaluation->employee->getTotalHoursForMonth($period->year, $period->month);
            @endphp
            {{ number_format($monthlyHours, 1) }}h
        </span>
    </div>
    <div>
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Data de Submissão:</span>
        <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
            {{ $selectedEvaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A' }}
        </span>
    </div>
                </div>
                {{-- HORAS TRABALHADAS --}}

                
                {{-- Métricas Detalhadas --}}
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Avaliação por Métrica</h4>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($selectedEvaluation->responses as $response)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-grow">
                                        <h5 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $response->metric->name }}</h5>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $response->metric->type_display }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                                        Peso: {{ $response->metric->weight }}%
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Avaliado</div>
                                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $response->display_value }}</div>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pontuação</div>
                                        <div class="text-lg font-semibold text-green-600">{{ number_format($response->calculated_score, 1) }}</div>
                                    </div>
                                </div>
                                
                                @if($response->comments)
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                                        <div class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">Comentários</div>
                                        <div class="text-sm text-yellow-700 dark:text-yellow-200">{{ $response->comments }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recomendações --}}
                @if($selectedEvaluation->recommendations)
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recomendações</h4>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $selectedEvaluation->recommendations }}</p>
                    </div>
                </div>
                @endif

                {{-- Informações do Processo --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informações do Processo</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Avaliado por:</span>
                            <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $selectedEvaluation->evaluator->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Data de Submissão:</span>
                            <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $selectedEvaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A' }}
                            </span>
                        </div>
                        @if($selectedEvaluation->approved_at)
                        <div>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Data de Aprovação:</span>
                            <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $selectedEvaluation->approved_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Aprovado por:</span>
                            <span class="block text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $selectedEvaluation->approvedBy->name ?? 'N/A' }}
                            </span>
                        </div>
                        @endif
                        @if($selectedEvaluation->rejected_at)
                        <div class="md:col-span-2">
                            <span class="text-sm font-medium text-red-600 dark:text-red-400">Motivo da Rejeição:</span>
                            <span class="block text-lg font-semibold text-red-700 dark:text-red-300 mt-1">
                                {{ $selectedEvaluation->rejection_reason ?? 'N/A' }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Histórico de Aprovações Detalhado --}}
                @if($selectedEvaluation->approvals && $selectedEvaluation->approvals->count() > 0)
                <div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Histórico de Aprovações</h4>
                    <div class="space-y-4">
                        @foreach($selectedEvaluation->approvals as $approval)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-6 {{ $approval->status === 'approved' ? 'bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-800' : ($approval->status === 'rejected' ? 'bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-800' : ($approval->status === 'pending' ? 'bg-yellow-50 dark:bg-yellow-900/10 border-yellow-200 dark:border-yellow-800' : '')) }}">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @php
                                            $stageStatusConfig = [
                                                'pending' => ['color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'approved' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'rejected' => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'waiting' => ['color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'cancelled' => ['color' => 'gray', 'icon' => 'M6 18L18 6M6 6l12 12']
                                            ];
                                            $stageConfig = $stageStatusConfig[$approval->status] ?? $stageStatusConfig['waiting'];
                                        @endphp
                                        
                                        <div class="w-12 h-12 rounded-xl bg-{{ $stageConfig['color'] }}-100 dark:bg-{{ $stageConfig['color'] }}-900/20 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-{{ $stageConfig['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stageConfig['icon'] }}"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                Estágio {{ $approval->stage_number }}: {{ $approval->stage_name }}
                                            </h5>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $stageConfig['color'] }}-100 text-{{ $stageConfig['color'] }}-800 dark:bg-{{ $stageConfig['color'] }}-900/20 dark:text-{{ $stageConfig['color'] }}-300">
                                                {{ ucfirst($approval->status) }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                                            <span class="font-medium">Aprovador:</span> {{ $approval->approver->name ?? 'Aprovador não definido' }}
                                        </p>
                                        @if($approval->reviewed_at)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                                <span class="font-medium">Data:</span> {{ $approval->reviewed_at->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                        @if($approval->comments)
                                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comentários:</p>
                                                <p class="text-gray-600 dark:text-gray-400 italic">"{{ $approval->comments }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Botões de Ação no Modal --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($selectedEvaluation->status === 'approved')
                        <button wire:click="printEvaluation({{ $selectedEvaluation->id }})" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir
                        </button>
                        
                        <button wire:click="downloadEvaluation({{ $selectedEvaluation->id }})" 
                                class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                    @endif
                    
                    <button wire:click="closeDetailsModal" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@section('scripts')
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('open-print-page', (url) => {
        window.open(url, '_blank');
    });
});
</script>
@endsection