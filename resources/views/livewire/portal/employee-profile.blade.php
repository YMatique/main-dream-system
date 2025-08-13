{{-- resources/views/livewire/portal/employee-profile.blade.php --}}
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Informações pessoais e estatísticas de trabalho</p>
    </div>

    {{-- Informações Pessoais --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Pessoais</h3>
        </div>
        <div class="p-6">
            <div class="flex items-start space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ substr($employee->name, 0, 2) }}</span>
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $employee->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código do Funcionário</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $employee->code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                        <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->phone ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departamento</label>
                        <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->department->name ?? 'Não definido' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <div class="mt-1">{!! $employee->getStatusBadge() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas de Trabalho --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Estatísticas de Trabalho</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($workStats['total_hours_worked'], 1) }}h</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total de Horas</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $workStats['total_repair_orders'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ordens de Reparação</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($workStats['hours_this_year'], 1) }}h</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Horas Este Ano</div>
                </div>
                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $workStats['orders_this_year'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ordens Este Ano</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas de Avaliação --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Desempenho e Avaliações</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-indigo-600">{{ $evaluationStats['total_evaluations'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total de Avaliações</div>
                </div>
                <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($evaluationStats['average_performance'], 1) }}%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Média de Performance</div>
                </div>
                <div class="text-center p-4 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                    <div class="text-lg font-bold text-rose-600">{{ $evaluationStats['current_performance_class'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Classificação Atual</div>
                </div>
            </div>

            {{-- Última Avaliação --}}
            @if($evaluationStats['latest_evaluation'])
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-4">Última Avaliação</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Período</div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $evaluationStats['latest_evaluation']->evaluation_period_formatted }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Performance</div>
                            <div class="font-medium text-{{ $evaluationStats['latest_evaluation']->performance_color }}-600">
                                {{ number_format($evaluationStats['latest_evaluation']->final_percentage, 1) }}%
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Data de Aprovação</div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $evaluationStats['latest_evaluation']->approved_at?->format('d/m/Y') ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    @if($evaluationStats['latest_evaluation']->recommendations)
                        <div class="mt-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Recomendações</div>
                            <div class="mt-1 text-sm text-gray-800 dark:text-gray-200 bg-blue-50 dark:bg-blue-900/20 p-3 rounded">
                                {{ $evaluationStats['latest_evaluation']->recommendations }}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                    <p class="text-gray-600 dark:text-gray-400">Nenhuma avaliação encontrada</p>
                </div>
            @endif

            {{-- Alerta de Performance Baixa --}}
            @if($evaluationStats['has_below_threshold'])
                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <p class="text-yellow-800 dark:text-yellow-200 font-medium">Atenção!</p>
                            <p class="text-yellow-700 dark:text-yellow-300 text-sm">Você possui avaliações abaixo do threshold (50%). Consulte seu gestor para planos de desenvolvimento.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Informações da Empresa --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações da Empresa</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Empresa</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $employee->company->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email da Empresa</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->company->email }}</p>
                </div>
                @if($employee->company->phone)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone da Empresa</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->company->phone }}</p>
                </div>
                @endif
                @if($employee->company->address)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $employee->company->address }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Ações Rápidas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ações Rápidas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('employee.evaluations') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Ver Avaliações</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Histórico completo</p>
                    </div>
                </a>

                <a href="{{ route('employee.performance-history') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Análise de Desempenho</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tendências e métricas</p>
                    </div>
                </a>

                <a href="{{ route('employee.portal') }}" 
                   class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Dashboard</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Visão geral</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>