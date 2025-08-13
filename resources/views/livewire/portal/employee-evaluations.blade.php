{{-- resources/views/livewire/portal/employee-evaluations.blade.php --}}
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Minhas Avaliações</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Histórico completo das suas avaliações de desempenho</p>
        </div>
        <div class="flex space-x-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                📊 Relatório
            </button>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ano</label>
                <select wire:model.live="yearFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="">Todos os anos</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mês</label>
                <select wire:model.live="monthFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="">Todos os meses</option>
                    @foreach($months as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="">Todos os status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Por página</label>
                <select wire:model.live="perPage" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Lista de Avaliações --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if($evaluations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avaliador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($evaluations as $evaluation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $evaluation->evaluation_period_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-lg font-bold text-{{ $evaluation->performance_color }}-600">
                                            {{ number_format($evaluation->final_percentage, 1) }}%
                                        </div>
                                        <div class="ml-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $evaluation->performance_color }}-100 text-{{ $evaluation->performance_color }}-800">
                                                {{ $evaluation->performance_class }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $evaluation->status_display !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $evaluation->evaluator->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $evaluation->approved_at?->format('d/m/Y') ?? ($evaluation->submitted_at?->format('d/m/Y') ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="viewDetails({{ $evaluation->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        @if($evaluation->status === 'approved')
                                            <button wire:click="printEvaluation({{ $evaluation->id }})" 
                                                    class="text-green-600 hover:text-green-900 p-1 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="downloadEvaluation({{ $evaluation->id }})" 
                                                    class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $evaluations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-xl text-gray-500 dark:text-gray-400 mb-2">Nenhuma avaliação encontrada</p>
                <p class="text-gray-400 dark:text-gray-500">Ajuste os filtros ou aguarde novas avaliações</p>
            </div>
        @endif
    </div>

    {{-- Modal de Detalhes --}}
    @if($showDetailsModal && $selectedEvaluation)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Detalhes da Avaliação - {{ $selectedEvaluation->evaluation_period_formatted }}
                    </h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Resumo da Avaliação --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-2xl font-bold text-{{ $selectedEvaluation->performance_color }}-600">
                            {{ number_format($selectedEvaluation->final_percentage, 1) }}%
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Performance Final</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $selectedEvaluation->performance_class }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Classificação</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $selectedEvaluation->status_display }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Status</div>
                    </div>
                </div>

                {{-- Métricas Detalhadas --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Avaliação por Métrica</h4>
                    <div class="space-y-4">
                        @foreach($selectedEvaluation->responses as $response)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="font-medium text-gray-900 dark:text-white">{{ $response->metric->name }}</h5>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Peso: {{ $response->metric->weight }}%
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Valor Avaliado</div>
                                        <div class="font-medium">{{ $response->display_value }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Pontuação</div>
                                        <div class="font-medium">{{ number_format($response->calculated_score, 1) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Tipo</div>
                                        <div class="font-medium">{{ $response->metric->type_display }}</div>
                                    </div>
                                </div>
                                @if($response->comments)
                                    <div class="mt-2">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Comentários</div>
                                        <div class="text-sm text-gray-800 dark:text-gray-200">{{ $response->comments }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recomendações --}}
                @if($selectedEvaluation->recommendations)
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Recomendações</h4>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-gray-800 dark:text-gray-200">{{ $selectedEvaluation->recommendations }}</p>
                    </div>
                </div>
                @endif

                {{-- Informações do Avaliador --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Avaliado por:</span>
                            <span class="font-medium text-gray-900 dark:text-white ml-2">
                                {{ $selectedEvaluation->evaluator->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Data de Aprovação:</span>
                            <span class="font-medium text-gray-900 dark:text-white ml-2">
                                {{ $selectedEvaluation->approved_at?->format('d/m/Y H:i') ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
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