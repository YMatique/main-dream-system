<div class="space-y-6">
    {{-- Header com estatísticas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Aprovação de Avaliações</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerir aprovações de avaliações de desempenho - Sistema
                    Multi-Estágio</p>
            </div>

            {{-- Estatísticas Simplificadas --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mt-6 lg:mt-0">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800">
                    <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Pendentes para Mim</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['pending_for_me'] ?? 0 }}
                    </div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="text-red-600 dark:text-red-400 text-sm font-medium">Críticas para Mim</div>
                    <div class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['critical_for_me'] ?? 0 }}
                    </div>
                </div>
                <div
                    class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-200 dark:border-green-800">
                    <div class="text-green-600 dark:text-green-400 text-sm font-medium">Aprovadas</div>
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                        {{ $stats['total_approved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros Compactos --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
            {{-- Status (Principal) --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Busca --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar
                    Funcionário</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nome ou código..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors">
            </div>

            {{-- Departamento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departamento</label>
                <select wire:model.live="departmentFilter"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                    <option value="">Todos</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Performance --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Performance</label>
                <select wire:model.live="thresholdFilter"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                    <option value="all">Todas</option>
                    <option value="below_threshold">Abaixo de 50%</option>
                    <option value="above_threshold">Acima de 50%</option>
                </select>
            </div>
            {{-- Mês --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mês</label>
                <select wire:model.live="monthFilter" class="w-full px-4 py-3 rounded-lg border border-gray-300...">
                    <option value="">Todos os meses</option>
                    @foreach ($months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Ano --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ano</label>
                <select wire:model.live="yearFilter" class="w-full px-4 py-3 rounded-lg border border-gray-300...">
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Ações rápidas --}}
        <div
            class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center gap-4">
                <button wire:click="clearFilters"
                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                    Limpar Filtros
                </button>

                @if (in_array($statusFilter, ['', 'pending_for_me']) && !empty($selectedEvaluations))
                    <button wire:click="openBulkModal"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Aprovar {{ count($selectedEvaluations) }} Selecionada(s)
                    </button>
                @endif
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $evaluations->total() }} avaliação(ões) encontrada(s)
            </div>
        </div>
    </div>
    {{-- Lista de Avaliações (Cards Compactos em Grid) --}}
    <div>
        @if ($evaluations->count() > 0)
            {{-- Grid de Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($evaluations as $evaluation)
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 h-fit {{ $evaluation->is_below_threshold ? 'ring-2 ring-red-200 dark:ring-red-800' : '' }}">
                        {{-- Card Header --}}
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                {{-- Avatar + Info --}}
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    {{-- Checkbox (se aplicável) --}}
                                    @if (in_array($statusFilter, ['', 'pending_for_me']) && $this->isWaitingForMe($evaluation))
                                        <input type="checkbox" wire:model.live="selectedEvaluations"
                                            value="{{ $evaluation->id }}"
                                            class="w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 flex-shrink-0">
                                    @endif

                                    {{-- Avatar --}}
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                                        {{ strtoupper(substr($evaluation->employee->name, 0, 2)) }}
                                    </div>

                                    {{-- Nome e Departamento --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $evaluation->employee->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $evaluation->employee->department->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Performance Badge --}}
                                <div class="flex flex-col items-end flex-shrink-0">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ number_format($evaluation->final_percentage, 0) }}%
                                    </span>
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full 
                                    {{ $evaluation->performance_color === 'green' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $evaluation->performance_color === 'blue' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    {{ $evaluation->performance_color === 'yellow' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                    {{ $evaluation->performance_color === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                        {{ $evaluation->performance_class }}
                                    </span>
                                </div>
                            </div>

                            {{-- Badge Crítica (se aplicável) --}}
                            @if ($evaluation->is_below_threshold)
                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-medium rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Performance Crítica
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 space-y-3">
                            {{-- Informações Essenciais --}}
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400 block">Período</span>
                                    <span
                                        class="text-gray-900 dark:text-white font-medium">{{ $evaluation->evaluation_period_formatted }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400 block">Código</span>
                                    <span
                                        class="text-gray-900 dark:text-white font-medium">{{ $evaluation->employee->code }}</span>
                                </div>
                            </div>

                            {{-- Status/Estágio --}}
                            @if ($evaluation->status === 'in_approval')
                                @php $stageInfo = $this->getCurrentStageInfo($evaluation); @endphp
                                @if ($stageInfo)
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-blue-900 dark:text-blue-200">
                                                    Estágio {{ $stageInfo['stage_number'] }}
                                                </p>
                                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                                    {{ $stageInfo['stage_name'] }}
                                                </p>
                                            </div>
                                            @if ($stageInfo['is_my_turn'])
                                                <span
                                                    class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full animate-pulse">
                                                    Sua vez
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-medium rounded-full">
                                                    Aguardando
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- Status Simples --}}
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Status</span>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $evaluation->status === 'submitted' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    {{ $evaluation->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $evaluation->status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                        {{ $evaluation->status_display }}
                                    </span>
                                </div>
                            @endif

                            {{-- Avaliador e Data --}}
                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Avaliador:</span>
                                    <span
                                        class="font-medium">{{ Str::limit($evaluation->evaluator->name ?? 'N/A', 15) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Submetida:</span>
                                    <span
                                        class="font-medium">{{ $evaluation->submitted_at?->format('d/m/Y') ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer (Ações) --}}
                        <div
                            class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-600 rounded-b-xl">
                            <div class="flex items-center justify-between">
                                {{-- Botão Ver Detalhes --}}
                                <button wire:click="showEvaluationDetail({{ $evaluation->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Ver Detalhes
                                </button>

                                {{-- Ações de Aprovação (se aplicável) --}}
                                @if ($this->isWaitingForMe($evaluation))
                                    <div class="flex items-center gap-1">
                                        {{-- Aprovar --}}
                                        <button wire:click="openApprovalModal({{ $evaluation->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Aprovar
                                        </button>

                                        {{-- Rejeitar --}}
                                        <button wire:click="openRejectionModal({{ $evaluation->id }})"
                                            class="inline-flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginação --}}
            <div class="flex justify-center pt-8">
                {{ $evaluations->links() }}
            </div>
        @else
            {{-- Estado Vazio --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                <div class="text-center py-16">
                    <div
                        class="w-20 h-20 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-3">Nenhuma avaliação encontrada
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        @if ($statusFilter === 'pending_for_me')
                            Não há avaliações aguardando sua aprovação no momento.
                        @else
                            Não há avaliações que correspondam aos filtros selecionados.
                        @endif
                    </p>

                    {{-- Sugestão de Ação --}}
                    @if ($statusFilter !== '')
                        <button wire:click="clearFilters"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Limpar Filtros
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL: Aprovação (Simplificado) --}}
    @if ($showApprovalModal && $selectedEvaluation)
        <div
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full">
                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Aprovar Avaliação</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $selectedEvaluation->employee->name }} -
                            {{ $selectedEvaluation->evaluation_period_formatted }}
                        </p>
                    </div>
                    <button wire:click="closeApprovalModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-900 dark:text-green-200">
                                    Performance: {{ number_format($selectedEvaluation->final_percentage, 1) }}% -
                                    {{ $selectedEvaluation->performance_class }}
                                </p>
                                @if ($selectedEvaluation->status === 'in_approval')
                                    @php $stageInfo = $this->getCurrentStageInfo($selectedEvaluation); @endphp
                                    @if ($stageInfo)
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            Aprovando: {{ $stageInfo['stage_name'] }} (Estágio
                                            {{ $stageInfo['stage_number'] }})
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Comentários (Opcional)
                        </label>
                        <textarea wire:model="approvalComments" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition-colors resize-none"
                            placeholder="Adicione comentários sobre a aprovação..."></textarea>
                        @error('approvalComments')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeApprovalModal"
                        class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl font-medium transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="approveEvaluation"
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                        Aprovar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Rejeição (Simplificado) --}}
    @if ($showRejectionModal && $selectedEvaluation)
        <div
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full">
                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Rejeitar Avaliação</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $selectedEvaluation->employee->name }} -
                            {{ $selectedEvaluation->evaluation_period_formatted }}
                        </p>
                    </div>
                    <button wire:click="closeRejectionModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-900 dark:text-red-200">
                                    Esta ação irá rejeitar a avaliação
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    O avaliador será notificado e poderá revisar a avaliação
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Motivo da Rejeição <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="rejectionComments" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500 transition-colors resize-none"
                            placeholder="Explique o motivo da rejeição para orientar o avaliador..."></textarea>
                        @error('rejectionComments')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeRejectionModal"
                        class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl font-medium transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="rejectEvaluation"
                        class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                        Rejeitar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Aprovação em Lote --}}
    @if ($showBulkModal)
        <div
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full">
                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Aprovação em Lote</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Aprovar {{ count($selectedEvaluations) }} avaliação(ões) de uma só vez
                        </p>
                    </div>
                    <button wire:click="closeBulkModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-900 dark:text-green-200">
                                    Aprovar múltiplas avaliações
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    Este comentário será aplicado a todas as aprovações
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Comentários (Opcional)
                        </label>
                        <textarea wire:model="bulkComments" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 transition-colors resize-none"
                            placeholder="Comentário que será aplicado a todas as aprovações..."></textarea>
                        @error('bulkComments')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeBulkModal"
                        class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl font-medium transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="bulkApprove"
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                        Aprovar Todas
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Detalhes da Avaliação --}}
    @if ($showDetailModal && $selectedEvaluation)
        <div
            class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                {{-- Header --}}
                <div
                    class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-600 sticky top-0 bg-white dark:bg-gray-800 z-10">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Detalhes da Avaliação</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $selectedEvaluation->employee->name }} -
                            {{ $selectedEvaluation->evaluation_period_formatted }}
                        </p>
                    </div>
                    <button wire:click="closeDetailModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-6">
                    {{-- Informações Principais --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-slate-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Informações do
                                Funcionário</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nome:</span> {{ $selectedEvaluation->employee->name }}
                                </div>
                                <div><span class="font-medium">Código:</span>
                                    {{ $selectedEvaluation->employee->code }}</div>
                                <div><span class="font-medium">Departamento:</span>
                                    {{ $selectedEvaluation->employee->department->name ?? 'N/A' }}</div>
                                <div><span class="font-medium">Período:</span>
                                    {{ $selectedEvaluation->evaluation_period_formatted }}</div>
                                     {{-- <div><span class="font-medium">Horas Trabalhadas: </span>
                                    {{ $selectedEvaluation->employee->repairOrderForm2Employees }}</div> --}}
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Resultado da Avaliação
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Avaliador:</span>
                                    {{ $selectedEvaluation->evaluator->name ?? 'N/A' }}</div>
                                <div><span class="font-medium">Status:</span>
                                    {{ $selectedEvaluation->status_display }}</div>
                                <div>
                                    <span class="font-medium">Performance:</span>
                                    <span class="font-bold text-{{ $selectedEvaluation->performance_color }}-600">
                                        {{ number_format($selectedEvaluation->final_percentage, 1) }}% -
                                        {{ $selectedEvaluation->performance_class }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Submetida em:</span>
                                    {{ $selectedEvaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Progresso Multi-Estágio --}}
                    @if ($selectedEvaluation->approvals->count() > 0)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Progresso de Aprovação
                            </h4>
                            <div class="space-y-3">
                                @foreach ($selectedEvaluation->approvals as $approval)
                                    <div
                                        class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center
                                        {{ $approval->status === 'approved' ? 'bg-green-600 text-white' : '' }}
                                        {{ $approval->status === 'pending' ? 'bg-blue-600 text-white' : '' }}
                                        {{ $approval->status === 'waiting' ? 'bg-gray-300 text-gray-600' : '' }}
                                        {{ $approval->status === 'rejected' ? 'bg-red-600 text-white' : '' }}">
                                                @if ($approval->status === 'approved')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @elseif($approval->status === 'pending')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif($approval->status === 'rejected')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                @else
                                                    {{ $approval->stage_number }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $approval->stage_name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $approval->approver->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $approval->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                        {{ $approval->status === 'pending' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                        {{ $approval->status === 'waiting' ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                        {{ $approval->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                                {{ $approval->status_display }}
                                            </span>
                                            @if ($approval->reviewed_at)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $approval->reviewed_at->format('d/m H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Respostas das Métricas --}}
                    @if ($selectedEvaluation->responses->count() > 0)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Respostas das Métricas
                            </h4>
                            <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Métrica</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Valor</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Score</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Comentários</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach ($selectedEvaluation->responses as $response)
                                                <tr>
                                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                                        {{ $response->metric->name ?? 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                                        {{ $response->display_value }}</td>
                                                    <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">
                                                        {{ number_format($response->calculated_score, 1) }}</td>
                                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                                        {{ $response->comments ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Recomendações --}}
                    @if ($selectedEvaluation->recommendations)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Recomendações</h4>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                                <p class="text-gray-700 dark:text-gray-300">{{ $selectedEvaluation->recommendations }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Actions Footer --}}
                <div
                    class="flex flex-col sm:flex-row gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50">
                    @if ($this->isWaitingForMe($selectedEvaluation))
                        <button wire:click="openApprovalModal({{ $selectedEvaluation->id }}); closeDetailModal();"
                            class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                            Aprovar
                        </button>
                        <button wire:click="openRejectionModal({{ $selectedEvaluation->id }}); closeDetailModal();"
                            class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                            Rejeitar
                        </button>
                    @endif
                    <button wire:click="closeDetailModal"
                        class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 border border-gray-300 dark:border-gray-500 rounded-xl font-medium transition-colors">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading State --}}
    <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[60]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl max-w-sm w-full mx-4">
            <div class="flex flex-col items-center gap-4 justify-center">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-pulse">
                    </div>
                    <div
                        class="absolute top-0 left-0 w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin">
                    </div>
                </div>
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Processando...</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Aguarde um momento</p>
                </div>
            </div>
        </div>
    </div>
</div>
