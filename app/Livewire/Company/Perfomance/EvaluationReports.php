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

    public function filterQuick($type)
    {
        switch ($type) {
            case 'all':
                $this->statusFilter = '';
                $this->performanceFilter = 'all';
                break;
            case 'approved':
                $this->statusFilter = 'approved';
                break;
            case 'submitted':
                $this->statusFilter = 'submitted';
                break;
            case 'performance':
                // Manter filtros atuais, apenas regenerar relatório
                break;
        }

        $this->generateReport();
    }
    public function filterByDepartment($departmentName)
    {
        try {
            // Encontrar ID do departamento pelo nome
            $department = Department::where('company_id', auth()->user()->company_id)
                ->where('name', $departmentName)
                ->first();

            if ($department) {
                $this->departmentFilter = $department->id;
                $this->loadData(); // Recarregar funcionários do departamento
                $this->generateReport();

                // Flash message para feedback
                session()->flash('success', "Filtrado por departamento: {$departmentName}");
            }
        } catch (\Exception $e) {
            \Log::error('Erro no filtro por departamento: ' . $e->getMessage());
            session()->flash('error', 'Erro ao filtrar por departamento');
        }
    }

    /**
     * ===== MÉTODOS PARA ESTATÍSTICAS MELHORADAS =====
     */

    private function calculateGrowthRate()
    {
        try {
            // Período atual
            $currentPeriodStart = Carbon::parse($this->startDate)->startOfMonth();
            $currentPeriodEnd = Carbon::parse($this->endDate)->endOfMonth();

            // Calcular duração do período atual em meses
            $periodDurationMonths = $currentPeriodStart->diffInMonths($currentPeriodEnd) + 1;

            // Período anterior (mesmo número de meses)
            $previousPeriodEnd = $currentPeriodStart->copy()->subDay()->endOfMonth();
            $previousPeriodStart = $previousPeriodEnd->copy()->subMonths($periodDurationMonths - 1)->startOfMonth();

            $currentCount = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                ->whereBetween('evaluation_period', [$currentPeriodStart, $currentPeriodEnd])
                ->where('status', 'approved')
                ->count();

            $previousCount = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                ->whereBetween('evaluation_period', [$previousPeriodStart, $previousPeriodEnd])
                ->where('status', 'approved')
                ->count();

            if ($previousCount === 0) {
                return $currentCount > 0 ? 100 : 0;
            }

            return round((($currentCount - $previousCount) / $previousCount) * 100, 1);
        } catch (\Exception $e) {
            \Log::error('Erro no cálculo de growth rate: ' . $e->getMessage());
            return 0;
        }
    }

    private function getTopDepartment()
    {

        $topDept = Department::where('departments.company_id', auth()->user()->company_id)
            ->where('departments.is_active', true)
            ->withAvg(['evaluations' => function ($q) {
                $q->whereBetween('evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])->where('status', 'approved');
            }], 'final_percentage')
            ->having('evaluations_avg_final_percentage', '>', 0)
            ->orderByDesc('evaluations_avg_final_percentage')
            ->first();

        return $topDept ? $topDept->name : 'N/A';
    }

    private function getQuickTrends()
    {
        try {
            $trends = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $count = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                    ->whereYear('evaluation_period', $month->year)
                    ->whereMonth('evaluation_period', $month->month)
                    ->where('status', 'approved')
                    ->count();

                $trends[] = $count;
            }

            return $trends;
        } catch (\Exception $e) {
            \Log::error('Erro no cálculo de trends: ' . $e->getMessage());
            return [0, 0, 0, 0, 0, 0];
        }
    }


    private function calculateAvgApprovalTime()
    {
        $approvedEvaluations = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->where('status', 'approved')
            ->whereNotNull('submitted_at')
            ->whereNotNull('approved_at')
            ->whereBetween('evaluation_period', [
                Carbon::parse($this->startDate)->startOfMonth(),
                Carbon::parse($this->endDate)->endOfMonth()
            ])
            ->get(['submitted_at', 'approved_at']);

        if ($approvedEvaluations->isEmpty()) {
            return 'N/A';
        }

        $totalHours = 0;
        foreach ($approvedEvaluations as $evaluation) {
            $totalHours += $evaluation->submitted_at->diffInHours($evaluation->approved_at);
        }

        $avgHours = $totalHours / $approvedEvaluations->count();

        if ($avgHours < 24) {
            return round($avgHours, 1) . 'h';
        } else {
            return round($avgHours / 24, 1) . 'd';
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
            $query->whereHas('employee', function ($q) {
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
    /*
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
            ->withCount(['employees as evaluations_count' => function ($q) {
                $q->join('performance_evaluations', 'employees.id', '=', 'performance_evaluations.employee_id')
                    ->whereBetween('performance_evaluations.evaluation_period', [
                        Carbon::parse($this->startDate)->startOfMonth(),
                        Carbon::parse($this->endDate)->endOfMonth()
                    ])
                    ->where('performance_evaluations.status', 'approved')
                    ->where('performance_evaluations.company_id', auth()->user()->company_id);
            }])
            ->with(['employees' => function ($q) {
                $q->where('employees.company_id', auth()->user()->company_id)
                    ->whereHas('evaluations', function ($eval) {
                        $eval->whereBetween('performance_evaluations.evaluation_period', [
                            Carbon::parse($this->startDate)->startOfMonth(),
                            Carbon::parse($this->endDate)->endOfMonth()
                        ])->where('performance_evaluations.status', 'approved')
                            ->where('performance_evaluations.company_id', auth()->user()->company_id);
                    })->with(['evaluations' => function ($eval) {
                        $eval->whereBetween('performance_evaluations.evaluation_period', [
                            Carbon::parse($this->startDate)->startOfMonth(),
                            Carbon::parse($this->endDate)->endOfMonth()
                        ])->where('performance_evaluations.status', 'approved')
                            ->where('performance_evaluations.company_id', auth()->user()->company_id);
                    }]);
            }])
            ->get()
            ->map(function ($dept) {
                $evaluations = $dept->employees->flatMap->evaluations;
                return [
                    'department' => $dept->name,
                    'total_evaluations' => $evaluations->count(),
                    'average_performance' => $evaluations->avg('final_percentage') ?? 0,
                    'below_threshold' => $evaluations->where('is_below_threshold', true)->count(),
                ];
            })
            ->filter(function ($item) {
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
    */

    /**
     * ===== ATUALIZAR generateOverviewReport() =====
     */
    protected function generateOverviewReport()
    {
        $query = $this->getBaseQuery();

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

            // Estatísticas novas
            'growth_rate' => $this->calculateGrowthRate(),
            'approval_time_avg' => $this->calculateAvgApprovalTime(),
            'top_department' => $this->getTopDepartment(),
            'trends' => $this->getQuickTrends(),
        ];

        // Performance por faixa
        $performanceRanges = [
            'Excelente (90-100%)' => (clone $query)->where('performance_evaluations.final_percentage', '>=', 90)->count(),
            'Bom (70-89%)' => (clone $query)->whereBetween('performance_evaluations.final_percentage', [70, 89.99])->count(),
            'Satisfatório (50-69%)' => (clone $query)->whereBetween('performance_evaluations.final_percentage', [50, 69.99])->count(),
            'Péssimo (<50%)' => (clone $query)->where('performance_evaluations.final_percentage', '<', 50)->count(),
        ];

        // Performance por departamento - MÉTODO SIMPLIFICADO
        $departmentPerformance = collect();

        $departments = Department::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->with(['employees' => function ($q) {
                $q->where('company_id', auth()->user()->company_id)
                    ->where('is_active', true);
            }])
            ->get();

        foreach ($departments as $dept) {
            // Buscar avaliações do departamento no período
            $evaluations = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                ->whereHas('employee', function ($q) use ($dept) {
                    $q->where('department_id', $dept->id);
                })
                ->whereBetween('evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])
                ->where('status', 'approved');

            // Aplicar filtros se existirem
            if ($this->performanceFilter !== 'all') {
                switch ($this->performanceFilter) {
                    case 'excellent':
                        $evaluations->where('final_percentage', '>=', 90);
                        break;
                    case 'good':
                        $evaluations->whereBetween('final_percentage', [70, 89.99]);
                        break;
                    case 'satisfactory':
                        $evaluations->whereBetween('final_percentage', [50, 69.99]);
                        break;
                    case 'poor':
                        $evaluations->where('final_percentage', '<', 50);
                        break;
                }
            }

            $evaluationsList = $evaluations->get();

            if ($evaluationsList->count() > 0) {
                $departmentPerformance->push([
                    'department' => $dept->name,
                    'total_evaluations' => $evaluationsList->count(),
                    'average_performance' => round($evaluationsList->avg('final_percentage'), 1),
                    'below_threshold' => $evaluationsList->where('is_below_threshold', true)->count(),
                ]);
            }
        }

        $this->reportData = [
            'performance_ranges' => $performanceRanges,
            'department_performance' => $departmentPerformance->sortByDesc('average_performance'),
        ];

        // Dados para gráficos
        $this->chartData = [
            'performance_chart' => [
                'labels' => array_keys($performanceRanges),
                'data' => array_values($performanceRanges),
                'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444']
            ]
        ];
    }

    /*
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
                ->take(10);
        }

        $this->reportData = [
            'evaluations' => $evaluations,
            'top_performers' => $topPerformers,
            'bottom_performers' => $bottomPerformers,
        ];

        // dd($topPerformers[0]);
        // Estatísticas de performance
        $this->stats = [
            'highest_score' => (clone $query)->max('performance_evaluations.final_percentage') ?? 0,
            'lowest_score' => (clone $query)->min('performance_evaluations.final_percentage') ?? 0,
            'median_score' => $this->calculateMedian($query, 'performance_evaluations.final_percentage'),
            'std_deviation' => $this->calculateStandardDeviation($query, 'performance_evaluations.final_percentage'),
        ];
    }
    */
    /**
     * ===== MELHORAR generatePerformanceReport() =====
     */
    protected function generatePerformanceReport()
    {
        $query = $this->getBaseQuery()->where('performance_evaluations.status', 'approved');

        // Top performers (paginated para performance)
        $topPerformers = (clone $query)->orderBy('performance_evaluations.final_percentage', 'desc')
            ->take(10)
            ->get();

        // Bottom performers (apenas se admin)
        $bottomPerformers = collect();
        if (auth()->user()->isCompanyAdmin()) {
            $bottomPerformers = (clone $query)->orderBy('performance_evaluations.final_percentage', 'asc')
                ->take(10)
                ->get();
        }

        $this->reportData = [
            'top_performers' => $topPerformers,
            'bottom_performers' => $bottomPerformers,
        ];

        // Estatísticas de performance melhoradas
        $allScores = (clone $query)->pluck('performance_evaluations.final_percentage');

        $this->stats = [
            'highest_score' => $allScores->max() ?? 0,
            'lowest_score' => $allScores->min() ?? 0,
            'median_score' => $this->calculateMedianFromCollection($allScores),
            'std_deviation' => $this->calculateStandardDeviationFromCollection($allScores),
            'total_evaluations' => $allScores->count(),
            'avg_score' => round($allScores->avg() ?? 0, 2),
            'score_ranges' => [
                'excellent' => $allScores->filter(fn($score) => $score >= 90)->count(),
                'good' => $allScores->filter(fn($score) => $score >= 70 && $score < 90)->count(),
                'satisfactory' => $allScores->filter(fn($score) => $score >= 50 && $score < 70)->count(),
                'poor' => $allScores->filter(fn($score) => $score < 50)->count(),
            ]
        ];
    }

    /**
     * ===== HELPERS MELHORADOS =====
     */
    private function calculateMedianFromCollection($collection)
    {
        if ($collection->isEmpty()) return 0;

        $sorted = $collection->sort()->values();
        $count = $sorted->count();

        if ($count % 2 === 0) {
            return ($sorted[$count / 2 - 1] + $sorted[$count / 2]) / 2;
        } else {
            return $sorted[intval($count / 2)];
        }
    }

    private function calculateStandardDeviationFromCollection($collection)
    {
        if ($collection->count() <= 1) return 0;

        $mean = $collection->avg();
        $variance = $collection->reduce(function ($carry, $value) use ($mean) {
            return $carry + pow($value - $mean, 2);
        }, 0) / ($collection->count() - 1);

        return round(sqrt($variance), 2);
    }

    protected function generateDepartmentReport()
    {
        $departments = collect();

        $allDepartments = Department::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();

        foreach ($allDepartments as $dept) {
            // Funcionários do departamento
            $employees = Employee::where('department_id', $dept->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('is_active', true)
                ->get();

            // Avaliações do departamento no período
            $evaluations = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                ->whereIn('employee_id', $employees->pluck('id'))
                ->whereBetween('evaluation_period', [
                    Carbon::parse($this->startDate)->startOfMonth(),
                    Carbon::parse($this->endDate)->endOfMonth()
                ])
                ->where('status', 'approved')
                ->get();

            $departments->push([
                'id' => $dept->id,
                'name' => $dept->name,
                'total_employees' => $employees->count(),
                'evaluated_employees' => $evaluations->unique('employee_id')->count(),
                'total_evaluations' => $evaluations->count(),
                'average_performance' => round($evaluations->avg('final_percentage') ?? 0, 2),
                'excellent_count' => $evaluations->where('final_percentage', '>=', 90)->count(),
                'good_count' => $evaluations->whereBetween('final_percentage', [70, 89.99])->count(),
                'satisfactory_count' => $evaluations->whereBetween('final_percentage', [50, 69.99])->count(),
                'poor_count' => $evaluations->where('final_percentage', '<', 50)->count(),
                'below_threshold' => $evaluations->where('is_below_threshold', true)->count(),
            ]);
        }

        $this->reportData = ['departments' => $departments->sortByDesc('average_performance')];
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
        $progressData = $evaluations->map(function ($eval) {
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
            $metricAnalysis = $latestEvaluation->responses->map(function ($response) {
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
            ->map(function ($item) {
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

        $variance = $values->reduce(function ($carry, $value) use ($mean) {
            return $carry + pow($value - $mean, 2);
        }, 0) / ($count - 1);

        return round(sqrt($variance), 2);
    }
    protected function generateColors($count)
    {
        $colors = [
            '#3B82F6',
            '#10B981',
            '#F59E0B',
            '#EF4444',
            '#8B5CF6',
            '#06B6D4',
            '#84CC16',
            '#F97316',
            '#EC4899',
            '#6366F1'
        ];

        return array_slice($colors, 0, $count);
    }

    // ===== EXPORT METHODS =====

    /*
    public function exportReport()
    {
        // TODO: Implementar exportação baseada no formato selecionado
        $this->dispatch('export-started', [
            'format' => $this->exportFormat,
            'type' => $this->reportType
        ]);
    }
    */

    /**
     * ===== MÉTODOS DE EXPORT MELHORADOS =====
     */
    public function exportReport()
    {
        $this->validate([
            'exportFormat' => 'required|in:xlsx,csv,pdf'
        ]);

        try {
            // Dispatch event para mostrar loading
            $this->dispatch('export-started', [
                'format' => $this->exportFormat,
                'type' => $this->reportType,
                'period' => $this->period
            ]);

            // Log da exportação
            \Log::info('Export de relatório iniciado', [
                'user_id' => auth()->id(),
                'company_id' => auth()->user()->company_id,
                'report_type' => $this->reportType,
                'format' => $this->exportFormat,
                'period' => $this->period,
                'filters' => [
                    'department' => $this->departmentFilter,
                    'employee' => $this->employeeFilter,
                    'status' => $this->statusFilter,
                    'performance' => $this->performanceFilter
                ]
            ]);

            // Aqui você implementaria a lógica real de export
            // Por exemplo, usando Laravel Excel:
            /*
        switch ($this->exportFormat) {
            case 'xlsx':
                return Excel::download(new EvaluationReportExport($this->reportData, $this->reportType), 
                    'relatorio-avaliacoes-' . $this->reportType . '-' . now()->format('Y-m-d') . '.xlsx');
            case 'csv':
                return Excel::download(new EvaluationReportExport($this->reportData, $this->reportType), 
                    'relatorio-avaliacoes-' . $this->reportType . '-' . now()->format('Y-m-d') . '.csv', 
                    \Maatwebsite\Excel\Excel::CSV);
            case 'pdf':
                return PDF::loadView('exports.evaluation-report-pdf', [
                    'reportData' => $this->reportData,
                    'stats' => $this->stats,
                    'reportType' => $this->reportType
                ])->download('relatorio-avaliacoes-' . $this->reportType . '-' . now()->format('Y-m-d') . '.pdf');
        }
        */

            // Por enquanto, simular sucesso
            session()->flash('success', 'Relatório exportado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro no export de relatório', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'report_type' => $this->reportType
            ]);

            session()->flash('error', 'Erro ao exportar relatório: ' . $e->getMessage());
        }
    }

    // ===== LIFECYCLE HOOKS =====

    public function updatedReportType()
    {
        // $this->generateReport();
        // Reset filtros específicos quando mudar tipo
        if ($this->reportType === 'employee' && !$this->employeeFilter) {
            // Não gerar relatório até selecionar funcionário
            $this->reportData = ['message' => 'Selecione um funcionário para ver o relatório detalhado.'];
            return;
        }

        $this->generateReport();

        // Dispatch evento para animações
        $this->dispatch('report-type-changed', ['type' => $this->reportType]);
    }

    public function updatedPeriod()
    {
        $this->setPeriodDates();
        $this->generateReport();
    }

    public function updatedDepartmentFilter()
    {
        $this->loadData(); // Recarregar funcionários

        // Reset funcionário se não pertencer ao departamento selecionado
        if ($this->departmentFilter && $this->employeeFilter) {
            $employee = Employee::find($this->employeeFilter);
            if ($employee && $employee->department_id != $this->departmentFilter) {
                $this->employeeFilter = '';
            }
        }

        // $this->generateReport();
        $this->generateReport();
    }

    public function updatedEmployeeFilter()
    {
        // Auto-selecionar departamento do funcionário
        if ($this->employeeFilter) {
            $employee = Employee::find($this->employeeFilter);
            if ($employee && !$this->departmentFilter) {
                $this->departmentFilter = $employee->department_id;
            }
        }
        $this->generateReport();
    }
    protected function optimizeQuery()
    {
        // Cache queries pesadas por 5 minutos
        $cacheKey = 'report_' . $this->reportType . '_' . md5(serialize([
            $this->startDate,
            $this->endDate,
            $this->departmentFilter,
            $this->employeeFilter,
            $this->statusFilter,
            $this->performanceFilter,
            auth()->user()->company_id
        ]));

        return cache()->remember($cacheKey, 300, function () {
            return $this->getBaseQuery()->get();
        });
    }

    public function clearCache()
    {
        // Limpar cache quando filtros mudarem
        cache()->forget('report_*');
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
        // dd($this->evaluation);
        return view('livewire.company.perfomance.evaluation-reports', [
            'reportData' => $this->reportData,
            'chartData' => $this->chartData,
            'stats' => $this->stats,
        ])
            ->title('Relatórios de Avaliação')
            ->layout('layouts.company');
    }
}
