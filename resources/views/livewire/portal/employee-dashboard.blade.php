{{-- resources/views/livewire/portal/employee-dashboard.blade.php --}}
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Olá, {{ $employee->name }}!</h1>
                <p class="text-blue-100 mt-1">{{ $employee->department->name ?? 'Departamento não definido' }}</p>
                <p class="text-blue-100 text-sm">{{ $employee->company->name }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ number_format($stats['performance_percentage'], 1) }}%</div>
                <div class="text-sm text-blue-100">Última Avaliação</div>
                <div class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/20 mt-2">
                    {{ $stats['performance_class'] }}
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_evaluations'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total de Avaliações</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['average_performance'], 1) }}%</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Média Geral</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['current_year_evaluations'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Este Ano</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-lg {{ $stats['improvement_trend']['trend'] === 'improving' ? 'bg-green-100 text-green-600' : ($stats['improvement_trend']['trend'] === 'declining' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600') }}">
                    @if($stats['improvement_trend']['trend'] === 'improving')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    @elseif($stats['improvement_trend']['trend'] === 'declining')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                        </svg>
                    @endif
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $stats['improvement_trend']['change'] > 0 ? '+' : '' }}{{ number_format($stats['improvement_trend']['change'], 1) }}%
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tendência</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de Performance --}}
    @if(count($performanceChart) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Evolução do Desempenho</h3>
        <div class="h-64 w-full">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Avaliações Recentes --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Avaliações Recentes</h3>
                <a href="{{ route('employee.evaluations') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver Todas →
                </a>
            </div>
        </div>
        
        <div class="p-6">
            @if($recentEvaluations->count() > 0)
                <div class="space-y-4">
                    @foreach($recentEvaluations as $evaluation)
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 rounded-lg bg-{{ $evaluation->performance_color }}-100 text-{{ $evaluation->performance_color }}-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $evaluation->evaluation_period_formatted }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $evaluation->performance_class }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-{{ $evaluation->performance_color }}-600">{{ number_format($evaluation->final_percentage, 1) }}%</p>
                                <p class="text-xs text-gray-500">{{ $evaluation->approved_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma avaliação encontrada</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Suas avaliações aparecerão aqui quando estiverem disponíveis</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Alertas/Notificações --}}
    @if($stats['has_below_threshold'])
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <p class="text-yellow-800 font-medium">Atenção</p>
                <p class="text-yellow-700 text-sm">Você possui avaliações abaixo do threshold (50%). Consulte com seu gestor para planos de melhoria.</p>
            </div>
        </div>
    </div>
    @endif
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($performanceChart) > 0)
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($performanceChart, 'date')),
            datasets: [{
                label: 'Performance (%)',
                data: @json(array_column($performanceChart, 'percentage')),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif
});
</script>
@endsection