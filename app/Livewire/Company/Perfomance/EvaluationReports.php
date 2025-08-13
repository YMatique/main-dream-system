<?php

namespace App\Livewire\Company\Perfomance;

use App\Models\Company\Department;
use App\Models\Company\Employee;
use App\Models\Company\Evaluation\PerformanceEvaluation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationReports extends Component
{

    use WithPagination;

    // Report Type
    public $reportType = 'overview'; // overview, performance, department, employee, trends

    // Filters
    public $startDate = '';
    public $endDate = '';
    public $departmentFilter = '';
    public $employeeFilter = '';
    public $statusFilter = 'approved';
    public $performanceFilter = 'all'; // all, excellent, good, satisfactory, poor
    public $period = 'last_6_months'; // last_month, last_3_months, last_6_months, last_year, custom

    // Data
    public $departments = [];
    public $employees = [];
    public $reportData = [];
    public $chartData = [];
    public $stats = [];

    // Export
    public $exportFormat = 'xlsx'; // xlsx, csv, pdf

    protected $queryString = [
        'reportType' => ['except' => 'overview'],
        'departmentFilter' => ['except' => ''],
        'employeeFilter' => ['except' => ''],
        'statusFilter' => ['except' => 'approved'],
        'performanceFilter' => ['except' => 'all'],
        'period' => ['except' => 'last_6_months'],
    ];

    public function mount()
    {
        $this->checkPermissions();
        $this->loadData();
        $this->setPeriodDates();
        $this->generateReport();
    }

    // public function render()
    // {
    //     return view('livewire.company.performance.evaluation-reports', [
    //         'reportData' => $this->reportData,
    //         'chartData' => $this->chartData,
    //         'stats' => $this->stats,
    //     ])
    //     ->title('Relatórios de Avaliação')
    //     ->layout('layouts.company');
    // }

    protected function checkPermissions()
    {
        $user = auth()->user();
        
        if (!$user->isCompanyAdmin() && !$user->hasAnyPermission([
            'reports.view', 
            'evaluation.reports', 
            'performance.reports'
        ])) {
            abort(403, 'Sem permissão para visualizar relatórios');
        }
    }

    protected function loadData()
    {
        $user = auth()->user();
        
        // Carregar departamentos
        $this->departments = Department::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Carregar funcionários (filtrar por departamento se selecionado)
        $employeesQuery = Employee::where('company_id', $user->company_id)
            ->where('is_active', true);
            
        if ($this->departmentFilter) {
            $employeesQuery->where('department_id', $this->departmentFilter);
        }
        
        $this->employees = $employeesQuery->orderBy('name')->get();
    }

    protected function setPeriodDates()
    {
        switch ($this->period) {
            case 'last_month':
                $this->startDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_3_months':
                $this->startDate = now()->subMonths(3)->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_6_months':
                $this->startDate = now()->subMonths(6)->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_year':
                $this->startDate = now()->subYear()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Manter datas customizadas se já definidas
                if (!$this->startDate) {
                    $this->startDate = now()->subMonths(6)->format('Y-m-d');
                }
                if (!$this->endDate) {
                    $this->endDate = now()->format('Y-m-d');
                }
                break;
        }
    }

    public function generateReport()
    {
        $this->resetPage();
        
        switch ($this->reportType) {
            case 'overview':
                $this->generateOverviewReport();
                break;
            case 'performance':
                $this->generatePerformanceReport();
                break;
            case 'department':
                $this->generateDepartmentReport();
                break;
            case 'employee':
                $this->generateEmployeeReport();
                break;
            case 'trends':
                $this->generateTrendsReport();
                break;
        }
    }

    // protected function getBaseQuery()
    // {
    //     $query = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
    //         ->with(['employee.department', 'evaluator', 'approvedBy']);

    //     // Filtros de data
    //     if ($this->startDate && $this->endDate) {
    //         $query->whereBetween('evaluation_period', [
    //             Carbon::parse($this->startDate)->startOfMonth(),
    //             Carbon::parse($this->endDate)->endOfMonth()
    //         ]);
    //     }

    //     // Filtro de departamento
    //     if ($this->departmentFilter) {
    //         $query->whereHas('employee', function($q) {
    //             $q->where('department_id', $this->departmentFilter);
    //         });
    //     }

    //     // Filtro de funcionário
    //     if ($this->employeeFilter) {
    //         $query->where('employee_id', $this->employeeFilter);
    //     }

    //     // Filtro de status
    //     if ($this->statusFilter) {
    //         $query->where('status', $this->statusFilter);
    //     }

    //     // Filtro de performance
    //     if ($this->performanceFilter !== 'all') {
    //         switch ($this->performanceFilter) {
    //             case 'excellent':
    //                 $query->where('final_percentage', '>=', 90);
    //                 break;
    //             case 'good':
    //                 $query->whereBetween('final_percentage', [70, 89.99]);
    //                 break;
    //             case 'satisfactory':
    //                 $query->whereBetween('final_percentage', [50, 69.99]);
    //                 break;
    //             case 'poor':
    //                 $query->where('final_percentage', '<', 50);
    //                 break;
    //         }
    //     }

    //     return $query;
    // }

    protected function getBaseQuery()
{
    $query = PerformanceEvaluation::where('performance_evaluations.company_id', auth()->user()->company_id)
        ->with(['employee.department', 'evaluator', 'approvedBy']);

    // Filtros de data
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('performance_evaluations.evaluation_period', [
            Carbon::parse($this->startDate)->startOfMonth(),
            Carbon::parse($this->endDate)->endOfMonth()
        ]);
    }

    // Filtro de departamento
    if ($this->departmentFilter) {
        $query->whereHas('employee', function($q) {
            $q->where('employees.department_id', $this->departmentFilter);
        });
    }

    // Filtro de funcionário
    if ($this->employeeFilter) {
        $query->where('performance_evaluations.employee_id', $this->employeeFilter);
    }

    // Filtro de status
    if ($this->statusFilter) {
        $query->where('performance_evaluations.status', $this->statusFilter);
    }

    // Filtro de performance
    if ($this->performanceFilter !== 'all') {
        switch ($this->performanceFilter) {
            case 'excellent':
                $query->where('performance_evaluations.final_percentage', '>=', 90);
                break;
            case 'good':
                $query->whereBetween('performance_evaluations.final_percentage', [70, 89.99]);
                break;
            case 'satisfactory':
                $query->whereBetween('performance_evaluations.final_percentage', [50, 69.99]);
                break;
            case 'poor':
                $query->where('performance_evaluations.final_percentage', '<', 50);
                break;
        }
    }

    return $query;
}
protected function generateOverviewReport()
{
    $query = $this->getBaseQuery();
    
    // Estatísticas gerais
    $this->stats = [
        'total_evaluations' => $query->count(),
        'approved_evaluations' => (clone $query)->where('performance_evaluations.status', 'approved')->count(),
        'pending_evaluations' => (clone $query)->where('performance_evaluations.status', 'submitted')->count(),
        'rejected_evaluations' => (clone $query)->where('performance_evaluations.status', 'rejected')->count(),
        'below_threshold' => (clone $query)->where('performance_evaluations.is_below_threshold', true)->count(),
        'average_performance' => round((clone $query)->where('performance_evaluations.status', 'approved')->avg('performance_evaluations.final_percentage') ?? 0, 2),
        'departments_evaluated' => (clone $query)->join('employees', 'performance_evaluations.employee_id', '=', 'employees.id')
            ->where('performance_evaluations.status', 'approved')
            ->distinct('employees.department_id')->count(),
        'employees_evaluated' => (clone $query)->distinct('performance_evaluations.employee_id')->count(),
    ];

    // Performance por faixa
    $performanceRanges = [
        'Excelente (90-100%)' => (clone $query)->where('performance_evaluations.final_percentage', '>=', 90)->count(),
        'Bom (70-89%)' => (clone $query)->whereBetween('performance_evaluations.final_percentage', [70, 89.99])->count(),
        'Satisfatório (50-69%)' => (clone $query)->whereBetween('performance_evaluations.final_percentage', [50, 69.99])->count(),
        'Péssimo (<50%)' => (clone $query)->where('performance_evaluations.final_percentage', '<', 50)->count(),
    ];

    // Performance por departamento
    $departmentPerformance = Department::where('departments.company_id', auth()->user()->company_id)
        ->where('departments.is_active', true)
        ->withCount(['employees as evaluations_count' => function($q) {
            $q->join('performance_evaluations', 'employees.id', '=', 'performance_evaluations.employee_id')
              ->whereBetween('performance_evaluations.evaluation_period', [
                  Carbon::parse($this->startDate)->startOfMonth(),
                  Carbon::parse($this->endDate)->endOfMonth()
              ])
              ->where('performance_evaluations.status', 'approved')
              ->where('performance_evaluations.company_id', auth()->user()->company_id);
        }])
        ->with(['employees' => function($q) {
            $q->where('employees.company_id', auth()->user()->company_id)
              ->whereHas('evaluations', function($eval) {
                $eval->whereBetween('performance_evaluations.evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])->where('performance_evaluations.status', 'approved')
                  ->where('performance_evaluations.company_id', auth()->user()->company_id);
            })->with(['evaluations' => function($eval) {
                $eval->whereBetween('performance_evaluations.evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])->where('performance_evaluations.status', 'approved')
                  ->where('performance_evaluations.company_id', auth()->user()->company_id);
            }]);
        }])
        ->get()
        ->map(function($dept) {
            $evaluations = $dept->employees->flatMap->evaluations;
            return [
                'department' => $dept->name,
                'total_evaluations' => $evaluations->count(),
                'average_performance' => $evaluations->avg('final_percentage') ?? 0,
                'below_threshold' => $evaluations->where('is_below_threshold', true)->count(),
            ];
        })
        ->filter(function($item) {
            return $item['total_evaluations'] > 0;
        });

    $this->reportData = [
        'performance_ranges' => $performanceRanges,
        'department_performance' => $departmentPerformance,
    ];

    // Dados para gráficos
    $this->chartData = [
        'performance_chart' => [
            'labels' => array_keys($performanceRanges),
            'data' => array_values($performanceRanges),
            'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444']
        ],
        'department_chart' => [
            'labels' => $departmentPerformance->pluck('department')->toArray(),
            'data' => $departmentPerformance->pluck('average_performance')->toArray(),
            'colors' => $this->generateColors($departmentPerformance->count())
        ]
    ];
}

protected function generatePerformanceReport()
{
    $query = $this->getBaseQuery()->where('performance_evaluations.status', 'approved');
    
    $evaluations = $query->orderBy('performance_evaluations.final_percentage', 'desc')
        ->paginate(50);

    // Top performers
    $topPerformers = (clone $query)->orderBy('performance_evaluations.final_percentage', 'desc')
        ->take(10);

    // Bottom performers (apenas se admin)
    $bottomPerformers = collect();
    if (auth()->user()->isCompanyAdmin()) {
        $bottomPerformers = (clone $query)->orderBy('performance_evaluations.final_percentage', 'asc')
            ->limit(10)
            ->get();
    }

    $this->reportData = [
        'evaluations' => $evaluations,
        'top_performers' => $topPerformers,
        'bottom_performers' => $bottomPerformers,
    ];

    // Estatísticas de performance
    $this->stats = [
        'highest_score' => (clone $query)->max('performance_evaluations.final_percentage') ?? 0,
        'lowest_score' => (clone $query)->min('performance_evaluations.final_percentage') ?? 0,
        'median_score' => $this->calculateMedian($query, 'performance_evaluations.final_percentage'),
        'std_deviation' => $this->calculateStandardDeviation($query, 'performance_evaluations.final_percentage'),
    ];
}

protected function generateDepartmentReport()
{
    $departments = Department::where('departments.company_id', auth()->user()->company_id)
        ->where('departments.is_active', true)
        ->with(['employees' => function($q) {
            $q->where('employees.company_id', auth()->user()->company_id)
              ->with(['evaluations' => function($eval) {
                $eval->whereBetween('performance_evaluations.evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])->where('performance_evaluations.status', 'approved')
                  ->where('performance_evaluations.company_id', auth()->user()->company_id);
            }]);
        }])
        ->get()
        ->map(function($dept) {
            $evaluations = $dept->employees->flatMap->evaluations;
            
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'total_employees' => $dept->employees->count(),
                'evaluated_employees' => $evaluations->unique('employee_id')->count(),
                'total_evaluations' => $evaluations->count(),
                'average_performance' => round($evaluations->avg('final_percentage') ?? 0, 2),
                'excellent_count' => $evaluations->where('final_percentage', '>=', 90)->count(),
                'good_count' => $evaluations->whereBetween('final_percentage', [70, 89.99])->count(),
                'satisfactory_count' => $evaluations->whereBetween('final_percentage', [50, 69.99])->count(),
                'poor_count' => $evaluations->where('final_percentage', '<', 50)->count(),
                'below_threshold' => $evaluations->where('is_below_threshold', true)->count(),
            ];
        })
        ->sortByDesc('average_performance');

    $this->reportData = ['departments' => $departments];
    
    $this->chartData = [
        'department_comparison' => [
            'labels' => $departments->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Performance Média',
                    'data' => $departments->pluck('average_performance')->toArray(),
                    'backgroundColor' => '#3B82F6'
                ],
                [
                    'label' => 'Avaliações < 50%',
                    'data' => $departments->pluck('below_threshold')->toArray(),
                    'backgroundColor' => '#EF4444'
                ]
            ]
        ]
    ];
}

