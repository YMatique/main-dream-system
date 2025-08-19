<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Configuração de Estágios de Aprovação</h1>
                <p class="mt-2 text-gray-600">Defina os estágios de aprovação para avaliações de desempenho por departamento</p>
            </div>
            
            <button wire:click="openModal" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Estágio
            </button>
        </div>
    </div>

    {{-- Filtro de Departamento --}}
    <div class="mb-6">
        <div class="max-w-xs">
            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Departamento</label>
            <select wire:model.live="selectedDepartmentFilter" 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Todos os Departamentos</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Mensagens de Sucesso/Erro --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first('general') }}
        </div>
    @endif

    {{-- Lista de Departamentos e Estágios --}}
    <div class="space-y-6">
        @forelse($this->departmentStages as $department)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                {{-- Header do Departamento --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $department['name'] }}</h3>
                                @if($department['description'])
                                    <p class="text-sm text-gray-600">{{ $department['description'] }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ count($department['approval_stages']) }} estágio(s)
                            </span>
                            <button wire:click="openModal({{ $department['id'] }})" 
                                    class="text-blue-600 hover:text-blue-800 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Lista de Estágios --}}
                <div class="p-6">
                    @if(count($department['approval_stages']) > 0)
                        <div class="space-y-4">
                            @foreach($department['approval_stages'] as $stage)
                                <div class="border border-gray-200 rounded-lg p-4 {{ !$stage['is_active'] ? 'bg-gray-50 opacity-75' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                {{-- Número do Estágio --}}
                                                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white text-sm font-semibold rounded-full">
                                                    {{ $stage['stage_number'] }}
                                                </div>
                                                
                                                {{-- Nome do Estágio --}}
                                                <h4 class="text-lg font-medium text-gray-900">{{ $stage['stage_name'] }}</h4>
                                                
                                                {{-- Badges --}}
                                                <div class="flex gap-2">
                                                    @if($stage['is_final_stage'])
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Final</span>
                                                    @endif
                                                    @if($stage['is_required'])
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">Obrigatório</span>
                                                    @endif
                                                    @if(!$stage['is_active'])
                                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">Inativo</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Aprovador --}}
                                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span><strong>Aprovador:</strong> {{ $stage['approver']['name'] ?? 'Não definido' }}</span>
                                                @if(isset($stage['approver']['email']))
                                                    <span class="text-gray-500">({{ $stage['approver']['email'] }})</span>
                                                @endif
                                            </div>
                                            
                                            {{-- Descrição --}}
                                            @if($stage['description'])
                                                <p class="text-sm text-gray-600">{{ $stage['description'] }}</p>
                                            @endif
                                        </div>
                                        
                                        {{-- Ações --}}
                                        <div class="flex items-center gap-2 ml-4">
                                            {{-- Reordenar --}}
                                            @if($stage['stage_number'] > 1)
                                                <button wire:click="reorderStage({{ $stage['id'] }}, 'up')" 
                                                        class="text-gray-400 hover:text-gray-600 p-1"
                                                        title="Mover para cima">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            @if($stage['stage_number'] < count($department['approval_stages']))
                                                <button wire:click="reorderStage({{ $stage['id'] }}, 'down')" 
                                                        class="text-gray-400 hover:text-gray-600 p-1"
                                                        title="Mover para baixo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            {{-- Editar --}}
                                            <button wire:click="editStage({{ $stage['id'] }})" 
                                                    class="text-blue-600 hover:text-blue-800 p-1"
                                                    title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            
                                            {{-- Deletar --}}
                                            <button wire:click="deleteStage({{ $stage['id'] }})" 
                                                    onclick="return confirm('Tem certeza que deseja remover este estágio?')"
                                                    class="text-red-600 hover:text-red-800 p-1"
                                                    title="Remover">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-lg font-medium">Nenhum estágio configurado</p>
                            <p class="text-sm">Adicione estágios de aprovação para este departamento</p>
                            <button wire:click="openModal({{ $department['id'] }})" 
                                    class="mt-3 text-blue-600 hover:text-blue-800 font-medium">
                                Adicionar primeiro estágio
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum departamento encontrado</h3>
                <p class="text-gray-600">Configure departamentos primeiro para poder definir estágios de aprovação.</p>
            </div>
        @endforelse
    </div>

    {{-- Modal de Criação/Edição --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                {{-- Header do Modal --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingStage ? 'Editar Estágio' : 'Novo Estágio de Aprovação' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form do Modal --}}
                <form wire:submit="saveStage" class="p-6 space-y-6">
                    {{-- Departamento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                        <select wire:model="selectedDepartmentId" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('selectedDepartmentId') border-red-300 @enderror">
                            <option value="">Selecione um departamento</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedDepartmentId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Número do Estágio e Nome --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número do Estágio</label>
                            <input type="number" wire:model="stageNumber" min="1"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stageNumber') border-red-300 @enderror">
                            @error('stageNumber') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Estágio</label>
                            <input type="text" wire:model="stageName" placeholder="Ex: Supervisão, Gerência..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stageName') border-red-300 @enderror">
                            @error('stageName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Aprovador --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aprovador Responsável</label>
                        <select wire:model="approverUserId" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('approverUserId') border-red-300 @enderror">
                            <option value="">Selecione um usuário</option>
                            @foreach($companyUsers as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                            @endforeach
                        </select>
                        @error('approverUserId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição (Opcional)</label>
                        <textarea wire:model="description" rows="3" 
                                  placeholder="Descreva as responsabilidades deste estágio..."
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="isRequired" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-2 text-sm font-medium text-gray-700">Estágio obrigatório</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="isActive" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-2 text-sm font-medium text-gray-700">Estágio ativo</label>
                        </div>
                    </div>

                    {{-- Ações do Modal --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            {{ $editingStage ? 'Atualizar' : 'Criar' }} Estágio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Loading State --}}
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-40">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <svg class="animate-spin w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Processando...</span>
            </div>
        </div>
    </div>
</div>