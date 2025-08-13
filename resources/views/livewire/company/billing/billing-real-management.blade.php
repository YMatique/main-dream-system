<div class="space-y-6">

    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-rose-800 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Faturação Real</h2>
                <p class="mt-2 text-red-100">Faturação baseada em dados reais do Form3</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-red-200">Total de Registros</div>
                <div class="text-2xl font-bold">{{ number_format($statistics['total_billings']) }}</div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

        <!-- Total Faturado MZN -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total (MZN)</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($statistics['total_amount_mzn'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Faturado USD -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total (USD)</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        $ {{ number_format($statistics['total_amount_usd'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total de Horas Faturadas -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Horas</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($statistics['total_billed_hours'], 1) }}h
                    </p>
                </div>
            </div>
        </div>

        <!-- Preço Médio MZN -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Preço/h Real (MZN)</p>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ number_format($statistics['avg_hourly_rate_mzn'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Distribuição por Moeda -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Moedas</p>
                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">
                        @if(isset($statistics['currency_distribution']['MZN']))
                            MZN: {{ $statistics['currency_distribution']['MZN'] }}
                        @endif
                        @if(isset($statistics['currency_distribution']['USD']))
                            @if(isset($statistics['currency_distribution']['MZN'])) | @endif
                            USD: {{ $statistics['currency_distribution']['USD'] }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aviso sobre Faturação Real -->
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.99-.833-2.598 0L3.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-800 dark:text-red-200">
                    <strong>Atenção:</strong> A Faturação Real é gerada automaticamente após o Form3 e baseia-se em dados reais. Apenas a <strong>moeda de faturação</strong> pode ser alterada após a criação.
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros (adaptados para Real) -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                    </svg>
                    Filtros de Pesquisa
                </h3>
                <button wire:click="resetFilters" class="text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-300 px-3 py-1.5 rounded-lg transition-colors">
                    Limpar Filtros
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <!-- Ordem de Reparação -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ordem de Reparação</label>
                    <input type="text" wire:model.live="search" placeholder="Ex: OR-2024-001" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                </div>

                <!-- Tipo de Manutenção -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tipo de Manutenção</label>
                    <select wire:model.live="selectedMaintenanceType" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                        <option value="">Todos os tipos</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cliente -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Cliente</label>
                    <select wire:model.live="selectedClient" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                        <option value="">Todos os clientes</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Técnico/Funcionário -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Técnico</label>
                    <select wire:model.live="selectedEmployee" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                        <option value="">Todos os técnicos</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Data Faturação Início -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Data Faturação Início</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                </div>

                <!-- Data Faturação Fim -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Data Faturação Fim</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-zinc-700 dark:text-white">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Faturações -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Ordem de Reparação</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Tipo de Manutenção</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Nº Máquina</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Horas Faturadas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Materiais</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Valor Faturado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Data Faturação</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                  @forelse($billings as $billing)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-zinc-900 dark:text-white">
                                    {{ $billing->repairOrder->order_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200">
                                    {{ $billing->repairOrder->form1->maintenanceType->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                {{ $billing->repairOrder->form1->client->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                {{ $billing->repairOrder->form1->machineNumber->number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                <div class="font-medium">{{ number_format($billing->billed_hours, 1) }}h</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    @if($billing->billing_currency === 'USD')
                                        $ {{ number_format($billing->hourly_price_usd, 2) }}/h
                                    @else
                                        {{ number_format($billing->hourly_price_mzn, 2) }} MZN/h
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-white">
                                    @if($billing->repairOrder->form3 && $billing->repairOrder->form3->materials->count() > 0)
                                        @foreach($billing->repairOrder->form3->materials->take(2) as $material)
                                            <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded mr-1 mb-1">
                                                {{ $material->material->name ?? 'Material' }} ({{ number_format($material->quantidade, 1) }})
                                            </span>
                                        @endforeach
                                        @if($billing->repairOrder->form3->materials->count() > 2)
                                            <span class="text-xs text-gray-500">+{{ $billing->repairOrder->form3->materials->count() - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Sem materiais</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                    @if($billing->billing_currency === 'USD')
                                        <span class="text-green-600 dark:text-green-400">$ {{ number_format($billing->billed_amount, 2) }}</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">{{ number_format($billing->billed_amount, 2) }} MZN</span>
                                    @endif
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    MZN: {{ number_format($billing->total_mzn, 2) }} | USD: {{ number_format($billing->total_usd, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $billing->billing_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <!-- Botão Visualizar -->
                                <button wire:click="openViewModal({{ $billing->id }})" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </button>
                                
                                <!-- Botão Alterar Moeda -->
                                <button wire:click="openCurrencyModal({{ $billing->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700 text-red-700 dark:text-red-200 text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Alterar Moeda
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-1">Nenhuma faturação encontrada</h3>
                                    <p class="text-zinc-500 dark:text-zinc-400">Ajuste os filtros ou complete ordens no Form3.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($billings->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-700 px-6 py-4 border-t border-zinc-200 dark:border-zinc-600">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-zinc-700 dark:text-zinc-300">
                        Mostrando {{ $billings->firstItem() }} a {{ $billings->lastItem() }} de {{ $billings->total() }} resultados
                    </div>
                    <div class="flex-1 flex justify-end">
                        {{ $billings->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de Visualização -->
    @if($showViewModal && $viewBilling)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeViewModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
                    
                    <!-- Header do Modal -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">Detalhes da Faturação Real</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Ordem: {{ $viewBilling->repairOrder->order_number }}</p>
                            </div>
                        </div>
                        <button wire:click="closeViewModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Conteúdo do Modal -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Informações da Ordem -->
                        <div class="space-y-6">
                            <!-- Dados Básicos -->
                            <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Informações da Ordem</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Ordem:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->repairOrder->order_number }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Cliente:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->repairOrder->form1->client->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Tipo Manutenção:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->repairOrder->form1->maintenanceType->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Nº Máquina:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->repairOrder->form1->machineNumber->number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Localização:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->repairOrder->form1->location->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Materiais Faturados (Form3) -->
                            @if($viewBilling->repairOrder->form3 && $viewBilling->repairOrder->form3->materials->count() > 0)
                            <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Materiais Faturados</h4>
                                <div class="space-y-2">
                                    @foreach($viewBilling->repairOrder->form3->materials as $material)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $material->material->name ?? 'N/A' }}</span>
                                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ number_format($material->quantidade, 2) }} {{ $material->material->unit ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Informações de Faturação Real -->
                        <div class="space-y-6">
                            <!-- Valores de Faturação -->
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                                <h4 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-3">Faturação Real</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Data Faturação:</span>
                                        <span class="text-sm font-bold text-red-900 dark:text-red-100">{{ $viewBilling->billing_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Horas Faturadas:</span>
                                        <span class="text-sm font-bold text-red-900 dark:text-red-100">{{ number_format($viewBilling->billed_hours, 1) }}h</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Preço/h MZN:</span>
                                        <span class="text-sm font-medium text-red-900 dark:text-red-100">{{ number_format($viewBilling->hourly_price_mzn, 2) }} MZN</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Preço/h USD:</span>
                                        <span class="text-sm font-medium text-red-900 dark:text-red-100">$ {{ number_format($viewBilling->hourly_price_usd, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Total MZN:</span>
                                        <span class="text-sm font-medium text-red-900 dark:text-red-100">{{ number_format($viewBilling->total_mzn, 2) }} MZN</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-red-700 dark:text-red-300">Total USD:</span>
                                        <span class="text-sm font-medium text-red-900 dark:text-red-100">$ {{ number_format($viewBilling->total_usd, 2) }}</span>
                                    </div>
                                    <hr class="border-red-200 dark:border-red-700">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-semibold text-red-700 dark:text-red-300">Valor Faturado:</span>
                                        <span class="text-lg font-bold text-red-900 dark:text-red-100">
                                            @if($viewBilling->billing_currency === 'USD')
                                                $ {{ number_format($viewBilling->billed_amount, 2) }}
                                            @else
                                                {{ number_format($viewBilling->billed_amount, 2) }} MZN
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Aviso sobre Imutabilidade -->
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.99-.833-2.598 0L3.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <h5 class="text-sm font-medium text-amber-800 dark:text-amber-200">Faturação Protegida</h5>
                                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">Os valores desta faturação não podem ser alterados pois são baseados em dados reais do Form3. Apenas a moeda pode ser alterada.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Datas -->
                            <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">Informações de Data</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Data Faturação:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->billing_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Data Criação:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Última Atualização:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $viewBilling->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="openCurrencyModal({{ $viewBilling->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Alterar Moeda
                        </button>
                        <button wire:click="closeViewModal" class="inline-flex items-center px-4 py-2 bg-zinc-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-zinc-700 focus:bg-zinc-700 active:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal de Alteração de Moeda (APENAS MOEDA) -->
    @if($showCurrencyModal && $selectedBilling)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-700 opacity-75 transition-opacity" wire:click="closeCurrencyModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-zinc-900 dark:text-white" id="modal-title">
                                Alterar Moeda - Faturação Real
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Ordem: <strong>{{ $selectedBilling->repairOrder->order_number }}</strong>
                                </p>
                            </div>

                            <!-- Aviso sobre Limitações -->
                            <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.99-.833-2.598 0L3.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span class="text-xs text-amber-800 dark:text-amber-200">
                                        <strong>Atenção:</strong> Apenas a moeda pode ser alterada. Os valores são fixos e baseados em dados reais.
                                    </span>
                                </div>
                            </div>

                            <!-- Informações Atuais -->
                            <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-700 rounded-lg">
                                <h4 class="text-sm font-medium text-zinc-900 dark:text-white mb-2">Valores Fixos (Não Editáveis):</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-zinc-600 dark:text-zinc-400">Data Faturação:</span>
                                        <span class="font-medium text-zinc-900 dark:text-white ml-2">{{ $selectedBilling->billing_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-zinc-600 dark:text-zinc-400">Horas:</span>
                                        <span class="font-medium text-zinc-900 dark:text-white ml-2">{{ number_format($selectedBilling->billed_hours, 1) }}h</span>
                                    </div>
                                    <div>
                                        <span class="text-zinc-600 dark:text-zinc-400">Total MZN:</span>
                                        <span class="font-medium text-zinc-900 dark:text-white ml-2">{{ number_format($selectedBilling->total_mzn, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-zinc-600 dark:text-zinc-400">Total USD:</span>
                                        <span class="font-medium text-zinc-900 dark:text-white ml-2">$ {{ number_format($selectedBilling->total_usd, 2) }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-zinc-200 dark:border-zinc-600">
                                    <span class="text-zinc-600 dark:text-zinc-400">Moeda Atual:</span>
                                    <span class="font-medium text-zinc-900 dark:text-white">
                                        @if($selectedBilling->billing_currency === 'USD')
                                            $ {{ number_format($selectedBilling->billed_amount, 2) }} (USD)
                                        @else
                                            {{ number_format($selectedBilling->billed_amount, 2) }} MZN
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Seleção de Nova Moeda (Única Coisa Editável) -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Selecionar Nova Moeda de Faturação:
                                </label>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input id="currency-mzn-real" 
                                               type="radio" 
                                               wire:model="newCurrency" 
                                               value="MZN" 
                                               name="currency-real"
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-zinc-300 dark:border-zinc-600">
                                        <label for="currency-mzn-real" class="ml-3 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            Metical (MZN) - {{ number_format($selectedBilling->total_mzn, 2) }}
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="currency-usd-real" 
                                               type="radio" 
                                               wire:model="newCurrency" 
                                               value="USD" 
                                               name="currency-real"
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-zinc-300 dark:border-zinc-600">
                                        <label for="currency-usd-real" class="ml-3 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            Dólar (USD) - $ {{ number_format($selectedBilling->total_usd, 2) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="updateCurrency" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Alterar Moeda
                        </button>
                        <button wire:click="closeCurrencyModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-zinc-300 dark:border-zinc-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>

@section('scripts')
<script>
    // Auto-hide flash messages
    document.addEventListener('livewire:init', () => {
        Livewire.on('currency-updated', () => {
            // Optional: Add any additional JavaScript actions after currency update
        });
    });
</script>
@endsection          