protected function generateEmployeeReport()
{
    if (!$this->employeeFilter) {
        $this->reportData = ['message' => 'Selecione um funcionário para ver o relatório detalhado.'];
        return;
    }

    $employee = Employee::where('employees.id', $this->employeeFilter)
        ->where('employees.company_id', auth()->user()->company_id)
        ->firstOrFail();
    
    $evaluations = PerformanceEvaluation::where('performance_evaluations.employee_id', $this->employeeFilter)
        ->where('performance_evaluations.company_id', auth()->user()->company_id)
        ->where('performance_evaluations.status', 'approved')
        ->whereBetween('performance_evaluations.evaluation_period', [
            Carbon::parse($this->startDate)->startOfMonth(),
            Carbon::parse($this->endDate)->endOfMonth()
        ])
        ->with(['responses.metric'])
        ->orderBy('performance_evaluations.evaluation_period', 'desc')
        ->get();

    // Análise de progresso
    $progressData = $evaluations->map(function($eval) {
        return [
            'period' => $eval->evaluation_period->format('m/Y'),
            'score' => $eval->final_percentage,
            'class' => $eval->performance_class,
        ];
    })->reverse();

    // Análise por métrica (última avaliação)
    $latestEvaluation = $evaluations->first();
    $metricAnalysis = collect();
    
    if ($latestEvaluation) {
        $metricAnalysis = $latestEvaluation->responses->map(function($response) {
            return [
                'metric' => $response->metric->name,
                'score' => $response->calculated_score,
                'weight' => $response->metric->weight,
                'weighted_score' => ($response->calculated_score * $response->metric->weight) / 100,
                'comments' => $response->comments,
            ];
        })->sortByDesc('weighted_score');
    }

    $this->reportData = [
        'employee' => $employee,
        'evaluations' => $evaluations,
        'progress_data' => $progressData,
        'metric_analysis' => $metricAnalysis,
        'total_evaluations' => $evaluations->count(),
        'average_performance' => round($evaluations->avg('final_percentage') ?? 0, 2),
        'best_performance' => $evaluations->max('final_percentage') ?? 0,
        'worst_performance' => $evaluations->min('final_percentage') ?? 0,
    ];

    $this->chartData = [
        'progress_chart' => [
            'labels' => $progressData->pluck('period')->toArray(),
            'data' => $progressData->pluck('score')->toArray(),
            'borderColor' => '#3B82F6',
            'backgroundColor' => '#3B82F620'
        ]
    ];
}

