{{-- resources/views/livewire/portal/employee-performance-history.blade.php --}}
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Histórico de Desempenho</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Análise detalhada da sua evolução ao longo do tempo</p>
        </div>
        <div>
            <select wire:model.live="yearFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Gráfico de Tendência --}}
    @if(count($chartData) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Evolução do Desempenho - {{ $yearFilter }}</h3>
        <div class="h-80 w-full">
            <canvas id="performanceTrendChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Análise de Tendências --}}
 
{{-- Análise de Tendências --}}
@if($trendAnalysis && isset($trendAnalysis['change']))
<div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Análise de Tendências</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="text-2xl font-bold {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'text-green-600' : (($trendAnalysis['trend'] ?? '') === 'declining' ? 'text-red-600' : 'text-gray-600') }}">
                {{ ($trendAnalysis['change'] ?? 0) > 0 ? '+' : '' }}{{ $trendAnalysis['change'] ?? 0 }}%
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Mudança Total</div>
        </div>
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ ucfirst(str_replace('_', ' ', $trendAnalysis['trend'] ?? 'Sem dados')) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Tendência</div>
        </div>
        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $trendAnalysis['consistency'] ?? 'N/A' }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Consistência</div>
        </div>
    </div>
    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <p class="text-blue-800 dark:text-blue-200">{{ $trendAnalysis['description'] ?? 'Sem análise disponível' }}</p>
    </div>
</div>
@endif


    {{-- Performance por Métrica --}}
    @if(count($performanceMetrics) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance por Métrica</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                @foreach($performanceMetrics as $metric)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $metric['name'] }}</h4>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Peso: {{ $metric['weight'] }}%</span>
                                <span class="text-sm font-medium">Média: {{ number_format($metric['average'], 1) }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $metric['trend'] === 'improving' ? 'bg-green-100 text-green-800' : ($metric['trend'] === 'declining' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($metric['trend']) }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($metric['scores'] as $score)
                                <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ number_format($score['score'], 1) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $score['period'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $score['display_value'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Comparação com Departamento --}}
    @if($comparisonData)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Comparação com o Departamento</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($comparisonData['my_average'], 1) }}%</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Minha Média</div>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-2xl font-bold text-gray-600">{{ number_format($comparisonData['department_average'], 1) }}%</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Média do Departamento</div>
            </div>
            <div class="text-center p-4 bg-{{ $comparisonData['difference'] >= 0 ? 'green' : 'red' }}-50 dark:bg-{{ $comparisonData['difference'] >= 0 ? 'green' : 'red' }}-900/20 rounded-lg">
                <div class="text-2xl font-bold text-{{ $comparisonData['difference'] >= 0 ? 'green' : 'red' }}-600">
                    {{ $comparisonData['difference'] > 0 ? '+' : '' }}{{ number_format($comparisonData['difference'], 1) }}%
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Diferença</div>
            </div>
        </div>
        @if($comparisonData['difference'] > 0)
            <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-green-800 dark:text-green-200">🎉 Parabéns! Seu desempenho está acima da média do departamento.</p>
            </div>
        @elseif($comparisonData['difference'] < -5)
            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-yellow-800 dark:text-yellow-200">💪 Há oportunidades de melhoria para alcançar a média do departamento.</p>
            </div>
        @endif
    </div>
    @endif
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    @if(count($chartData) > 0)
    const ctx = document.getElementById('performanceTrendChart').getContext('2d');
    
    // Preparar cores no PHP primeiro
    const chartData = @json($chartData);
    const pointColors = chartData.map(function(item) {
        switch(item.color) {
            case 'green': return 'rgb(34, 197, 94)';
            case 'blue': return 'rgb(59, 130, 246)';
            case 'yellow': return 'rgb(234, 179, 8)';
            case 'red': return 'rgb(239, 68, 68)';
            default: return 'rgb(107, 114, 128)';
        }
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.date),
            datasets: [{
                label: 'Performance (%)',
                data: chartData.map(item => item.percentage),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: pointColors,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
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
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            return 'Classificação: ' + chartData[context.dataIndex].class;
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection