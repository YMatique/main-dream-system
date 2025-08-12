<div class="space-y-6">
    {{-- Header com gradiente --}}
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Formulário 4 - Gestão de Máquina</h2>
                <p class="mt-2 text-purple-100">Registre e confirme o número da máquina da ordem</p>
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

        {{-- Progress Bar --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-purple-100">Progresso dos Formulários</span>
                <span class="text-sm text-purple-100">4 de 5</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: 80%"></div>
            </div>
        </div>

        {{-- Informações da Ordem (se selecionada) --}}
        @if($repairOrder && $this->orderSummary)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-purple-100">Cliente</div>
                    <div class="font-semibold">{{ $this->orderSummary['client'] }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-purple-100">Máquina Nº</div>
                    <div class="text-xl font-bold">{{ $this->orderSummary['machine_number'] }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-purple-100">Horas Trabalhadas</div>
                    <div class="text-xl font-bold">{{ number_format($this->orderSummary['worked_hours'], 1) }}h</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-purple-100">Horas Faturadas</div>
                    <div class="text-xl font-bold">{{ number_format($this->orderSummary['billed_hours'], 1) }}h</div>
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
                    <p class="text-sm text-gray-600 dark:text-gray-400">Escolha a ordem com faturação concluída</p>
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
                            class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white select2-order">
                        <option value="">Selecione uma ordem...</option>
                        @foreach($availableOrders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->order_number }} - {{ $order->form1?->client?->name ?? 'Cliente N/A' }}
                                (Máquina: {{ $order->form1?->machineNumber?->number ?? 'N/A' }} - {{ $order->created_at->format('d/m/Y') }})
                                @if($order->form4) ✅ @endif
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
                        <a href="{{ route('company.repair-orders.form3') }}" 
                           class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ir para Formulário 3
                        </a>
                    </div>
                @else
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ count($availableOrders) }} ordem(ns) com faturação concluída. 
                            <span class="ml-1">✅ = Já processada</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mostrar formulário só depois de selecionar ordem --}}
    @if($selectedOrderId && $repairOrder)
    <form wire:submit="save" class="space-y-6">

        {{-- Card 1: Informações da Máquina --}}
        @if($repairOrder && $repairOrder->form1)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-lg border border-blue-200 dark:border-gray-600 overflow-hidden">
                <div class="px-6 py-4 border-b border-blue-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações da Máquina</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Dados carregados automaticamente do Form 1</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-blue-600 dark:text-blue-400">Número da Máquina</div>
                            <div class="text-3xl font-bold text-blue-800 dark:text-blue-300">{{ $this->machineNumber }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cliente</div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $this->orderSummary['client'] }}</div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tipo de Manutenção</div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $this->orderSummary['maintenance_type'] }}</div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Descrição da Avaria</div>
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ Str::limit($this->orderSummary['description'], 50) }}</div>
                        </div>
                    </div>
                    
                    {{-- Alerta se não há número de máquina --}}
                    @if(!$this->machineNumber || $this->machineNumber === 'N/A')
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-yellow-800 text-sm font-medium">
                                    ⚠️ Esta ordem não possui número de máquina definido no Formulário 1. 
                                    É necessário editar o Formulário 1 primeiro.
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Card 2: Dados do Formulário --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados do Formulário 4</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Localização e estado da ordem</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Carimbo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Carimbo (Data/Hora)
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   value="{{ now()->format('d/m/Y H:i') }}" 
                                   readonly 
                                   class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 focus:outline-none">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Número de Máquina (Carregado Dinamicamente) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Número da Máquina (Carregado Automaticamente)
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   value="{{ $this->machineNumber }}" 
                                   readonly 
                                   class="w-full px-4 py-3 pl-10 border border-blue-300 dark:border-blue-600 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 font-semibold focus:outline-none">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Este número foi definido no Formulário 1 e é carregado automaticamente.
                        </p>
                    </div>

                    {{-- Localização --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Localização *
                        </label>
                        <div class="relative">
                            <select wire:model="location_id" 
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Selecione a localização</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('location_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Estado *
                        </label>
                        <div class="relative">
                            <select wire:model="status_id" 
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Selecione o estado</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('status_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Resumo da Ordem --}}
        @if($repairOrder && $this->orderSummary)
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo da Ordem</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Histórico de progresso nos formulários anteriores</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Formulário 1 --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-blue-600 dark:text-blue-400">Form 1 ✅</h4>
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div>Cliente: <span class="font-medium">{{ Str::limit($this->orderSummary['client'], 15) }}</span></div>
                                <div>Tipo: <span class="font-medium">{{ Str::limit($this->orderSummary['maintenance_type'], 15) }}</span></div>
                                <div>Máquina: <span class="font-medium text-blue-600">{{ $this->orderSummary['machine_number'] }}</span></div>
                            </div>
                        </div>

                        {{-- Formulário 2 --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-green-500">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-green-600 dark:text-green-400">Form 2 ✅</h4>
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div>Horas Trabalhadas:</div>
                                <div class="text-lg font-bold text-green-600">{{ number_format($this->orderSummary['worked_hours'], 1) }}h</div>
                            </div>
                        </div>

                        {{-- Formulário 3 --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-orange-500">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-orange-600 dark:text-orange-400">Form 3 ✅</h4>
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div>Horas Faturadas:</div>
                                <div class="text-lg font-bold text-orange-600">{{ number_format($this->orderSummary['billed_hours'], 1) }}h</div>
                            </div>
                        </div>

                        {{-- Formulário 4 (Atual) --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-purple-500">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-purple-600 dark:text-purple-400">Form 4 📝</h4>
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div>Status: <span class="font-medium text-purple-600">{{ $isEditing ? 'Editando' : 'Novo' }}</span></div>
                                <div class="text-xs text-purple-600">Em Processamento</div>
                            </div>
                        </div>
                    </div>

                    {{-- Descrição da Avaria --}}
                    <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Descrição da Avaria:</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">{{ $this->orderSummary['description'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Ações --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Botões de navegação --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('company.repair-orders.form3') }}"
                       class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-all duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Ir para Formulário 3
                    </a>
                </div>

                {{-- Botões principais --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    {{-- Botão Salvar --}}
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            @if(!$this->machineNumber || $this->machineNumber === 'N/A') disabled @endif
                            class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <div wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processando...
                        </div>
                        <div wire:loading.remove wire:target="save" class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $isEditing ? 'Atualizar Registro' : 'Registrar Máquina' }}
                        </div>
                    </button>

                    {{-- Botão Prosseguir (só aparece se já salvou) --}}
                    @if($repairOrder && $repairOrder->form4)
                        <a href="{{ route('company.repair-orders.form5', $repairOrder->id) }}"
                           class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <span class="mr-2">Prosseguir para Formulário 5</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Aviso sobre número de máquina --}}
            @if(!$this->machineNumber || $this->machineNumber === 'N/A')
                <div class="mt-4 text-center">
                    <p class="text-sm text-red-600 dark:text-red-400">
                        ⚠️ Não é possível prosseguir sem um número de máquina válido.
                        <a href="{{ route('company.repair-orders.form1.edit', $repairOrder->id) }}" 
                           class="text-blue-600 hover:text-blue-800 underline ml-1">
                            Editar Formulário 1
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </form>
    
    @else
        {{-- Mensagem quando nenhuma ordem está selecionada --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Selecione uma ordem para processar</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Para registrar a máquina no Formulário 4, selecione uma ordem de reparação que já possui faturação concluída (Formulário 3).
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('company.repair-orders.form3') }}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ir para Formulário 3
                    </a>
                    <button wire:click="loadAvailableOrders" 
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Atualizar Lista
                    </button>
                </div>
            </div>
        </div>
    @endif

    
{{-- Toast Notifications --}}
@if (session()->has('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif  
</div>

{{-- Scripts para funcionalidades dinâmicas --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Select2 para seleção de ordens
    if (typeof $ !== 'undefined' && $.fn.select2) {
        initializeOrderSelect2();
    }
});

function initializeOrderSelect2() {
    $('.select2-order').select2({
        placeholder: 'Selecione uma ordem para processar...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Nenhuma ordem encontrada"; },
            searching: function() { return "Pesquisando..."; }
        }
    }).on('change', function() {
        @this.set('selectedOrderId', $(this).val());
    });
}

// Recalcular valores quando mudanças ocorrerem
window.addEventListener('livewire:updated', function() {
    // Reinicializar Select2 após updates
    setTimeout(() => {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-order').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
            initializeOrderSelect2();
        }
    }, 100);
});

// Validação em tempo real
document.addEventListener('livewire:validation-error', function(event) {
    // Scroll para o primeiro erro
    const firstError = document.querySelector('.text-red-600');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Confirmação antes de sair se houver dados preenchidos
window.addEventListener('beforeunload', function(e) {
    if (!@this.selectedOrderId) return; // Não alertar se não há ordem selecionada
    
    const hasData = @this.location_id !== '' || @this.status_id !== '';
    
    if (hasData && !@this.isEditing) {
        e.preventDefault();
        e.returnValue = 'Tem dados não salvos. Tem certeza que deseja sair?';
        return e.returnValue;
    }
});

// Validação do número de máquina
function validateMachineNumber() {
    const machineNumber = @this.machineNumber;
    const submitButton = document.querySelector('button[type="submit"]');
    
    if (machineNumber === 'N/A' || !machineNumber) {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
        return false;
    } else {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        return true;
    }
}

// Executar validação quando carregar
setInterval(validateMachineNumber, 1000);

// Highlights dinâmicos baseados no estado
function updateFormHighlights() {
    const isEditing = @this.isEditing;
    const formCards = document.querySelectorAll('.rounded-xl.shadow-lg');
    
    formCards.forEach(card => {
        if (isEditing) {
            card.classList.add('ring-2', 'ring-blue-200');
        } else {
            card.classList.remove('ring-2', 'ring-blue-200');
        }
    });
}

// Atualizar highlights quando necessário
window.addEventListener('livewire:updated', updateFormHighlights);

// Smooth scroll para seções importantes
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }
}

// Auto-focus no primeiro campo quando ordem é selecionada
window.addEventListener('livewire:updated', function() {
    if (@this.selectedOrderId && @this.selectedOrderId !== '') {
        setTimeout(() => {
            const firstSelectField = document.querySelector('select[wire\\:model="location_id"]');
            if (firstSelectField && !@this.location_id) {
                firstSelectField.focus();
            }
        }, 200);
    }
});
</script>
@endpush