protected function generateTrendsReport()
{
    // Tendências por mês
    $monthlyTrends = $this->getBaseQuery()
        ->where('performance_evaluations.status', 'approved')
        ->select(
            DB::raw('YEAR(performance_evaluations.evaluation_period) as year'),
            DB::raw('MONTH(performance_evaluations.evaluation_period) as month'),
            DB::raw('COUNT(*) as total_evaluations'),
            DB::raw('AVG(performance_evaluations.final_percentage) as avg_performance'),
            DB::raw('COUNT(CASE WHEN performance_evaluations.is_below_threshold = 1 THEN 1 END) as below_threshold_count')
        )
        ->groupBy(DB::raw('YEAR(performance_evaluations.evaluation_period)'), DB::raw('MONTH(performance_evaluations.evaluation_period)'))
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function($item) {
            return [
                'period' => sprintf('%02d/%04d', $item->month, $item->year),
                'total_evaluations' => $item->total_evaluations,
                'avg_performance' => round($item->avg_performance, 2),
                'below_threshold_count' => $item->below_threshold_count,
                'below_threshold_percentage' => $item->total_evaluations > 0 
                    ? round(($item->below_threshold_count / $item->total_evaluations) * 100, 2) 
                    : 0,
            ];
        });

    $this->reportData = [
        'monthly_trends' => $monthlyTrends,
    ];

    $this->chartData = [
        'trends_chart' => [
            'labels' => $monthlyTrends->pluck('period')->toArray(),
            'datasets' => [
                [
                    'label' => 'Performance Média',
                    'data' => $monthlyTrends->pluck('avg_performance')->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => '#10B98120',
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Total de Avaliações',
                    'data' => $monthlyTrends->pluck('total_evaluations')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => '#3B82F620',
                    'yAxisID' => 'y1'
                ]
            ]
        ]
    ];
}

