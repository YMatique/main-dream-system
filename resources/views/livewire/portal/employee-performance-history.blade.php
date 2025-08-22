{{-- resources/views/livewire/portal/employee-performance-history.blade.php --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Histórico de Desempenho</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Análise detalhada da sua evolução ao longo do tempo</p>
                </div>
                <div class="flex items-center space-x-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrar por ano:</label>
                    <select wire:model.live="yearFilter" 
                            class="px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[120px]">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Gráfico de Tendência --}}
        @if(count($chartData) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Evolução do Desempenho - {{ $yearFilter }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Acompanhe sua progressão mensal</p>
            </div>
            <div class="p-6">
                <div class="h-96 w-full">
                    <canvas id="performanceTrendChart"></canvas>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma avaliação encontrada</h3>
                <p class="text-gray-600 dark:text-gray-400">Não há dados de desempenho para o ano de {{ $yearFilter }}</p>
            </div>
        </div>
        @endif

        {{-- Análise de Tendências --}}
        @if($trendAnalysis && isset($trendAnalysis['change']))
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Análise de Tendências</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Insights sobre sua evolução</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Mudança Total --}}
                    <div class="text-center p-6 rounded-xl 
                        {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800' : 
                           (($trendAnalysis['trend'] ?? '') === 'declining' ? 'bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border border-red-200 dark:border-red-800' : 
                           'bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20 border border-gray-200 dark:border-gray-600') }}">
                        <div class="text-4xl font-bold mb-2 
                            {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'text-green-600 dark:text-green-400' : 
                               (($trendAnalysis['trend'] ?? '') === 'declining' ? 'text-red-600 dark:text-red-400' : 
                               'text-gray-600 dark:text-gray-400') }}">
                            {{ ($trendAnalysis['change'] ?? 0) > 0 ? '+' : '' }}{{ $trendAnalysis['change'] ?? 0 }}%
                        </div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Mudança Total</div>
                    </div>

                    {{-- Tendência --}}
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                            @php
                                $trendIcons = [
                                    'improving' => '📈',
                                    'declining' => '📉', 
                                    'stable' => '➡️',
                                    'insufficient_data' => '❓',
                                    'no_data' => '❓'
                                ];
                                $trendText = [
                                    'improving' => 'Melhorando',
                                    'declining' => 'Declinando',
                                    'stable' => 'Estável',
                                    'insufficient_data' => 'Dados Insuficientes',
                                    'no_data' => 'Sem Dados'
                                ];
                            @endphp
                            {{ $trendIcons[$trendAnalysis['trend'] ?? 'no_data'] ?? '❓' }}
                        </div>
                        <div class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                            {{ $trendText[$trendAnalysis['trend'] ?? 'no_data'] ?? 'Indefinido' }}
                        </div>
                        <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Tendência</div>
                    </div>

                    {{-- Consistência --}}
                    <div class="text-center p-6 rounded-xl
                        {{ ($trendAnalysis['consistency'] ?? '') === 'Muito Consistente' ? 'bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 border border-purple-200 dark:border-purple-800' :
                           (($trendAnalysis['consistency'] ?? '') === 'Consistente' ? 'bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border border-indigo-200 dark:border-indigo-800' :
                           'bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800') }}">
                        <div class="text-2xl font-bold mb-2
                            {{ ($trendAnalysis['consistency'] ?? '') === 'Muito Consistente' ? 'text-purple-600 dark:text-purple-400' :
                               (($trendAnalysis['consistency'] ?? '') === 'Consistente' ? 'text-indigo-600 dark:text-indigo-400' :
                               'text-yellow-600 dark:text-yellow-400') }}">
                            @php
                                $consistencyIcons = [
                                    'Muito Consistente' => '🎯',
                                    'Consistente' => '✅',
                                    'Moderadamente Variável' => '⚡',
                                    'Muito Variável' => '🌊',
                                    'N/A' => '❓'
                                ];
                            @endphp
                            {{ $consistencyIcons[$trendAnalysis['consistency'] ?? 'N/A'] ?? '❓' }}
                        </div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $trendAnalysis['consistency'] ?? 'N/A' }}
                        </div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Consistência</div>
                    </div>
                </div>

                {{-- Descrição da Tendência --}}
                <div class="p-6 rounded-xl 
                    {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800' :
                       (($trendAnalysis['trend'] ?? '') === 'declining' ? 'bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800' :
                       'bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800') }}">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'bg-green-100 dark:bg-green-900/20' :
                                   (($trendAnalysis['trend'] ?? '') === 'declining' ? 'bg-red-100 dark:bg-red-900/20' :
                                   'bg-blue-100 dark:bg-blue-900/20') }}">
                                <svg class="w-5 h-5 
                                    {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'text-green-600 dark:text-green-400' :
                                       (($trendAnalysis['trend'] ?? '') === 'declining' ? 'text-red-600 dark:text-red-400' :
                                       'text-blue-600 dark:text-blue-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold 
                                {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'text-green-900 dark:text-green-100' :
                                   (($trendAnalysis['trend'] ?? '') === 'declining' ? 'text-red-900 dark:text-red-100' :
                                   'text-blue-900 dark:text-blue-100') }} mb-1">Análise do Período</h4>
                            <p class="
                                {{ ($trendAnalysis['trend'] ?? '') === 'improving' ? 'text-green-800 dark:text-green-200' :
                                   (($trendAnalysis['trend'] ?? '') === 'declining' ? 'text-red-800 dark:text-red-200' :
                                   'text-blue-800 dark:text-blue-200') }}">
                                {{ $trendAnalysis['description'] ?? 'Sem análise disponível' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Performance por Métrica --}}
        @if(count($performanceMetrics) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Performance por Métrica</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Detalhamento do desempenho em cada critério avaliado</p>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($performanceMetrics as $metric)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $metric['name'] }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Peso na avaliação: {{ $metric['weight'] }}%</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-center px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($metric['average'], 1) }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Média</div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $metric['trend'] === 'improving' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                           ($metric['trend'] === 'declining' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 
                                           'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300') }}">
                                        @if($metric['trend'] === 'improving')
                                            📈 Melhorando
                                        @elseif($metric['trend'] === 'declining')
                                            📉 Declinando
                                        @else
                                            ➡️ Estável
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                @foreach($metric['scores'] as $score)
                                    <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($score['score'], 1) }}</div>
                                        <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $score['period'] }}</div>
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
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Comparação com o Departamento</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Veja como você se posiciona em relação aos colegas</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ number_format($comparisonData['my_average'], 1) }}%</div>
                        <div class="text-sm font-semibold text-blue-900 dark:text-blue-100">Minha Média</div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-700 dark:to-slate-700 border border-gray-200 dark:border-gray-600 rounded-xl">
                        <div class="text-4xl font-bold text-gray-600 dark:text-gray-400 mb-2">{{ number_format($comparisonData['department_average'], 1) }}%</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Média do Departamento</div>
                    </div>
                    <div class="text-center p-6 rounded-xl border
                        {{ $comparisonData['difference'] >= 0 ? 'bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-800' : 'bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-red-200 dark:border-red-800' }}">
                        <div class="text-4xl font-bold mb-2 {{ $comparisonData['difference'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $comparisonData['difference'] > 0 ? '+' : '' }}{{ number_format($comparisonData['difference'], 1) }}%
                        </div>
                        <div class="text-sm font-semibold {{ $comparisonData['difference'] >= 0 ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">Diferença</div>
                    </div>
                </div>

                {{-- Mensagens Motivacionais --}}
                @if($comparisonData['difference'] > 0)
                    <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 border border-green-200 dark:border-green-800 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100">🎉 Excelente trabalho!</h4>
                                <p class="text-green-800 dark:text-green-200">Seu desempenho está {{ number_format($comparisonData['difference'], 1) }}% acima da média do departamento. Continue assim!</p>
                            </div>
                        </div>
                    </div>
                @elseif($comparisonData['difference'] < -5)
                    <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/10 dark:to-orange-900/10 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">💪 Oportunidade de crescimento</h4>
                                <p class="text-yellow-800 dark:text-yellow-200">Há espaço para melhorar e alcançar a média do departamento. Converse com seu gestor sobre estratégias de desenvolvimento.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100">👍 Desempenho equilibrado</h4>
                                <p class="text-blue-800 dark:text-blue-200">Você está alinhado com a média do departamento. Continue mantendo a consistência!</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($chartData) > 0)
    const chartElement = document.getElementById('performanceTrendChart');
    
    if (chartElement) {
        const ctx = chartElement.getContext('2d');
        
        // Dados do gráfico
        const chartData = @json($chartData);
        console.log('Chart Data:', chartData); // Debug
        
        // Preparar cores dos pontos
        const pointColors = chartData.map(function(item) {
            switch(item.color) {
                case 'green': return '#22c55e';
                case 'blue': return '#3b82f6';
                case 'yellow': return '#eab308';
                case 'red': return '#ef4444';
                default: return '#6b7280';
            }
        });

        // Preparar cores de fundo dos pontos
        const pointBackgroundColors = chartData.map(function(item) {
            switch(item.color) {
                case 'green': return 'rgba(34, 197, 94, 0.1)';
                case 'blue': return 'rgba(59, 130, 246, 0.1)';
                case 'yellow': return 'rgba(234, 179, 8, 0.1)';
                case 'red': return 'rgba(239, 68, 68, 0.1)';
                default: return 'rgba(107, 114, 128, 0.1)';
            }
        });
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.date),
                datasets: [{
                    label: 'Performance (%)',
                    data: chartData.map(item => parseFloat(item.percentage)),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: pointColors,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 8,
                    pointHoverRadius: 12,
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return 'Período: ' + context[0].label;
                            },
                            label: function(context) {
                                const dataIndex = context.dataIndex;
                                const item = chartData[dataIndex];
                                return [
                                    'Performance: ' + context.parsed.y + '%',
                                    'Classificação: ' + item.class,
                                    'Pontuação: ' + item.total_score
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        borderCapStyle: 'round',
                        borderJoinStyle: 'round'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    } else {
        console.error('Canvas element not found!');
    }
    @else
    console.log('No chart data available');
    @endif
});
</script>
@endsection