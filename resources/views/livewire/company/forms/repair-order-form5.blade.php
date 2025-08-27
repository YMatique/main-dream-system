<div class="space-y-6">
    {{-- Header com gradiente especial (último formulário) --}}
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Formulário 5 - Finalização da Ordem</h2>
                <p class="mt-2 text-green-100">Equipamento, validação e conclusão do processo</p>
            </div>
            <div class="mt-4 lg:mt-0">
                @if($repairOrder)
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $repairOrder->order_number }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Progress Bar (100% quando completar) --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-green-100">Progresso dos Formulários</span>
                <span class="text-sm text-green-100">5 de 5 - FINALIZAÇÃO</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: {{ $repairOrder && $repairOrder->form5 ? '100%' : '90%' }}"></div>
            </div>
        </div>

        {{-- Informações da Ordem --}}
        @if($repairOrder && $this->orderSummary)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Cliente</div>
                    <div class="font-semibold">{{ $this->orderSummary['client'] }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Máquina Nº</div>
                    <div class="text-xl font-bold">{{ $this->orderSummary['machine_number'] }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Horas Form3</div>
                    <div class="text-xl font-bold">{{ number_format($this->orderSummary['billed_hours_form3'], 1) }}h</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Horas Form5</div>
                    <div class="text-xl font-bold">{{ number_format($this->orderSummary['total_hours_form5'], 1) }}h</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Success Message --}}
    @if($showSuccessMessage)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">{{ $successMessage }}</h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Error Messages --}}
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Date Validation Error --}}
    @if($dateValidationError)
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">{{ $dateValidationError }}</h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 0: Seleção de Ordem --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Seleção da Ordem de Reparação</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Escolha a ordem com todas as etapas anteriores concluídas</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Ordem de Reparação *
                </label>
                <div class="relative">
                    <select wire:model.live="selectedOrderId" 
                            class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Selecione uma ordem para finalizar...</option>
                        @foreach($availableOrders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->order_number }} - {{ $order->form1?->client?->name ?? 'Cliente N/A' }}
                                (Máq: {{ $order->form1?->machineNumber?->number ?? 'N/A' }} - {{ $order->created_at->format('d/m/Y') }})
                                @if($order->form5) 🏁 @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                @error('selectedOrderId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                
                @if(count($availableOrders) === 0)
                    <div class="mt-3 text-center py-4">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">Nenhuma ordem disponível. Complete o Formulário 3 primeiro.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Formulário só depois de selecionar ordem --}}
    @if($selectedOrderId && $repairOrder)
    <form wire:submit="save" class="space-y-6">

        {{-- Card 1: Dados Dinâmicos --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-lg border border-blue-200 dark:border-gray-600 overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados Carregados Dinamicamente</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Informações automáticas dos formulários anteriores</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Carimbo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carimbo</label>
                        <input type="text" value="{{ now()->format('d/m/Y H:i') }}" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                    </div>

                    {{-- Máquina --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Número Equipamento/Máquina</label>
                        <input type="text" value="{{ $this->machineNumber }}" readonly class="w-full px-4 py-3 bg-blue-50 border border-blue-300 rounded-lg text-blue-800 font-semibold">
                    </div>

                    {{-- Cliente --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cliente</label>
                        <input type="text" value="{{ $this->clientName }}" readonly class="w-full px-4 py-3 bg-green-50 border border-green-300 rounded-lg text-green-800 font-semibold">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Datas de Faturação --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Datas de Faturação</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Máximo 4 dias de diferença entre as datas</p>
                    </div>
                    @if($this->daysDifference > 0)
                        <div class="text-right">
                            <div class="text-sm text-orange-600">Diferença</div>
                            <div class="text-2xl font-bold {{ $this->daysDifference <= 4 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $this->daysDifference }} dia(s)
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Data Faturação 1 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Data de Faturação 1 *
                        </label>
                        <input type="date" wire:model.live="data_faturacao_1" max="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                        @error('data_faturacao_1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Horas 1 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Horas Faturadas 1 *
                        </label>
                        <input type="number" step="0.25" min="0" max="999.99" wire:model="horas_faturadas_1" placeholder="0.00" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                        @error('horas_faturadas_1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Data Faturação 2 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Data de Faturação 2 *
                        </label>
                        <input type="date" wire:model.live="data_faturacao_2" max="{{ date('Y-m-d') }}" min="{{ $data_faturacao_1 }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                        @error('data_faturacao_2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Horas 2 --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Horas Faturadas 2 *
                        </label>
                        <input type="number" step="0.25" min="0" max="999.99" wire:model="horas_faturadas_2" placeholder="0.00" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                        @error('horas_faturadas_2') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Resumo Horas --}}
                @if($horas_faturadas_1 || $horas_faturadas_2)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Total Horas Form 5:</h4>
                                <p class="text-lg font-bold text-orange-600">{{ number_format($this->totalHours, 2) }}h</p>
                            </div>
                            @if($repairOrder && $repairOrder->form3)
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Horas Form 3:</div>
                                    <div class="text-lg font-bold text-gray-800">{{ number_format($repairOrder->form3->horas_faturadas, 2) }}h</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card 3: Descrição e Técnico --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Descrição e Técnico Responsável</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Atividades realizadas e técnico designado</p>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    {{-- Descrição --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descrição das Actividades *
                        </label>
                        <textarea wire:model="descricao_actividades" rows="4" placeholder="Descreva as atividades realizadas..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                        @error('descricao_actividades') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div class="mt-2 text-xs text-gray-500">{{ strlen($descricao_actividades) }}/1000 caracteres (mínimo: 10)</div>
                    </div>

                    {{-- Técnico --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Técnico Responsável *
                        </label>
                        <select wire:model="employee_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecione o técnico responsável</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->name }}
                                    @if($employee->code) ({{ $employee->code }}) @endif
                                    @if($employee->department) - {{ $employee->department->name }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        
                        {{-- Info do técnico selecionado --}}
                        @if($this->selectedEmployee)
                            <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-sm font-medium text-green-800">{{ $this->selectedEmployee->name }}</div>
                                <div class="text-xs text-green-600">
                                    @if($this->selectedEmployee->code) Código: {{ $this->selectedEmployee->code }} @endif
                                    @if($this->selectedEmployee->department) | Departamento: {{ $this->selectedEmployee->department->name }} @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Resumo Final --}}
        @if($repairOrder && $this->orderSummary)
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Resumo Completo da Ordem</h3>
                    <p class="text-sm text-gray-600">Histórico de todos os formulários</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        {{-- Form 1 --}}
                        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-500">
                            <h4 class="text-sm font-medium text-blue-600 mb-2">Form 1 ✅</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div>Cliente: <span class="font-medium">{{ Str::limit($this->orderSummary['client'], 12) }}</span></div>
                                <div>Máquina: <span class="font-medium text-blue-600">{{ $this->orderSummary['machine_number'] }}</span></div>
                            </div>
                        </div>

                        {{-- Form 2 --}}
                        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-500">
                            <h4 class="text-sm font-medium text-green-600 mb-2">Form 2 ✅</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div>Horas Trabalhadas:</div>
                                <div class="text-lg font-bold text-green-600">{{ number_format($this->orderSummary['worked_hours'], 1) }}h</div>
                            </div>
                        </div>

                        {{-- Form 3 --}}
                        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-orange-500">
                            <h4 class="text-sm font-medium text-orange-600 mb-2">Form 3 ✅</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div>Horas Faturadas:</div>
                                <div class="text-lg font-bold text-orange-600">{{ number_format($this->orderSummary['billed_hours_form3'], 1) }}h</div>
                            </div>
                        </div>

                        {{-- Form 4 --}}
                        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-purple-500">
                            <h4 class="text-sm font-medium text-purple-600 mb-2">Form 4 ✅</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div>Máquina Confirmada:</div>
                                <div class="text-sm font-bold text-purple-600">{{ $this->orderSummary['machine_number'] }}</div>
                            </div>
                        </div>

                        {{-- Form 5 --}}
                        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-emerald-500">
                            <h4 class="text-sm font-medium text-emerald-600 mb-2">Form 5 📝</h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div>Total Horas:</div>
                                <div class="text-lg font-bold text-emerald-600">{{ number_format($this->orderSummary['total_hours_form5'], 1) }}h</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Ações --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex gap-3 w-full sm:w-auto">
                    <a href="{{ route('company.repair-orders.form4') }}" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Voltar para Form 4
                    </a>
                </div>

                <div class="flex gap-3 w-full sm:w-auto">
                    <button type="submit" wire:loading.attr="disabled" class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center disabled:opacity-75 disabled:cursor-not-allowed">
                        <div wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Finalizando Ordem...
                        </div>
                        <div wire:loading.remove wire:target="save" class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isEditing ? 'Atualizar e Finalizar' : 'FINALIZAR ORDEM' }}
                        </div>
                    </button>

                    {{-- Botão de sucesso se já finalizou --}}
                    @if($repairOrder && $repairOrder->form5 && $repairOrder->is_completed)
                        {{--{{ route('company.repair-orders.index') }}  --}}
                    <a href="" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <span class="mr-2">Ver Todas as Ordens</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
    
    @else
        {{-- Mensagem quando nenhuma ordem está selecionada --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12">
            <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione uma ordem para finalizar</h3>
                <p class="text-gray-600 mb-6">Para concluir o processo, selecione uma ordem de reparação que já passou por todos os formulários anteriores.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('company.repair-orders.form3') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                        Ir para Formulário 3
                    </a>
                    <button wire:click="loadAvailableOrders" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium transition-colors">
                        Atualizar Lista
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast Notifications --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif  
</div>

{{-- Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação em tempo real das datas
    function validateDates() {
        const date1Input = document.querySelector('input[wire\\:model\\.live="data_faturacao_1"]');
        const date2Input = document.querySelector('input[wire\\:model\\.live="data_faturacao_2"]');
        
        if (date1Input && date2Input && date1Input.value && date2Input.value) {
            const date1 = new Date(date1Input.value);
            const date2 = new Date(date2Input.value);
            
            if (date2 < date1) {
                date2Input.style.borderColor = '#f59e0b';
                date2Input.title = 'A segunda data deve ser posterior à primeira';
            } else {
                const daysDiff = Math.ceil((date2 - date1) / (1000 * 60 * 60 * 24));
                if (daysDiff > 4) {
                    date2Input.style.borderColor = '#ef4444';
                    date2Input.title = `Diferença de ${daysDiff} dias excede o máximo de 4 dias`;
                } else {
                    date2Input.style.borderColor = '';
                    date2Input.title = '';
                }
            }
        }
    }

    // Executar validação a cada segundo
    setInterval(validateDates, 1000);

    // Confirmação antes de finalizar
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const isEditing = @json($isEditing);
            if (!isEditing) {
                const confirmed = confirm('Tem certeza que deseja FINALIZAR esta ordem? Esta ação não pode ser desfeita.');
                if (!confirmed) {
                    e.preventDefault();
                }
            }
        });
    }

    // Auto-scroll para erros de validação
    document.addEventListener('livewire:validation-error', function() {
        const firstError = document.querySelector('.text-red-600');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Contador de caracteres dinâmico
    const textarea = document.querySelector('textarea[wire\\:model="descricao_actividades"]');
    if (textarea) {
        textarea.addEventListener('input', function() {
            const counter = document.querySelector('.text-xs.text-gray-500');
            if (counter) {
                const length = this.value.length;
                counter.textContent = `${length}/1000 caracteres (mínimo: 10)`;
                
                if (length < 10) {
                    counter.style.color = '#ef4444';
                } else if (length > 950) {
                    counter.style.color = '#f59e0b';
                } else {
                    counter.style.color = '#6b7280';
                }
            }
        });
    }

    // Animação de sucesso quando finalizar
    window.addEventListener('livewire:updated', function() {
        if (@this.showSuccessMessage && @this.successMessage.includes('FINALIZADA')) {
            // Animação especial de finalização
            const header = document.querySelector('.bg-gradient-to-r.from-green-600');
            if (header) {
                header.classList.add('animate-pulse');
                setTimeout(() => {
                    header.classList.remove('animate-pulse');
                }, 3000);
            }

            // Confetti effect (se tiver biblioteca)
            if (typeof confetti !== 'undefined') {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        }
    });
});
</script>
@endpush