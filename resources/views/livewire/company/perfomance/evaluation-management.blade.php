<div>
    <!-- resources/views/livewire/company/performance/evaluation-management.blade.php -->
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-800 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">Avaliações de Desempenho</h2>
                    <p class="mt-1 text-blue-100">Gerir avaliações dos funcionários</p>
                </div>
                <button wire:click="createEvaluation"
                    class="bg-white text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-50">
                    + Nova Avaliação
                </button>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-xl font-bold">{{ $stats['total_evaluations'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Este Mês</p>
                        <p class="text-xl font-bold">{{ $stats['this_month'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Pendentes</p>
                        <p class="text-xl font-bold">{{ $stats['pending_approval'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Baixo Desempenho</p>
                        <p class="text-xl font-bold">{{ $stats['below_threshold'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Média</p>
                        <p class="text-xl font-bold">{{ number_format($stats['average_performance'], 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Avaliações -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium">Avaliações</h3>
                    <button wire:click="createEvaluation"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Nova Avaliação
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="p-4 border-b bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input wire:model.live="search" type="text" placeholder="Buscar funcionário..."
                        class="border rounded px-3 py-2">

                    <select wire:model.live="departmentFilter" class="border rounded px-3 py-2">
                        <option value="">Todos departamentos</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="statusFilter" class="border rounded px-3 py-2">
                        <option value="">Todos status</option>
                        <option value="draft">Rascunho</option>
                        <option value="submitted">Submetida</option>
                        <option value="in_approval">Em Aprovação</option>
                        <option value="approved">Aprovada</option>
                        <option value="rejected">Rejeitada</option>
                    </select>

                    <input wire:model.live="periodFilter" type="month" class="border rounded px-3 py-2">

                    <select wire:model.live="perPage" class="border rounded px-3 py-2">
                        <option value="15">15 por página</option>
                        <option value="25">25 por página</option>
                        <option value="50">50 por página</option>
                    </select>
                </div>
            </div>

            <!-- Tabela -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Funcionário</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Departamento</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Período</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Performance</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Avaliador</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($evaluations as $evaluation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $evaluation->employee->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $evaluation->employee->code ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm">
                                        {{ $evaluation->employee->department->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">{{ $evaluation->evaluation_period->format('m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $evaluation->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm font-medium mr-2">{{ number_format($evaluation->final_percentage, 1) }}%</span>
                                        <div class="w-16 bg-gray-200 rounded h-2">
                                            <div class="h-2 rounded {{ $evaluation->final_percentage >= 70 ? 'bg-green-500' : ($evaluation->final_percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                style="width: {{ $evaluation->final_percentage }}%"></div>
                                        </div>
                                    </div>
                                    @if ($evaluation->is_below_threshold)
                                        <span
                                            class="inline-block mt-1 px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                            Baixo Desempenho
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded text-sm
                                    {{ $evaluation->status === 'approved'
                                        ? 'bg-green-100 text-green-800'
                                        : ($evaluation->status === 'rejected'
                                            ? 'bg-red-100 text-red-800'
                                            : ($evaluation->status === 'in_approval'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $evaluation->status_display }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">{{ $evaluation->evaluator->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="viewEvaluation({{ $evaluation->id }})"
                                            class="text-blue-600 hover:text-blue-800" title="Ver">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>

                                        @if ($evaluation->canBeEdited())
                                            <button wire:click="editEvaluation({{ $evaluation->id }})"
                                                class="text-green-600 hover:text-green-800" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>

                                            <button wire:click="confirmDeleteEvaluation({{ $evaluation->id }})"
                                                class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Nenhuma avaliação encontrada</p>
                                        <button wire:click="createEvaluation"
                                            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                            Criar primeira avaliação
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($evaluations->hasPages())
                <div class="p-4 border-t">
                    {{ $evaluations->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Create/Edit Evaluation -->
        @if ($showEvaluationModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-zinc-900 opacity-75" wire:click="closeEvaluationModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
               
                    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl relative max-h-screen overflow-y-auto">
                        <form wire:submit.prevent="saveEvaluation">
                            <div class="p-6 border-b">
                                <h3 class="text-lg font-medium">
                                    {{ $currentEvaluationId ? 'Editar' : 'Nova' }} Avaliação de Desempenho
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Seleção de Funcionário e Período -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Funcionário *</label>
                                        <select wire:model.live="selectedEmployeeId"
                                            class="w-full border rounded px-3 py-2"
                                            {{ $currentEvaluationId ? 'disabled' : '' }}>
                                            <option value="">Selecione um funcionário</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }} - {{ $employee->department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selectedEmployeeId')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Período de Avaliação *</label>
                                        <input wire:model.live="evaluationPeriod" type="month"
                                            class="w-full border rounded px-3 py-2"
                                            {{ $currentEvaluationId ? 'disabled' : '' }}>
                                        @error('evaluationPeriod')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Métricas de Avaliação -->
                                @if ($metrics && $metrics->count() > 0)
                                    <div class="space-y-6">
                                        <div class="border-t pt-6">
                                            <h4 class="text-lg font-medium mb-4">Avaliação por Métricas</h4>

                                            @foreach ($metrics as $metric)
                                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                                    <div class="flex justify-between items-start mb-3">
                                                        <div class="flex-1">
                                                            <h5 class="font-medium">{{ $metric->name }}</h5>
                                                            @if ($metric->description)
                                                                <p class="text-sm text-gray-600 mt-1">
                                                                    {{ $metric->description }}</p>
                                                            @endif
                                                        </div>
                                                        <span
                                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                                            Peso: {{ $metric->weight }}%
                                                        </span>
                                                    </div>

                                                    <!-- Input baseado no tipo de métrica -->
                                                    @if ($metric->type === 'numeric')
                                                        <div class="mb-3">
                                                            <label class="block text-sm font-medium mb-1">
                                                                Pontuação ({{ $metric->min_value }} -
                                                                {{ $metric->max_value }}) *
                                                            </label>
                                                            <input
                                                                wire:model.live="responses.{{ $metric->id }}.numeric_value"
                                                                type="number" step="0.1"
                                                                min="{{ $metric->min_value }}"
                                                                max="{{ $metric->max_value }}"
                                                                class="w-full border rounded px-3 py-2">
                                                            @error("responses.{$metric->id}.numeric_value")
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    @elseif($metric->type === 'rating')
                                                        {{-- <div class="mb-3">
                                                        <label class="block text-sm font-medium mb-1">Avaliação *</label>
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                            @foreach ($metric->rating_options as $option)
                                                                <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-100">
                                                                    <input wire:model.live="responses.{{ $metric->id }}.rating_value" 
                                                                           type="radio" 
                                                                           value="{{ $option }}"
                                                                           class="mr-2">
                                                                    <span class="text-sm">{{ $option }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @error("responses.{$metric->id}.rating_value") 
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div> --}}
                                                        <div class="mb-3">
                                                            <label class="block text-sm font-medium mb-1">Avaliação
                                                                *</label>
                                                            <div class="grid grid-cols-1 gap-2">
                                                                @foreach ($metric->rating_options as $option)
                                                                    <label
                                                                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors
                                                    {{ isset($responses[$metric->id]['rating_value']) && $responses[$metric->id]['rating_value'] === $option
                                                        ? 'border-blue-500 bg-blue-50'
                                                        : 'border-gray-200' }}">
                                                                        <input
                                                                            wire:model.live="responses.{{ $metric->id }}.rating_value"
                                                                            type="radio"
                                                                            value="{{ $option }}"
                                                                            name="metric_{{ $metric->id }}_rating"
                                                                            class="mr-3 text-blue-600 focus:ring-blue-500">
                                                                        <div class="flex-1">
                                                                            <span
                                                                                class="text-sm font-medium">{{ $option }}</span>
                                                                            <div class="text-xs text-gray-500 mt-1">
                                                                                @php
                                                                                    $index = array_search(
                                                                                        $option,
                                                                                        $metric->rating_options,
                                                                                    );
                                                                                    $totalOptions = count(
                                                                                        $metric->rating_options,
                                                                                    );
                                                                                    $score =
                                                                                        $totalOptions > 1
                                                                                            ? ($index /
                                                                                                    ($totalOptions -
                                                                                                        1)) *
                                                                                                10
                                                                                            : 10;
                                                                                @endphp
                                                                                {{ number_format($score, 1) }} pontos
                                                                            </div>
                                                                        </div>
                                                                        @if (isset($responses[$metric->id]['rating_value']) && $responses[$metric->id]['rating_value'] === $option)
                                                                            <svg class="w-5 h-5 text-blue-600"
                                                                                fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                                    clip-rule="evenodd"></path>
                                                                            </svg>
                                                                        @endif
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                            @error("responses.{$metric->id}.rating_value")
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    @elseif($metric->type === 'boolean')
                                                        <div class="mb-3">
                                                            <label class="block text-sm font-medium mb-1">Resposta
                                                                *</label>
                                                            <div class="flex space-x-4">
                                                                <label class="flex items-center">
                                                                    <input
                                                                        wire:model.live="responses.{{ $metric->id }}.numeric_value"
                                                                        type="radio" value="1" class="mr-2">
                                                                    <span>Sim</span>
                                                                </label>
                                                                <label class="flex items-center">
                                                                    <input
                                                                        wire:model.live="responses.{{ $metric->id }}.numeric_value"
                                                                        type="radio" value="0" class="mr-2">
                                                                    <span>Não</span>
                                                                </label>
                                                            </div>
                                                            @error("responses.{$metric->id}.numeric_value")
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    <!-- Comentários opcionais -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Comentários
                                                            (opcional)</label>
                                                        <textarea wire:model="responses.{{ $metric->id }}.comments" class="w-full border rounded px-3 py-2" rows="2"
                                                            placeholder="Observações sobre esta métrica..."></textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Score Total -->
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h5 class="font-medium">Score Total</h5>
                                                    <p class="text-sm text-gray-600">Baseado nas métricas preenchidas
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <div
                                                        class="text-2xl font-bold {{ $finalPercentage >= 70 ? 'text-green-600' : ($finalPercentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                        {{ number_format($finalPercentage, 1) }}%
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        {{ $finalPercentage >= 90 ? 'Excelente' : ($finalPercentage >= 70 ? 'Bom' : ($finalPercentage >= 50 ? 'Satisfatório' : 'Péssimo')) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 w-full bg-gray-200 rounded-full h-3">
                                                <div class="h-3 rounded-full {{ $finalPercentage >= 70 ? 'bg-green-500' : ($finalPercentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                    style="width: {{ $finalPercentage }}%"></div>
                                            </div>

                                            @if ($finalPercentage < 50)
                                                <div class="mt-3 p-3 bg-red-100 border border-red-200 rounded">
                                                    <div class="flex">
                                                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        <div>
                                                            <h6 class="text-sm font-medium text-red-800">Baixo
                                                                Desempenho Detectado</h6>
                                                            <p class="text-sm text-red-700">Esta avaliação ficará
                                                                abaixo do limite de 50% e será enviada notificação
                                                                automática.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Recomendações -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Recomendações *</label>
                                            <textarea wire:model="recommendations" class="w-full border rounded px-3 py-2" rows="4"
                                                placeholder="Descreva as recomendações para o funcionário, pontos de melhoria e reconhecimentos..."></textarea>
                                            @error('recommendations')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-sm text-gray-500 mt-1">Mínimo 10 caracteres</p>
                                        </div>
                                    </div>
                                @elseif($selectedEmployeeId && $selectedDepartmentId)
                                    <div class="text-center py-8">
                                        <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">Métricas não configuradas
                                        </h4>
                                        <p class="text-gray-600 mb-4">
                                            O departamento selecionado não possui métricas configuradas ou o peso total
                                            não soma 100%.
                                        </p>
                                        <a href="{{ route('company.performance.metrics') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Configurar Métricas
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                <button type="button" wire:click="closeEvaluationModal"
                                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                                    Cancelar
                                </button>
                                @if ($metrics && $metrics->count() > 0)
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        {{ $currentEvaluationId ? 'Atualizar' : 'Criar' }} e Submeter
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal View Evaluation -->
        @if ($showViewModal)
            @php $viewEvaluation = $this->getEvaluationForViewing(); @endphp
            @if ($viewEvaluation)
                <div class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-900 bg-opacity-50" wire:click="closeViewModal"></div>

                        <div
                            class="bg-white rounded-lg shadow-xl w-full max-w-4xl relative max-h-screen overflow-y-auto">
                            <div class="p-6 border-b">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium">Avaliação de
                                            {{ $viewEvaluation->employee->name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $viewEvaluation->employee->department->name }} -
                                            {{ $viewEvaluation->evaluation_period->format('m/Y') }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-3 py-1 rounded text-sm
                                    {{ $viewEvaluation->status === 'approved'
                                        ? 'bg-green-100 text-green-800'
                                        : ($viewEvaluation->status === 'rejected'
                                            ? 'bg-red-100 text-red-800'
                                            : ($viewEvaluation->status === 'in_approval'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $viewEvaluation->status_display }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Informações Gerais -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-gray-50 rounded p-4">
                                        <h5 class="font-medium mb-2">Performance Final</h5>
                                        <div
                                            class="text-2xl font-bold {{ $viewEvaluation->final_percentage >= 70 ? 'text-green-600' : ($viewEvaluation->final_percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ number_format($viewEvaluation->final_percentage, 1) }}%
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $viewEvaluation->performance_class }}
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded p-4">
                                        <h5 class="font-medium mb-2">Avaliador</h5>
                                        <div class="text-sm">{{ $viewEvaluation->evaluator->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $viewEvaluation->created_at->format('d/m/Y H:i') }}</div>
                                    </div>

                                    <div class="bg-gray-50 rounded p-4">
                                        <h5 class="font-medium mb-2">Status</h5>
                                        <div class="text-sm">{{ $viewEvaluation->status_display }}</div>
                                        @if ($viewEvaluation->approved_at)
                                            <div class="text-xs text-gray-500">Aprovada em
                                                {{ $viewEvaluation->approved_at->format('d/m/Y') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Respostas por Métrica -->
                                <div>
                                    <h4 class="text-lg font-medium mb-4">Avaliação por Métricas</h4>
                                    <div class="space-y-4">
                                        @foreach ($viewEvaluation->responses as $response)
                                            <div class="bg-gray-50 rounded p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h5 class="font-medium">{{ $response->metric->name }}</h5>
                                                        @if ($response->metric->description)
                                                            <p class="text-sm text-gray-600">
                                                                {{ $response->metric->description }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right">
                                                        <span
                                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                                            Peso: {{ $response->metric->weight }}%
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Resposta</label>
                                                        <div class="text-lg font-medium">
                                                            {{ $response->display_value }}</div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Score
                                                            Calculado</label>
                                                        <div class="text-lg font-medium">
                                                            {{ number_format($response->calculated_score, 2) }} pontos
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($response->comments)
                                                    <div class="mt-3">
                                                        <label
                                                            class="block text-sm font-medium mb-1">Comentários</label>
                                                        <p class="text-sm text-gray-700">{{ $response->comments }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Recomendações -->
                                <div>
                                    <h4 class="text-lg font-medium mb-2">Recomendações</h4>
                                    <div class="bg-gray-50 rounded p-4">
                                        <p class="text-gray-700">{{ $viewEvaluation->recommendations }}</p>
                                    </div>
                                </div>

                                @if ($viewEvaluation->is_below_threshold)
                                    <div class="bg-red-50 border border-red-200 rounded p-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <h5 class="text-sm font-medium text-red-800">Baixo Desempenho</h5>
                                                <p class="text-sm text-red-700">Esta avaliação está abaixo do limite de
                                                    50% e notificações foram enviadas.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="px-6 py-4 bg-gray-50 flex justify-end">
                                <button wire:click="closeViewModal"
                                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                                    Fechar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Modal Delete -->
        @if ($showDeleteModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

                    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm relative">
                        <div class="p-6">
                            <h3 class="text-lg font-medium mb-4">Eliminar Avaliação</h3>
                            <p class="text-gray-600 mb-6">
                                Tem certeza que deseja eliminar esta avaliação? Esta ação não pode ser desfeita.
                            </p>

                            <div class="flex justify-end space-x-3">
                                <button wire:click="$set('showDeleteModal', false)"
                                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                                    Cancelar
                                </button>
                                <button wire:click="deleteEvaluation"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Notifications -->
    @if (session()->has('success'))
        <div
            class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
