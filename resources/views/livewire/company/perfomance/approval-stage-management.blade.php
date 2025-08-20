<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuração de Estágios de Aprovação</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Defina os estágios de aprovação para avaliações de desempenho por departamento</p>
            </div>
            
            <div class="mt-4 lg:mt-0">
                <button wire:click="openModal" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Adicionar Estágio
                </button>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filtrar por Departamento</label>
                <select wire:model.live="selectedDepartmentFilter" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                    <option value="">Todos os Departamentos</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button wire:click="loadData" 
                        class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-3 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mensagens de Sucesso/Erro --}}
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-6 py-4 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-6 py-4 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $errors->first('general') }}
            </div>
        </div>
    @endif

    {{-- Lista de Departamentos e Estágios --}}
    <div class="space-y-6">
        @forelse($this->departmentStages as $department)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                {{-- Header do Departamento --}}
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-gray-700 dark:to-gray-800 px-6 py-5 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $department['name'] }}</h3>
                                @if($department['description'])
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $department['description'] }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm font-medium px-3 py-1.5 rounded-full">
                                {{ count($department['approval_stages']) }} estágio(s)
                            </span>
                            <button wire:click="openModal({{ $department['id'] }})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-lg transition-colors">
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
                                <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-5 {{ !$stage['is_active'] ? 'bg-gray-50 dark:bg-gray-700/50 opacity-75' : 'bg-white dark:bg-gray-800' }} transition-all hover:shadow-md">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-4 mb-3">
                                                {{-- Número do Estágio --}}
                                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-sm font-bold rounded-full shadow-lg">
                                                    {{ $stage['stage_number'] }}
                                                </div>
                                                
                                                {{-- Nome do Estágio --}}
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stage['stage_name'] }}</h4>
                                                    
                                                    {{-- Badges --}}
                                                    <div class="flex flex-wrap gap-2 mt-2">
                                                        @if($stage['is_final_stage'])
                                                            <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 text-xs font-medium px-2.5 py-1 rounded-full">Final</span>
                                                        @endif
                                                        @if($stage['is_required'])
                                                            <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 text-xs font-medium px-2.5 py-1 rounded-full">Obrigatório</span>
                                                        @endif
                                                        @if(!$stage['is_active'])
                                                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">Inativo</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Aprovador --}}
                                            <div class="bg-slate-50 dark:bg-gray-700/50 rounded-lg p-4 mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-violet-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $stage['approver']['name'] ?? 'Não definido' }}
                                                        </p>
                                                        @if(isset($stage['approver']['email']))
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stage['approver']['email'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Descrição --}}
                                            @if($stage['description'])
                                                <p class="text-sm text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                                    {{ $stage['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        {{-- Ações --}}
                                        <div class="flex flex-col gap-2 ml-4">
                                            {{-- Reordenar --}}
                                            <div class="flex gap-1">
                                                @if($stage['stage_number'] > 1)
                                                    <button wire:click="reorderStage({{ $stage['id'] }}, 'up')" 
                                                            class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors"
                                                            title="Mover para cima">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                
                                                @if($stage['stage_number'] < count($department['approval_stages']))
                                                    <button wire:click="reorderStage({{ $stage['id'] }}, 'down')" 
                                                            class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors"
                                                            title="Mover para baixo">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                            
                                            <div class="flex gap-1">
                                                {{-- Editar --}}
                                                <button wire:click="editStage({{ $stage['id'] }})" 
                                                        class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                        title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                
                                                {{-- Deletar --}}
                                                <button wire:click="deleteStage({{ $stage['id'] }})" 
                                                        onclick="return confirm('Tem certeza que deseja remover este estágio?')"
                                                        class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                        title="Remover">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum estágio configurado</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Adicione estágios de aprovação para este departamento</p>
                            <button wire:click="openModal({{ $department['id'] }})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Adicionar primeiro estágio
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                <div class="text-center py-16">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-3">Nenhum departamento encontrado</h3>
                    <p class="text-gray-600 dark:text-gray-400">Configure departamentos primeiro para poder definir estágios de aprovação.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Modal de Criação/Edição --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                {{-- Header do Modal --}}
                <div class="flex justify-between items-center px-8 py-6 border-b border-gray-200 dark:border-gray-600">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $editingStage ? 'Editar Estágio' : 'Novo Estágio de Aprovação' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $editingStage ? 'Modifique as informações do estágio' : 'Configure um novo estágio de aprovação' }}
                        </p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form do Modal --}}
                <form wire:submit="saveStage" class="p-8 space-y-6">
                    {{-- Departamento --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Departamento</label>
                        <select wire:model="selectedDepartmentId" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors @error('selectedDepartmentId') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Selecione um departamento</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedDepartmentId') 
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Número do Estágio e Nome --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Número do Estágio</label>
                            <input type="number" wire:model="stageNumber" min="1"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors @error('stageNumber') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('stageNumber') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Nome do Estágio</label>
                            <input type="text" wire:model="stageName" placeholder="Ex: Supervisão, Gerência..."
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors @error('stageName') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('stageName') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    {{-- Aprovador --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Aprovador Responsável</label>
                        <select wire:model="approverUserId" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors @error('approverUserId') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Selecione um usuário</option>
                            @foreach($companyUsers as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                            @endforeach
                        </select>
                        @error('approverUserId') 
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Descrição (Opcional)</label>
                        <textarea wire:model="description" rows="4" 
                                  placeholder="Descreva as responsabilidades deste estágio..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    </div>

                    {{-- Configurações --}}
                    <div class="bg-slate-50 dark:bg-gray-700/50 rounded-xl p-6">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Configurações</h4>
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="isRequired" 
                                       class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estágio obrigatório</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Este estágio deve ser aprovado obrigatoriamente</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="isActive" 
                                       class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estágio ativo</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Estágio está ativo e será usado nas aprovações</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Ações do Modal --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" wire:click="closeModal" 
                                class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl font-medium transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg hover:shadow-xl">
                            {{ $editingStage ? 'Atualizar' : 'Criar' }} Estágio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Loading State --}}
    <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[60]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl max-w-sm w-full mx-4">
            <div class="flex flex-col items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-pulse"></div>
                    <div class="absolute top-0 left-0 w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </div>
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Processando...</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Aguarde um momento</p>
                </div>
            </div>
        </div>
    </div>
</div>