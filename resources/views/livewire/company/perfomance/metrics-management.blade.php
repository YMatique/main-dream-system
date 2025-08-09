<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-800 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">Gestão de Métricas de Desempenho</h2>
                    <p class="mt-1 text-purple-100">Configure métricas por departamento</p>
                </div>
                <button wire:click="createMetric"
                    class="bg-white text-purple-700 px-4 py-2 rounded-lg font-medium hover:bg-purple-50">
                    + Nova Métrica
                </button>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Total Métricas</p>
                        <p class="text-xl font-bold">{{ $stats['total_metrics'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Ativas</p>
                        <p class="text-xl font-bold">{{ $stats['active_metrics'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Departamentos</p>
                        <p class="text-xl font-bold">{{ $departments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Incompletos</p>
                        <p class="text-xl font-bold">{{ $stats['incomplete_weights'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departamentos -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-medium">Departamentos</h3>
                <p class="text-sm text-gray-600">Clique em um departamento para gerir suas métricas</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($departments as $department)
                        @php
                            $weightStatus = $this->getDepartmentWeightStatus($department->id);
                        @endphp
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                            wire:click="selectDepartment({{ $department->id }})">

                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium">{{ $department->name }}</h4>
                                <span
                                    class="px-2 py-1 text-xs rounded {{ $weightStatus['status'] === 'complete' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $weightStatus['total'] }}%
                                </span>
                            </div>

                            <div class="text-sm text-gray-600 space-y-1">
                                <div>Métricas: {{ $department->performanceMetrics()->count() }}</div>
                                <div>Ativas: {{ $department->performanceMetrics()->where('is_active', true)->count() }}
                                </div>
                            </div>

                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full"
                                    style="width: {{ min($weightStatus['total'], 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lista de Métricas -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium">Métricas</h3>
                    <button wire:click="createMetric"
                        class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Nova Métrica
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="p-4 border-b bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input wire:model.live="search" type="text" placeholder="Buscar métricas..."
                        class="border rounded px-3 py-2">

                    <select wire:model.live="departmentFilter" class="border rounded px-3 py-2">
                        <option value="">Todos departamentos</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="typeFilter" class="border rounded px-3 py-2">
                        <option value="">Todos tipos</option>
                        <option value="numeric">Numérico</option>
                        <option value="rating">Avaliação</option>
                        <option value="boolean">Sim/Não</option>
                    </select>
                </div>
            </div>

            <!-- Tabela -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Métrica</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Departamento</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Tipo</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Peso</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($metrics as $metric)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $metric->name }}</div>
                                    @if ($metric->description)
                                        <div class="text-sm text-gray-600">{{ Str::limit($metric->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm">
                                        {{ $metric->department->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                        {{ ucfirst($metric->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ $metric->weight }}%</span>
                                        <div class="w-16 bg-gray-200 rounded h-2">
                                            <div class="bg-purple-500 h-2 rounded"
                                                style="width: {{ $metric->weight }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="toggleMetricStatus({{ $metric->id }})"
                                        class="px-2 py-1 rounded text-sm {{ $metric->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $metric->is_active ? 'Ativa' : 'Inativa' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="editMetric({{ $metric->id }})"
                                            class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeleteMetric({{ $metric->id }})"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Nenhuma métrica encontrada</p>
                                        <button wire:click="createMetric"
                                            class="mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                            Criar primeira métrica
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($metrics->hasPages())
                <div class="p-4 border-t">
                    {{ $metrics->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Create/Edit -->
        @if ($showMetricModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50" wire:click="closeMetricModal"></div>

                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative">
                        <form wire:submit.prevent="saveMetric">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">
                                    {{ $editingMetricId ? 'Editar' : 'Nova' }} Métrica
                                </h3>

                                <div class="space-y-4">
                                    <!-- Departamento -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Departamento *</label>
                                        <select wire:model="selectedDepartmentId"
                                            class="w-full border rounded px-3 py-2">
                                            <option value="">Selecione</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedDepartmentId')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Nome -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nome da Métrica *</label>
                                        <input wire:model="metric_name" type="text"
                                            class="w-full border rounded px-3 py-2"
                                            placeholder="Ex: Pontualidade, Qualidade...">
                                        @error('metric_name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Descrição -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Descrição</label>
                                        <textarea wire:model="metric_description" class="w-full border rounded px-3 py-2" rows="2"
                                            placeholder="Descreva o que esta métrica avalia..."></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Tipo -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tipo *</label>
                                            <select wire:model.live="metric_type"
                                                class="w-full border rounded px-3 py-2">
                                                <option value="numeric">Numérico (0-10)</option>
                                                <option value="rating">Avaliação Rápida</option>
                                                <option value="boolean">Sim/Não</option>
                                            </select>
                                        </div>

                                        <!-- Peso -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Peso (%) *</label>
                                            <input wire:model="metric_weight" type="number" min="1"
                                                max="100" class="w-full border rounded px-3 py-2">
                                            @error('metric_weight')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    @if ($metric_type === 'rating')
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Opções de Avaliação *</label>
                                            <div class="space-y-2">
                                                @foreach ($metric_rating_options as $index => $option)
                                                    <div class="flex items-center space-x-2">
                                                        <input type="text"
                                                            wire:model="metric_rating_options.{{ $index }}"
                                                            class="flex-1 border rounded px-2 py-1">
                                                        <button type="button"
                                                            wire:click="removeRatingOption({{ $index }})"
                                                            class="text-red-600 hover:text-red-800"
                                                            {{ count($metric_rating_options) <= 2 ? 'disabled' : '' }}>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach

                                                <div class="flex items-center space-x-2">
                                                    <input type="text" wire:model="newRatingOption"
                                                        placeholder="Nova opção..."
                                                        class="flex-1 border rounded px-2 py-1">
                                                    <button type="button" wire:click="addRatingOption"
                                                        class="bg-purple-600 text-white px-3 py-1 rounded text-sm">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Status -->
                                    <div class="flex items-center">
                                        <input wire:model="is_active" type="checkbox" class="mr-2">
                                        <label class="text-sm">Métrica ativa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                <button type="button" wire:click="closeMetricModal"
                                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                    {{ $editingMetricId ? 'Atualizar' : 'Criar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Delete -->
        @if ($showDeleteModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

                    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm relative">
                        <div class="p-6">
                            <h3 class="text-lg font-medium mb-4">Eliminar Métrica</h3>
                            <p class="text-gray-600 mb-6">
                                Tem certeza que deseja eliminar esta métrica? Esta ação não pode ser desfeita.
                            </p>

                            <div class="flex justify-end space-x-3">
                                <button wire:click="$set('showDeleteModal', false)"
                                    class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100">
                                    Cancelar
                                </button>
                                <button wire:click="deleteMetric"
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