// Método helper corrigido também
protected function calculateMedian($query, $column)
{
    $values = (clone $query)->pluck($column)->sort()->values();
    $count = $values->count();
    
    if ($count === 0) return 0;
    
    if ($count % 2 === 0) {
        return ($values[$count / 2 - 1] + $values[$count / 2]) / 2;
    } else {
        return $values[intval($count / 2)];
    }
}

protected function calculateStandardDeviation($query, $column)
{
    $values = (clone $query)->pluck($column);
    $mean = $values->avg();
    $count = $values->count();
    
    if ($count <= 1) return 0;
    
    $variance = $values->reduce(function($carry, $value) use ($mean) {
        return $carry + pow($value - $mean, 2);
    }, 0) / ($count - 1);
    
    return round(sqrt($variance), 2);
}
    protected function generateColors($count)
    {
        $colors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];
        
        return array_slice($colors, 0, $count);
    }

    // ===== EXPORT METHODS =====

    public function exportReport()
    {
        // TODO: Implementar exportação baseada no formato selecionado
        $this->dispatch('export-started', [
            'format' => $this->exportFormat,
            'type' => $this->reportType
        ]);
    }

    // ===== LIFECYCLE HOOKS =====

    public function updatedReportType()
    {
        $this->generateReport();
    }

    public function updatedPeriod()
    {
        $this->setPeriodDates();
        $this->generateReport();
    }

    public function updatedDepartmentFilter()
    {
        $this->loadData(); // Recarregar funcionários
        $this->generateReport();
    }

    public function updatedEmployeeFilter()
    {
        $this->generateReport();
    }

    public function updatedStatusFilter()
    {
        $this->generateReport();
    }

    public function updatedPerformanceFilter()
    {
        $this->generateReport();
    }

    public function updatedStartDate()
    {
        if ($this->period === 'custom') {
            $this->generateReport();
        }
    }

    public function updatedEndDate()
    {
        if ($this->period === 'custom') {
            $this->generateReport();
        }
    }
    public function render()
    {
        return view('livewire.company.perfomance.evaluation-reports',[
            'reportData' => $this->reportData,
            'chartData' => $this->chartData,
            'stats' => $this->stats,
        ])
        ->title('Relatórios de Avaliação')
        ->layout('layouts.company');
    }
}
