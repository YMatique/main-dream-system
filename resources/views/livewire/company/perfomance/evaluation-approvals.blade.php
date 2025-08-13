<div class="space-y-6">
    {{-- Header com estatísticas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Aprovação de Avaliações</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerir aprovações de avaliações de desempenho</p>
            </div>
            
            {{-- Estatísticas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6 lg:mt-0">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <div class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Pendentes</div>
                    <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['total_pending'] ?? 0 }}</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                    <div class="text-red-600 dark:text-red-400 text-sm font-medium">< 50%</div>
                    <div class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['below_threshold'] ?? 0 }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <div class="text-green-600 dark:text-green-400 text-sm font-medium">Aprovadas</div>
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['total_approved'] ?? 0 }}</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Tempo Médio</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['avg_approval_time'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            {{-- Busca --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar Funcionário</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nome ou código..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="submitted">Aguardando Aprovação</option>
                    <option value="approved">Aprovadas</option>
                    <option value="rejected">Rejeitadas</option>
                </select>
            </div>

            {{-- Departamento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departamento</label>
                <select wire:model.live="departmentFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Mês --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mês</label>
                <select wire:model.live="monthFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Ano --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ano</label>
                <select wire:model.live="yearFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Filtros adicionais --}}
        <div class="flex flex-wrap items-center gap-4 mt-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Performance:</label>
                <select wire:model.live="thresholdFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todas</option>
                    <option value="below_threshold">Abaixo de 50%</option>
                    <option value="above_threshold">Acima de 50%</option>
                </select>
            </div>

            <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                Limpar Filtros
            </button>
        </div>
    </div>

    {{-- Ações em lote --}}
    @if(in_array($statusFilter, ['', 'submitted']))
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           @change="$wire.selectedEvaluations = $event.target.checked ? @js($evaluations->pluck('id')->toArray()) : []"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Selecionar Todas</span>
                </label>
                
                @if(!empty($selectedEvaluations))
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ count($selectedEvaluations) }} selecionada(s)
                    </span>
                @endif
            </div>

            @if(!empty($selectedEvaluations))
                <button wire:click="openBulkModal" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Aprovar Selecionadas
                </button>
            @endif
        </div>
    </div>
    @endif

    {{-- Lista de avaliações --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if($evaluations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @if(in_array($statusFilter, ['', 'submitted']))
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Funcionário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Período
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Performance
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Avaliador
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Submetida
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($evaluations as $evaluation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $evaluation->is_below_threshold ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            @if(in_array($statusFilter, ['', 'submitted', 'in_approval']))
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       wire:model.live="selectedEvaluations" 
                                       value="{{ $evaluation->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            @endif
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                            {{ $evaluation->employee->initials ?? '??' }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $evaluation->employee->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $evaluation->employee->code }} • {{ $evaluation->employee->department->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $evaluation->evaluation_period_formatted }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($evaluation->final_percentage, 1) }}%
                                    </span>
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $evaluation->performance_color === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                        {{ $evaluation->performance_color === 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                        {{ $evaluation->performance_color === 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                        {{ $evaluation->performance_color === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                        {{ $evaluation->performance_class }}
                                    </span>
                                    @if($evaluation->is_below_threshold)
                                        <svg class="ml-2 w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $evaluation->status === 'submitted' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                    {{ $evaluation->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                    {{ $evaluation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                    {{ $evaluation->status_display }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $evaluation->evaluator->name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $evaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    {{-- Ver Detalhes --}}
                                    <button wire:click="showEvaluationDetail({{ $evaluation->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>

                                    @if($evaluation->status === 'submitted')
                                        {{-- Aprovar --}}
                                        <button wire:click="openApprovalModal({{ $evaluation->id }})"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>

                                        {{-- Rejeitar --}}
                                        <button wire:click="openRejectionModal({{ $evaluation->id }})"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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

            {{-- Paginação --}}
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-600">
                {{ $evaluations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma avaliação encontrada</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Não há avaliações que correspondam aos filtros selecionados.
                </p>
            </div>
        @endif
    </div>

    {{-- MODAL: Aprovação Individual --}}
    @if($showApprovalModal && $selectedEvaluation)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeApprovalModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
               
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Aprovar Avaliação
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Está prestes a aprovar a avaliação de <strong>{{ $selectedEvaluation->employee->name }}</strong> 
                                    para o período {{ $selectedEvaluation->evaluation_period_formatted }}.
                                </p>
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Performance Final:</span>
                                        <span class="ml-2 font-bold text-{{ $selectedEvaluation->performance_color }}-600">
                                            {{ number_format($selectedEvaluation->final_percentage, 1) }}% - {{ $selectedEvaluation->performance_class }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Comentários (Opcional)
                                </label>
                                <textarea wire:model="approvalComments" rows="3" 
                                          class="w-full border-1 p-2 border-1 p-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-1 focus:ring-green-500"
                                          placeholder="Adicione comentários sobre a aprovação..."></textarea>
                                @error('approvalComments') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="approveEvaluation" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Aprovar
                    </button>
                    <button wire:click="closeApprovalModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Rejeição Individual --}}
    @if($showRejectionModal && $selectedEvaluation)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeRejectionModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
               
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Rejeitar Avaliação
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Está prestes a rejeitar a avaliação de <strong>{{ $selectedEvaluation->employee->name }}</strong>.
                                    O avaliador será notificado e poderá revisar a avaliação.
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Motivo da Rejeição <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="rejectionComments" rows="4" 
                                          class="w-full border-1 p-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500"
                                          placeholder="Explique o motivo da rejeição para orientar o avaliador..."></textarea>
                                @error('rejectionComments') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="rejectEvaluation" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Rejeitar
                    </button>
                    <button wire:click="closeRejectionModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Aprovação em Lote --}}
    @if($showBulkModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeBulkModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
               
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Aprovação em Lote
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Está prestes a aprovar <strong>{{ count($selectedEvaluations) }}</strong> avaliação(ões) de uma só vez.
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Comentários (Opcional)
                                </label>
                                <textarea wire:model="bulkComments" rows="3" 
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500"
                                          placeholder="Comentário que será aplicado a todas as aprovações..."></textarea>
                                @error('bulkComments') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="bulkApprove" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Aprovar Todas
                    </button>
                    <button wire:click="closeBulkModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Detalhes da Avaliação --}}
    @if($showDetailModal && $selectedEvaluation)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeDetailModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
               
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Detalhes da Avaliação
                        </h3>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Informações do Funcionário --}}
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Informações do Funcionário</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Nome:</span> {{ $selectedEvaluation->employee->name }}</div>
                                <div><span class="font-medium">Código:</span> {{ $selectedEvaluation->employee->code }}</div>
                                <div><span class="font-medium">Departamento:</span> {{ $selectedEvaluation->employee->department->name ?? 'N/A' }}</div>
                                <div><span class="font-medium">Período:</span> {{ $selectedEvaluation->evaluation_period_formatted }}</div>
                            </div>
                        </div>

                        {{-- Informações da Avaliação --}}
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Informações da Avaliação</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Avaliador:</span> {{ $selectedEvaluation->evaluator->name ?? 'N/A' }}</div>
                                <div><span class="font-medium">Status:</span> {{ $selectedEvaluation->status_display }}</div>
                                <div><span class="font-medium">Performance:</span> 
                                    <span class="font-bold text-{{ $selectedEvaluation->performance_color }}-600">
                                        {{ number_format($selectedEvaluation->final_percentage, 1) }}% - {{ $selectedEvaluation->performance_class }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Submetida em:</span> {{ $selectedEvaluation->submitted_at?->format('d/m/Y H:i') ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Respostas das Métricas --}}
                    @if($selectedEvaluation->responses->count() > 0)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Respostas das Métricas</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Métrica</th>
                                        <th class="px-4 py-2 text-left">Valor</th>
                                        <th class="px-4 py-2 text-left">Score</th>
                                        <th class="px-4 py-2 text-left">Comentários</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($selectedEvaluation->responses as $response)
                                    <tr>
                                        <td class="px-4 py-2 font-medium">{{ $response->metric->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $response->display_value }}</td>
                                        <td class="px-4 py-2">{{ number_format($response->calculated_score, 1) }}</td>
                                        <td class="px-4 py-2">{{ $response->comments ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Histórico de Aprovação/Rejeição --}}
                    @if($selectedEvaluation->status === 'approved' || $selectedEvaluation->status === 'rejected')
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Histórico de Aprovação</h4>
                        <div class="space-y-2">
                            @if($selectedEvaluation->status === 'approved')
                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div>
                                        <span class="font-medium text-green-800 dark:text-green-200">Aprovada</span>
                                        <span class="text-green-600 dark:text-green-400 ml-2">{{ $selectedEvaluation->approvedBy->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            Aprovada
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $selectedEvaluation->approved_at?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                                @if($selectedEvaluation->approval_comments)
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            <strong>Comentários:</strong> {{ $selectedEvaluation->approval_comments }}
                                        </p>
                                    </div>
                                @endif
                            @elseif($selectedEvaluation->status === 'rejected')
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <div>
                                        <span class="font-medium text-red-800 dark:text-red-200">Rejeitada</span>
                                        <span class="text-red-600 dark:text-red-400 ml-2">{{ $selectedEvaluation->rejectedBy->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                            Rejeitada
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $selectedEvaluation->rejected_at?->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                                @if($selectedEvaluation->rejection_reason)
                                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <p class="text-sm text-red-700 dark:text-red-300">
                                            <strong>Motivo da Rejeição:</strong> {{ $selectedEvaluation->rejection_reason }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Recomendações --}}
                    @if($selectedEvaluation->recommendations)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Recomendações</h4>
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $selectedEvaluation->recommendations }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($selectedEvaluation->status === 'submitted')
                        <button wire:click="openApprovalModal({{ $selectedEvaluation->id }}); closeDetailModal();" 
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Aprovar
                        </button>
                        <button wire:click="openRejectionModal({{ $selectedEvaluation->id }}); closeDetailModal();" 
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Rejeitar
                        </button>
                    @endif
                    <button wire:click="closeDetailModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Loading States --}}
    <div wire:loading.flex class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 dark:text-white font-medium">Processando...</span>
        </div>
    </div>
</div>

{{-- Scripts para melhor UX --}}
@section('scripts')
<script>
    // Auto-refresh da página a cada 5 minutos para capturar novas avaliações
    setInterval(() => {
        if (document.visibilityState === 'visible') {
            @this.calculateStats();
        }
    }, 300000); // 5 minutos

    // Notificações de desktop para avaliações críticas
    document.addEventListener('livewire:init', () => {
        Livewire.on('evaluation-approved', (evaluationId) => {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Avaliação Aprovada', {
                    body: 'Uma avaliação foi aprovada com sucesso.',
                    icon: '/favicon.ico'
                });
            }
        });

        Livewire.on('evaluation-rejected', (evaluationId) => {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Avaliação Rejeitada', {
                    body: 'Uma avaliação foi rejeitada e requer revisão.',
                    icon: '/favicon.ico'
                });
            }
        });
    });

    // Solicitar permissão para notificações
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        // ESC para fechar modais
        if (e.key === 'Escape') {
            @this.closeApprovalModal();
            @this.closeRejectionModal();
            @this.closeBulkModal();
            @this.closeDetailModal();
        }
        
        // Ctrl+A para selecionar todas
        if (e.ctrlKey && e.key === 'a' && !e.target.closest('textarea, input')) {
            e.preventDefault();
            const checkboxes = document.querySelectorAll('input[type="checkbox"][wire\\:model*="selectedEvaluations"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            @this.selectedEvaluations = allChecked ? [] : @js($evaluations->pluck('id')->toArray() ?? []);
        }
    });
</script>
@endsection

{{-- Estilos personalizados --}}
@section('styles')
<style>
    /* Animação para linhas de avaliações críticas */
    @keyframes pulse-red {
        0%, 100% { background-color: rgb(254 242 242); }
        50% { background-color: rgb(252 226 226); }
    }
    
    .critical-evaluation {
        animation: pulse-red 3s ease-in-out infinite;
    }
    
    /* Melhoria nos indicadores de status */
    .status-indicator {
        position: relative;
        overflow: hidden;
    }
    
    .status-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .status-indicator:hover::before {
        left: 100%;
    }
    
    /* Tooltip personalizado */
    .tooltip {
        position: relative;
    }
    
    .tooltip:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        white-space: nowrap;
        z-index: 1000;
    }
    
    /* Responsividade melhorada para tabelas */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table-responsive td, 
        .table-responsive th {
            padding: 0.5rem 0.25rem;
        }
    }
    
    /* Dark mode melhorado */
    @media (prefers-color-scheme: dark) {
        .critical-evaluation {
            animation: pulse-red-dark 3s ease-in-out infinite;
        }
    }
    
    @keyframes pulse-red-dark {
        0%, 100% { background-color: rgb(127 29 29 / 0.1); }
        50% { background-color: rgb(127 29 29 / 0.2); }
    }
</style>
@endsection