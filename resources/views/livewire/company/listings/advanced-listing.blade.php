<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Listagem Avançada</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Crie consultas personalizadas combinando campos de todos os formulários
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('company.orders.index') }}"
                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                ← Voltar à Listagem
            </a>
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            {{-- Step 1: Filtros --}}
            <div class="flex items-center">
                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    @if ($currentStep > 1)
                        ✓
                    @else
                        1
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm {{ $currentStep >= 1 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                        Filtros Principais
                    </p>
                </div>
            </div>

            {{-- Linha conectora --}}
            <div class="flex-1 h-0.5 mx-4 {{ $currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200' }}"></div>

            {{-- Step 2: Campos --}}
            <div class="flex items-center">
                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    @if ($currentStep > 2)
                        ✓
                    @else
                        2
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium {{ $currentStep >= 2 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500' }}">
                        Passo 2
                    </p>
                    <p class="text-sm {{ $currentStep >= 2 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                        Seleção de Campos
                    </p>
                </div>
            </div>

            {{-- Linha conectora --}}
            <div class="flex-1 h-0.5 mx-4 {{ $currentStep >= 3 ? 'bg-blue-600' : 'bg-gray-200' }}"></div>

            {{-- Step 3: Resultados --}}
            <div class="flex items-center">
                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                    @if ($currentStep >= 3)
                        ✓
                    @else
                        3
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium {{ $currentStep >= 3 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500' }}">
                        Passo 3
                    </p>
                    <p class="text-sm {{ $currentStep >= 3 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">
                        Resultados
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- PASSO 1: FILTROS PRINCIPAIS --}}
    @if ($currentStep === 1)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">📋 Filtros Principais</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Configure os filtros para definir quais ordens serão incluídas na consulta
                </p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Primeira linha: Período e Ordem --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Data Início</label>
                        <input type="date" wire:model="filterPeriodStart"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Data Fim</label>
                        <input type="date" wire:model="filterPeriodEnd"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🔢 Ordem de Reparação</label>
                        <input type="text" wire:model="filterOrderNumber" placeholder="OR-2024-0001"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                {{-- Segunda linha: Cliente e Técnicos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">👤 Cliente</label>
                        <select wire:model="filterClient"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Todos os clientes</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">🔧 Técnicos</label>
                        <select wire:model="filterTechnicians" multiple
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}">
                                    {{ $technician->name }} {{ $technician->code ? '(' . $technician->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Segure Ctrl/Cmd para selecionar múltiplos</p>
                    </div>
                </div>

                {{-- Terceira linha: Estado, Localização e Tipo --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📊 Estado</label>
                        <select wire:model="filterStatus"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Todos os estados</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📍 Localização</label>
                        <select wire:model="filterLocation"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Todas as localizações</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">⚙️ Tipo de Manutenção</label>
                        <select wire:model="filterMaintenanceType"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Todos os tipos</option>
                            @foreach ($maintenanceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Quarta linha: Descrição de avaria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📝 Descrição de Avaria</label>
                    <input type="text" wire:model="filterDescription" placeholder="Digite palavras-chave da avaria..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>

                {{-- Ações do passo 1 --}}
                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="clearAllFilters"
                        class="px-4 py-2 text-red-600 dark:text-red-400 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        🗑️ Limpar Filtros
                    </button>
                    
                    <button wire:click="nextStep"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Próximo: Selecionar Campos →
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- PASSO 2: SELEÇÃO DE CAMPOS (ACCORDION) --}}
    @if ($currentStep === 2)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">📝 Seleção de Campos</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Escolha quais campos dos formulários deseja visualizar na tabela
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Campos selecionados</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $this->getTotalSelectedFields() }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                {{-- Accordion para cada formulário --}}
                @foreach (['form1' => ['name' => 'Formulário 1 - Inicial', 'icon' => '📝', 'color' => 'blue'],
                          'form2' => ['name' => 'Formulário 2 - Técnicos', 'icon' => '🔧', 'color' => 'green'],
                          'form3' => ['name' => 'Formulário 3 - Faturação', 'icon' => '💰', 'color' => 'orange'],
                          'form4' => ['name' => 'Formulário 4 - Máquina', 'icon' => '⚙️', 'color' => 'purple'],
                          'form5' => ['name' => 'Formulário 5 - Final', 'icon' => '✅', 'color' => 'red']] as $formKey => $formInfo)
                    
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                        {{-- Header do accordion --}}
                        <button wire:click="toggleAccordion('{{ $formKey }}')"
                            class="w-full px-4 py-3 bg-{{ $formInfo['color'] }}-50 dark:bg-{{ $formInfo['color'] }}-900/20 hover:bg-{{ $formInfo['color'] }}-100 dark:hover:bg-{{ $formInfo['color'] }}-900/30 rounded-t-lg transition-colors text-left flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $formInfo['icon'] }}</span>
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">{{ $formInfo['name'] }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ count($selectedFields[$formKey] ?? []) }} campo(s) selecionado(s)
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if (count($selectedFields[$formKey] ?? []) > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $formInfo['color'] }}-100 text-{{ $formInfo['color'] }}-800 dark:bg-{{ $formInfo['color'] }}-900/40 dark:text-{{ $formInfo['color'] }}-400">
                                        {{ count($selectedFields[$formKey] ?? []) }}
                                    </span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ $accordionOpen[$formKey] ? 'rotate-180' : '' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        {{-- Conteúdo do accordion --}}
                        @if ($accordionOpen[$formKey])
                            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Selecione os campos que deseja visualizar:</p>
                                    <div class="flex gap-2">
                                        <button wire:click="selectAllFields('{{ $formKey }}')"
                                            class="text-xs px-2 py-1 bg-{{ $formInfo['color'] }}-100 text-{{ $formInfo['color'] }}-700 dark:bg-{{ $formInfo['color'] }}-900/20 dark:text-{{ $formInfo['color'] }}-400 rounded hover:bg-{{ $formInfo['color'] }}-200 dark:hover:bg-{{ $formInfo['color'] }}-900/40 transition-colors">
                                            Selecionar Todos
                                        </button>
                                        <button wire:click="clearAllFields('{{ $formKey }}')"
                                            class="text-xs px-2 py-1 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            Limpar
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach ($availableFields[$formKey] as $fieldKey => $fieldLabel)
                                        <label class="flex items-center p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <input type="checkbox" 
                                                wire:click="toggleField('{{ $formKey }}', '{{ $fieldKey }}')"
                                                {{ in_array($fieldKey, $selectedFields[$formKey] ?? []) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-{{ $formInfo['color'] }}-600 shadow-sm focus:border-{{ $formInfo['color'] }}-300 focus:ring focus:ring-{{ $formInfo['color'] }}-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $fieldLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Ações do passo 2 --}}
                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="previousStep"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        ← Voltar aos Filtros
                    </button>
                    
                    <div class="flex items-center gap-4">
                        @if ($this->getTotalSelectedFields() === 0)
                            <p class="text-sm text-red-600 dark:text-red-400">⚠️ Selecione pelo menos um campo</p>
                        @endif
                        
                        <button wire:click="nextStep" 
                            {{ $this->getTotalSelectedFields() === 0 ? 'disabled' : '' }}
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Gerar Resultados →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- PASSO 3: RESULTADOS --}}
    @if ($currentStep === 3)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">📊 Resultados</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            @if ($showResults)
                                {{ $totalResults }} ordem(ns) encontrada(s) • {{ $this->getTotalSelectedFields() }} campo(s) selecionado(s)
                            @else
                                Gerando consulta...
                            @endif
                        </p>
                    </div>
                    
                    <div class="flex gap-2">
                        @if ($showResults && !empty($results))
                            <button wire:click="exportResults('csv')"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                📄 CSV
                            </button>
                            <button wire:click="exportResults('excel')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                📊 Excel
                            </button>
                        @endif
                        
                        <button wire:click="previousStep"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            ← Ajustar Campos
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($showResults)
                    @if (!empty($results))
                        {{-- Tabela de resultados --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        @foreach ($this->getTableHeaders() as $header)
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                {{ $header }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($results as $row)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            @foreach ($this->getTableHeaders() as $header)
                                                @php
                                                    $key = $this->getKeyFromHeader($header);
                                                    $value = $row[$key] ?? '-';
                                                @endphp
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginação simples --}}
                        @if ($totalResults > $perPage)
                            <div class="mt-4 flex justify-between items-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Mostrando {{ ($this->getPage() - 1) * $perPage + 1 }} - 
                                    {{ min($this->getPage() * $perPage, $totalResults) }} de {{ $totalResults }}
                                </p>
                                
                                <div class="flex gap-2">
                                    @if ($this->getPage() > 1)
                                        <button wire:click="setPage({{ $this->getPage() - 1 }})"
                                            class="px-3 py-1 text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                            ← Anterior
                                        </button>
                                    @endif
                                    
                                    @if ($this->getPage() * $perPage < $totalResults)
                                        <button wire:click="setPage({{ $this->getPage() + 1 }})"
                                            class="px-3 py-1 text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Próxima →
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- Estado vazio --}}
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum resultado encontrado</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Não foram encontradas ordens que correspondam aos filtros aplicados.
                            </p>
                            <button wire:click="goToStep(1)"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Ajustar Filtros
                            </button>
                        </div>
                    @endif
                @else
                    {{-- Loading --}}
                    <div class="text-center py-12">
                        <div class="inline-flex items-center">
                            <svg class="animate-spin h-8 w-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-lg text-gray-700 dark:text-gray-300">Processando consulta...</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Loading overlay global --}}
    <div wire:loading.flex 
         wire:target="nextStep,previousStep,generateResults,exportResults"
         class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center">
                <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">Processando...</span>
            </div>
        </div>
    </div>
</div> 