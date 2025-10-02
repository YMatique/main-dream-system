<div class="space-y-6">
    {{-- Header com gradiente --}}
    <div class="bg-gradient-to-r from-green-600 via-emerald-700 to-teal-800 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Formulário 2 - Técnicos e Materiais</h2>
                <p class="mt-2 text-green-100">Registre os técnicos envolvidos, tempo de trabalho e materiais utilizados
                </p>
            </div>
            <div class="mt-4 lg:mt-0">
                @if ($repairOrder)
                    <div
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        {{ $repairOrder->order_number }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-green-100">Progresso dos Formulários</span>
                <span class="text-sm text-green-100">2 de 5</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: 40%"></div>
            </div>
        </div>

        {{-- Informações da Ordem (se selecionada) --}}
        @if ($repairOrder && $repairOrder->form1)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Cliente</div>
                    <div class="font-semibold">{{ $repairOrder->form1?->client?->name ?? 'N/A' }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Tipo de Manutenção</div>
                    <div class="font-semibold">{{ $repairOrder->form1?->maintenanceType?->name ?? 'N/A' }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Tempo Total</div>
                    <div class="text-2xl font-bold">{{ number_format($tempoTotalCalculado, 1) }}h</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Número de Máquina</div>
                    <div class="font-semibold">{{ $repairOrder->form1?->machineNumber?->number ?? 'N/A' }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-sm text-green-100">Descrição de Avaria</div>
                    <div class=" text-sm ">   {{ \Illuminate\Support\Str::limit($repairOrder->form1?->descricao_avaria ?? 'N/A', 100) }}</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Success Message --}}
    @if ($showSuccessMessage)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 0: Seleção de Ordem --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Seleção da Ordem de Reparação</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Escolha a ordem de reparação que deseja
                        trabalhar</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Ordem de Reparação *
                </label>
                <div class="relative">
                    <select wire:model.live="selectedOrderId"
                        class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white select2-order">
                        <option value="">Selecione uma ordem de reparação...</option>
                        @foreach ($availableOrders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->order_number }} - {{ $order->form1?->client?->name ?? 'Cliente N/A' }}
                                ({{ $order->created_at->format('d/m/Y') }})
                                @if ($order->form2)
                                    ✓
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                @error('selectedOrderId')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if (count($availableOrders) === 0)
                    <div class="mt-3 text-center py-4">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-sm">Nenhuma ordem disponível. Complete o Formulário de Dados Iniciais.</p>
                        <a href="{{ route('company.repair-orders.form1') }}"
                            class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Criar Nova Ordem
                        </a>
                    </div>
                @else
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ count($availableOrders) }} ordem(ns) disponível(eis).
                            {{-- <span class="ml-1">✓ = Já possui Formulário 2</span> --}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mostrar formulário só depois de selecionar ordem --}}
    @if ($selectedOrderId && $repairOrder)
        <form wire:submit="save" class="space-y-6">
            {{-- Card 1: Dados Básicos --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Básicas</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estado, localização e dados da execução
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Carimbo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Carimbo (Data/Hora)
                            </label>
                            <div class="relative">
                                <input type="text" value="{{ now()->format('d/m/Y H:i') }}" readonly
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 focus:outline-none">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Localização --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Localização *
                            </label>
                            <div class="relative">
                                <select wire:model="location_id"
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <option value="">Selecione a localização</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('location_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado da Obra --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Estado da Obra *
                            </label>
                            <div class="relative">
                                <select wire:model="status_id"
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <option value="">Selecione o estado</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tempo Total (Calculado) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tempo Total (Calculado)
                            </label>
                            <div class="relative">
                                <input type="text" value="{{ number_format($tempoTotalCalculado, 1) }} horas"
                                    readonly
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 font-medium focus:outline-none">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Técnicos Afetos --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Técnicos Afetos</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Selecione os técnicos e suas horas
                                    de trabalho</p>
                            </div>
                        </div>

                        <button type="button" wire:click="addTechnician"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adicionar Técnico
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($tecnicos as $index => $tecnico)
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                        Técnico {{ $index + 1 }}
                                    </h4>
                                    @if ($numero_tecnicos > 1)
                                        <button type="button" wire:click="removeTechnician({{ $index }})"
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Técnico --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Funcionário *
                                        </label>
                                        <select wire:model="tecnicos.{{ $index }}.employee_id"
                                            wire:change="calculateTotalHours"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                            <option value="">Selecione o técnico</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }} ({{ $employee->code }}) -
                                                    {{ $employee->department->name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tecnicos.' . $index . '.employee_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Horas --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Horas Trabalhadas *
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="0.5" min="0" max="24"
                                                wire:model="tecnicos.{{ $index }}.horas_trabalhadas"
                                                wire:change="calculateTotalHours" placeholder="0.0"
                                                class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-sm">h</span>
                                            </div>
                                        </div>
                                        @error('tecnicos.' . $index . '.horas_trabalhadas')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (empty($tecnicos) || count($tecnicos) === 0)
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhum técnico adicionado ainda</p>
                            <button type="button" wire:click="addTechnician"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                                Adicionar Primeiro Técnico
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card 3: Materiais Cadastrados --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Materiais Utilizados
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Selecione os materiais do estoque e
                                    suas quantidades</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Selecionados</div>
                            <div class="text-lg font-bold text-orange-600">{{ $this->selectedMaterialsCount }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if (count($materials) > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach ($materials as $material)
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 {{ isset($materiaisDisponiveis[$material->id]) && $materiaisDisponiveis[$material->id]['selected'] ? 'ring-2 ring-orange-500 bg-orange-50 dark:bg-orange-900/20' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <input type="checkbox" wire:click="toggleMaterial({{ $material->id }})"
                                                {{ isset($materiaisDisponiveis[$material->id]) && $materiaisDisponiveis[$material->id]['selected'] ? 'checked' : '' }}
                                                class="mt-1 h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $material->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $material->unit }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                                                    {{ number_format($material->cost_per_unit_mzn, 2) }}
                                                    MZN/{{ $material->unit }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($materiaisDisponiveis[$material->id]) && $materiaisDisponiveis[$material->id]['selected'])
                                        <div class="mt-3">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Quantidade *
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" step="0.01" min="0"
                                                    wire:model="materiaisDisponiveis.{{ $material->id }}.quantidade"
                                                    placeholder="0.00"
                                                    class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                                <span class="text-xs text-gray-500">{{ $material->unit }}</span>
                                            </div>
                                            @error('materiaisDisponiveis.' . $material->id . '.quantidade')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Nenhum material cadastrado no sistema</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card 4: Material Adicional --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Material Adicional</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Materiais não cadastrados no
                                    sistema</p>
                            </div>
                        </div>

                        <button type="button" wire:click="addAdditionalMaterial"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adicionar Material
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if (count($materiaisAdicionais) > 0)
                        <div class="space-y-4">
                            @foreach ($materiaisAdicionais as $index => $material)
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                            Material Adicional {{ $index + 1 }}
                                        </h4>
                                        <button type="button"
                                            wire:click="removeAdditionalMaterial({{ $index }})"
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        {{-- Nome do Material --}}
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Nome do Material *
                                            </label>
                                            <input type="text"
                                                wire:model="materiaisAdicionais.{{ $index }}.nome_material"
                                                placeholder="Ex: Parafuso M6..."
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                            @error('materiaisAdicionais.' . $index . '.nome_material')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Custo Unitário --}}
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Custo Unitário (MZN) *
                                            </label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0"
                                                    wire:model="materiaisAdicionais.{{ $index }}.custo_unitario"
                                                    placeholder="0.00"
                                                    class="w-full px-3 py-2 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-400 text-sm">MZN</span>
                                                </div>
                                            </div>
                                            @error('materiaisAdicionais.' . $index . '.custo_unitario')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Quantidade --}}
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Quantidade *
                                            </label>
                                            <input type="number" step="0.01" min="0"
                                                wire:model="materiaisAdicionais.{{ $index }}.quantidade"
                                                placeholder="0.00"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                                            @error('materiaisAdicionais.' . $index . '.quantidade')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Custo Total Calculado --}}
                                    @if ($material['custo_unitario'] > 0 && $material['quantidade'] > 0)
                                        <div class="mt-3 text-right">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Custo Total: </span>
                                            <span class="font-bold text-yellow-600">
                                                {{ number_format($material['custo_unitario'] * $material['quantidade'], 2) }}
                                                MZN
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Nenhum material adicional adicionado</p>
                            <button type="button" wire:click="addAdditionalMaterial"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium">
                                Adicionar Primeiro Material
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card 5: Atividade Realizada --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-gray-700 dark:to-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Atividade Realizada</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Descreva detalhadamente o trabalho
                                executado</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Descrição detalhada da atividade *
                        </label>
                        <div class="relative">
                            <textarea wire:model="actividade_realizada" rows="6"
                                placeholder="Descreva detalhadamente todas as atividades realizadas, procedimentos executados, soluções aplicadas e observações relevantes..."
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"></textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                <span id="activity-char-count">{{ strlen($actividade_realizada) }}</span>/2000
                            </div>
                        </div>
                        @error('actividade_realizada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mínimo 10 caracteres, máximo 2000 caracteres. Seja específico e detalhado.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 6: Resumo de Custos --}}
            <div
                class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-lg border border-indigo-200 dark:border-gray-600 overflow-hidden">
                <div class="px-6 py-4 border-b border-indigo-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo de Custos</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Resumo automático dos custos calculados
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format($tempoTotalCalculado, 1) }}h</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tempo Total</div>
                        </div>

                        <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                {{ $this->selectedMaterialsCount }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Materiais</div>
                        </div>

                        <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($this->totalMaterialCost, 2) }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Custo Total (MZN)</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ações --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    {{-- Botão voltar --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <button type="button" wire:click="backToForm1"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-all duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Voltar ao Formulário Inicial
                        </button>
                    </div>

                    {{-- Botões principais --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        {{-- Botão Salvar --}}
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center disabled:opacity-75 disabled:cursor-not-allowed">
                            <div wire:loading wire:target="save" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Salvando...
                            </div>
                            <div wire:loading.remove wire:target="save" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                {{ $isEditing ? 'Atualizar Formulário' : 'Salvar e Continuar' }}
                            </div>
                        </button>

                        {{-- Botão Prosseguir (só aparece se já salvou) --}}
                        @if ($repairOrder && $repairOrder->form2)
                            <button type="button" wire:click="proceedToForm3"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center">
                                <span class="mr-2">Prosseguir para Gestão de Máquina</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    @endif
    {{-- Toast Notifications --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg max-w-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
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
            // Inicializar contador de caracteres
            initializeActivityCharacterCounter();
        });

        function initializeActivityCharacterCounter() {
            const textarea = document.querySelector('textarea[wire\\:model="actividade_realizada"]');
            const charCount = document.getElementById('activity-char-count');

            if (textarea && charCount) {
                // Atualizar contador inicial
                updateActivityCharCount(textarea, charCount);

                // Listener para mudanças
                textarea.addEventListener('input', function() {
                    updateActivityCharCount(this, charCount);
                });
            }
        }

        function updateActivityCharCount(textarea, charCount) {
            const length = textarea.value.length;
            charCount.textContent = length;

            // Mudança de cor baseada no limite
            if (length > 1800) {
                charCount.style.color = '#ef4444'; // vermelho
                charCount.classList.add('font-bold');
            } else if (length > 1500) {
                charCount.style.color = '#f59e0b'; // amarelo
                charCount.classList.remove('font-bold');
            } else {
                charCount.style.color = '#6b7280'; // cinza
                charCount.classList.remove('font-bold');
            }
        }

        // Recalcular tempo total quando mudanças ocorrerem
        window.addEventListener('livewire:updated', function() {
            // Reinicializar contador de caracteres após updates
            setTimeout(() => {
                initializeActivityCharacterCounter();
            }, 100);
        });

        // Validação em tempo real
        document.addEventListener('livewire:validation-error', function(event) {
            // Scroll para o primeiro erro
            const firstError = document.querySelector('.text-red-600');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        // Confirmação antes de sair se houver dados preenchidos
        window.addEventListener('beforeunload', function(e) {
            const hasData = @this.tecnicos.some(t => t.employee_id || t.horas_trabalhadas > 0) ||
                @this.actividade_realizada.trim().length > 0 ||
                Object.values(@this.materiaisDisponiveis).some(m => m.selected) ||
                @this.materiaisAdicionais.some(m => m.nome_material);

            if (hasData && !@this.isEditing) {
                e.preventDefault();
                e.returnValue = 'Tem dados não salvos. Tem certeza que deseja sair?';
                return e.returnValue;
            }
        });

        // Auto-save draft (opcional - salvar rascunho a cada 30 segundos)
        setInterval(function() {
            if (@this.tecnicos.some(t => t.employee_id && t.horas_trabalhadas > 0) &&
                @this.actividade_realizada.trim().length > 10) {
                // Pode implementar auto-save aqui se necessário
                console.log('Auto-save draft...');
            }
        }, 30000);
    </script>
@endpush
