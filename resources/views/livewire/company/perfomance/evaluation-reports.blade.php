<div>
    <div>
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Relatórios de Avaliação
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Análise detalhada do desempenho dos funcionários
                    </p>
                </div>
                
                <!-- Export Button -->
                <div class="flex items-center space-x-3">
                    <select wire:model="exportFormat" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="xlsx">Excel</option>
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                    <button wire:click="exportReport" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 sm:px-6 lg:px-8 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Relatório
                    </label>
                    <select wire:model.live="reportType" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="overview">Visão Geral</option>
                        <option value="performance">Performance</option>
                        <option value="department">Por Departamento</option>
                        <option value="employee">Por Funcionário</option>
                        <option value="trends">Tendências</option>
                    </select>
                </div>

                <!-- Period -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Período
                    </label>
                    <select wire:model.live="period" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="last_month">Último Mês</option>
                        <option value="last_3_months">Últimos 3 Meses</option>
                        <option value="last_6_months">Últimos 6 Meses</option>
                        <option value="last_year">Último Ano</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Departamento
                    </label>
                    <select wire:model.live="departmentFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Employee Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Funcionário
                    </label>
                    <select wire:model.live="employeeFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select wire:model.live="statusFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="">Todos</option>
                        <option value="approved">Aprovado</option>
                        <option value="submitted">Submetido</option>
                        <option value="rejected">Rejeitado</option>
                    </select>
                </div>

                <!-- Performance Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Performance
                    </label>
                    <select wire:model.live="performanceFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                        <option value="all">Todas</option>
                        <option value="excellent">Excelente (≥90%)</option>
                        <option value="good">Bom (70-89%)</option>
                        <option value="satisfactory">Satisfatório (50-69%)</option>
                        <option value="poor">Péssimo (<50%)</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range (if period is custom) -->
            @if($period === 'custom')
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Data Início
                    </label>
                    <input type="date" wire:model.live="startDate" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Data Fim
                    </label>
                    <input type="date" wire:model.live="endDate" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Content Section -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <!-- Loading State -->
        <div wire:loading.delay class="text-center py-8">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-500 hover:bg-blue-400 transition ease-in-out duration-150">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Gerando relatório...
            </div>
        </div>

        <!-- Report Content -->
        <div wire:loading.remove>
            @if($reportType === 'overview')
                {{-- @include('livewire.company.performance.reports.overview') --}}
            @elseif($reportType === 'performance')
                {{-- @include('livewire.company.performance.reports.performance') --}}
            @elseif($reportType === 'department')
                {{-- @include('livewire.company.performance.reports.department') --}}
            @elseif($reportType === 'employee')
                {{-- @include('livewire.company.performance.reports.employee') --}}
            @elseif($reportType === 'trends')
                {{-- @include('livewire.company.performance.reports.trends') --}}
            @endif
        </div>
    </div>
</div>

<!-- Overview Report Template -->
@if($reportType === 'overview')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total de Avaliações
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $stats['total_evaluations'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Aprovadas
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $stats['approved_evaluations'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Pendentes
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $stats['pending_evaluations'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-percentage text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Performance Média
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $stats['average_performance'] ?? 0 }}%
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Distribution Chart -->
    @if(isset($chartData['performance_chart']))
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Distribuição de Performance
        </h3>
        <div class="h-64">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['performance_chart']['labels']),
                    datasets: [{
                        data: @json($chartData['performance_chart']['data']),
                        backgroundColor: @json($chartData['performance_chart']['colors']),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endif

    <!-- Department Performance Table -->
    @if(isset($reportData['department_performance']) && $reportData['department_performance']->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Performance por Departamento
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Departamento
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Avaliações
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Performance Média
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Abaixo do Limite
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reportData['department_performance'] as $dept)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $dept['department'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $dept['total_evaluations'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <span class="mr-2">{{ number_format($dept['average_performance'], 1) }}%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $dept['average_performance'] }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($dept['below_threshold'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $dept['below_threshold'] }}
                                </span>
                            @else
                                <span class="text-green-600">0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Performance Report Template -->
@if($reportType === 'performance')
<div class="space-y-6">
    <!-- Performance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Maior Pontuação</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['highest_score'] ?? 0 }}%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Menor Pontuação</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['lowest_score'] ?? 0 }}%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Mediana</div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['median_score'] ?? 0 }}%</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Desvio Padrão</div>
            <div class="text-2xl font-bold text-gray-600">{{ $stats['std_deviation'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Top Performers -->
    @if(isset($reportData['top_performers']) && $reportData['top_performers']->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-green-600">
                <i class="fas fa-trophy mr-2"></i>Top 10 Melhores Performances
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Posição
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Funcionário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Departamento
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Performance
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Período
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reportData['top_performers'] as $index => $evaluation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            @if($index < 3)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800') }}">
                                    {{ $index + 1 }}
                                </span>
                            @else
                                <span class="text-gray-600">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $evaluation->employee->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $evaluation->employee->department->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-green-600 mr-2">{{ $evaluation->final_percentage }}%</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $evaluation->performance_class }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $evaluation->evaluation_period->format('m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>