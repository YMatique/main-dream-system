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

     // Tab ativo
    public $activeTab = 'employee';
    
    // Filtros principais
    public $period = 'last_3_months';
    public $startDate = '';
    public $endDate = '';
    
    // Filtros avançados
    public $showAdvancedFilters = false;
    public $departmentFilter = '';
    public $employeeFilter = '';
    public $statusFilter = '';
    public $performanceFilter = 'all';
    
    // Dados para dropdowns
    public $departments = [];
    public $employees = [];
    
    // Dados do relatório
    public $reportData = [];
    public $stats = [];
    public $chartData = [];
    
    // Loading state
    public $isLoading = false;

    public function mount()
    {
        $this->loadFilterData();
        $this->loadReportData();
    }

    public function updatedActiveTab()
    {
        $this->loadReportData();
    }

    public function updatedPeriod()
    {
        if ($this->period === 'custom') {
            $this->startDate = now()->subMonth()->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
        }
        $this->loadReportData();
    }

    public function updatedStartDate()
    {
        if ($this->period === 'custom') {
            $this->loadReportData();
        }
    }

    public function updatedEndDate()
    {
        if ($this->period === 'custom') {
            $this->loadReportData();
        }
    }

    public function updatedDepartmentFilter()
    {
        $this->loadEmployees();
        $this->loadReportData();
    }

    public function updatedEmployeeFilter()
    {
        $this->loadReportData();
    }

    public function updatedStatusFilter()
    {
        $this->loadReportData();
    }

    public function updatedPerformanceFilter()
    {
        $this->loadReportData();
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    private function loadFilterData()
    {
        $this->departments = Department::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get();
            
        $this->loadEmployees();
    }

    private function loadEmployees()
    {
        $query = Employee::where('company_id', auth()->user()->company_id);
        
        if ($this->departmentFilter) {
            $query->where('department_id', $this->departmentFilter);
        }
        
        $this->employees = $query->orderBy('name')->get();
    }

    private function loadReportData()
    {
        $this->isLoading = true;
        
        $dateRange = $this->getDateRange();
        
        switch ($this->activeTab) {
            case 'overview':
                $this->loadOverviewData($dateRange);
                break;
            case 'performance':
                $this->loadPerformanceData($dateRange);
                break;
            case 'department':
                $this->loadDepartmentData($dateRange);
                break;
            case 'employee':
                $this->loadEmployeeData($dateRange);
                break;
            case 'trends':
                $this->loadTrendsData($dateRange);
                break;
        }
        
        $this->isLoading = false;
    }

    private function getDateRange()
    {
        switch ($this->period) {
            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
            case 'last_3_months':
                return [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()];
            case 'last_6_months':
                return [now()->subMonths(6)->startOfMonth(), now()->endOfMonth()];
            case 'last_year':
                return [now()->subYear()->startOfYear(), now()->endOfYear()];
            case 'custom':
                return [
                    $this->startDate ? Carbon::parse($this->startDate) : now()->subMonth(),
                    $this->endDate ? Carbon::parse($this->endDate) : now()
                ];
            default:
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
        }
    }

    private function getBaseQuery($dateRange)
    {
        $query = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->whereBetween('evaluation_period', $dateRange)
            ->with(['employee.department', 'evaluator']);

        if ($this->departmentFilter) {
            $query->whereHas('employee', function ($q) {
                $q->where('department_id', $this->departmentFilter);
            });
        }

        if ($this->employeeFilter) {
            $query->where('employee_id', $this->employeeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->performanceFilter !== 'all') {
            switch ($this->performanceFilter) {
                case 'excellent':
                    $query->where('final_percentage', '>=', 90);
                    break;
                case 'good':
                    $query->whereBetween('final_percentage', [70, 89]);
                    break;
                case 'satisfactory':
                    $query->whereBetween('final_percentage', [50, 69]);
                    break;
                case 'poor':
                    $query->where('final_percentage', '<', 50);
                    break;
            }
        }

        return $query;
    }

    private function loadOverviewData($dateRange)
    {
        $evaluations = $this->getBaseQuery($dateRange)->get();
        
        $this->stats = [
            'total_evaluations' => $evaluations->count(),
            'approved_evaluations' => $evaluations->where('status', 'approved')->count(),
            'pending_evaluations' => $evaluations->where('status', 'submitted')->count(),
            'average_performance' => $evaluations->avg('final_percentage') ? round($evaluations->avg('final_percentage'), 1) : 0,
            'below_threshold' => $evaluations->where('final_percentage', '<', 50)->count(),
        ];

        // Dados do gráfico de distribuição de performance
        $this->chartData['performance_distribution'] = [
            'labels' => ['Excelente (≥90%)', 'Bom (70-89%)', 'Satisfatório (50-69%)', 'Péssimo (<50%)'],
            'data' => [
                $evaluations->where('final_percentage', '>=', 90)->count(),
                $evaluations->whereBetween('final_percentage', [70, 89])->count(),
                $evaluations->whereBetween('final_percentage', [50, 69])->count(),
                $evaluations->where('final_percentage', '<', 50)->count(),
            ],
            'colors' => ['#10B981', '#3B82F6', '#F59E0B', '#EF4444']
        ];

        // Performance por departamento
        $this->reportData['department_performance'] = $this->departments->map(function ($dept) use ($evaluations) {
            $deptEvaluations = $evaluations->filter(function ($eval) use ($dept) {
                return $eval->employee->department_id === $dept->id;
            });

            return [
                'department' => $dept->name,
                'total_evaluations' => $deptEvaluations->count(),
                'average_performance' => $deptEvaluations->count() > 0 ? round($deptEvaluations->avg('final_percentage'), 1) : 0,
                'below_threshold' => $deptEvaluations->where('final_percentage', '<', 50)->count(),
            ];
        })->filter(function ($dept) {
            return $dept['total_evaluations'] > 0;
        });
    }

    private function loadPerformanceData($dateRange)
    {
        $evaluations = $this->getBaseQuery($dateRange)->get();
        
        $this->stats = [
            'highest_score' => $evaluations->max('final_percentage') ?: 0,
            'lowest_score' => $evaluations->min('final_percentage') ?: 0,
            'median_score' => $this->calculateMedian($evaluations->pluck('final_percentage')->toArray()),
            'std_deviation' => $this->calculateStandardDeviation($evaluations->pluck('final_percentage')->toArray()),
        ];

        // Top 10 performers
        $this->reportData['top_performers'] = $evaluations
            ->sortByDesc('final_percentage')
            ->take(10);
    }

    private function loadDepartmentData($dateRange)
    {
        $evaluations = $this->getBaseQuery($dateRange)->get();
        
        $this->reportData['departments'] = $this->departments->map(function ($dept) use ($evaluations) {
            $deptEvaluations = $evaluations->filter(function ($eval) use ($dept) {
                return $eval->employee->department_id === $dept->id;
            });

            $totalEmployees = Employee::where('department_id', $dept->id)
                ->where('company_id', auth()->user()->company_id)
                ->count();

            return [
                'name' => $dept->name,
                'total_employees' => $totalEmployees,
                'evaluated_employees' => $deptEvaluations->pluck('employee_id')->unique()->count(),
                'total_evaluations' => $deptEvaluations->count(),
                'average_performance' => $deptEvaluations->count() > 0 ? round($deptEvaluations->avg('final_percentage'), 1) : 0,
                'excellent_count' => $deptEvaluations->where('final_percentage', '>=', 90)->count(),
                'good_count' => $deptEvaluations->whereBetween('final_percentage', [70, 89])->count(),
                'satisfactory_count' => $deptEvaluations->whereBetween('final_percentage', [50, 69])->count(),
                'poor_count' => $deptEvaluations->where('final_percentage', '<', 50)->count(),
            ];
        })->filter(function ($dept) {
            return $dept['total_evaluations'] > 0;
        });
    }

    private function loadEmployeeData($dateRange)
    {
        if (!$this->employeeFilter) {
            $this->reportData['message'] = 'Selecione um funcionário nos filtros para ver seu relatório detalhado.';
            return;
        }

        $employee = Employee::find($this->employeeFilter);
        $evaluations = $this->getBaseQuery($dateRange)
            ->where('employee_id', $this->employeeFilter)
            ->orderBy('evaluation_period', 'desc')
            ->get();

        $this->reportData['employee'] = $employee;
        $this->reportData['evaluations'] = $evaluations;
        $this->reportData['total_evaluations'] = $evaluations->count();
        $this->reportData['average_performance'] = $evaluations->count() > 0 ? round($evaluations->avg('final_percentage'), 1) : 0;

        // Dados para gráfico de evolução
        if ($evaluations->count() > 1) {
            $this->chartData['progress_chart'] = [
                'labels' => $evaluations->pluck('evaluation_period')->map(function ($date) {
                    return Carbon::parse($date)->format('m/Y');
                })->reverse()->toArray(),
                'data' => $evaluations->pluck('final_percentage')->reverse()->toArray(),
            ];
        }
    }

    private function loadTrendsData($dateRange)
    {
        $evaluations = $this->getBaseQuery($dateRange)->get();
        
        $monthlyData = $evaluations->groupBy(function ($evaluation) {
            return Carbon::parse($evaluation->evaluation_period)->format('Y-m');
        })->map(function ($monthEvaluations, $period) {
            $total = $monthEvaluations->count();
            $belowThreshold = $monthEvaluations->where('final_percentage', '<', 50)->count();
            
            return [
                'period' => Carbon::createFromFormat('Y-m', $period)->format('m/Y'),
                'total_evaluations' => $total,
                'avg_performance' => $total > 0 ? round($monthEvaluations->avg('final_percentage'), 1) : 0,
                'below_threshold_count' => $belowThreshold,
                'below_threshold_percentage' => $total > 0 ? round(($belowThreshold / $total) * 100, 1) : 0,
            ];
        })->sortBy('period');

        $this->reportData['monthly_trends'] = $monthlyData;

        // Dados para gráfico de tendências
        $this->chartData['trends_chart'] = [
            'labels' => $monthlyData->pluck('period')->toArray(),
            'datasets' => [
                [
                    'label' => 'Performance Média (%)',
                    'data' => $monthlyData->pluck('avg_performance')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Total de Avaliações',
                    'data' => $monthlyData->pluck('total_evaluations')->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'yAxisID' => 'y1',
                ]
            ]
        ];
    }

    private function calculateMedian($values)
    {
        if (empty($values)) return 0;
        
        sort($values);
        $count = count($values);
        $middle = floor($count / 2);
        
        if ($count % 2 === 0) {
            return round(($values[$middle - 1] + $values[$middle]) / 2, 1);
        }
        
        return round($values[$middle], 1);
    }

    private function calculateStandardDeviation($values)
    {
        if (empty($values)) return 0;
        
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);
        
        return round(sqrt($variance), 1);
    }

    public function exportReport($format)
    {
        $this->dispatch('export-started');
        
        // TODO: Implementar exportação real
        session()->flash('success', "Exportação em formato {$format} será implementada em breve.");
    }

    public function filterQuick($type)
    {
        switch ($type) {
            case 'approved':
                $this->statusFilter = 'approved';
                break;
            case 'submitted':
                $this->statusFilter = 'submitted';
                break;
            case 'performance':
                $this->performanceFilter = 'poor';
                break;
            default:
                $this->statusFilter = '';
                $this->performanceFilter = 'all';
        }
        
        $this->loadReportData();
    }

    public function filterByDepartment($departmentName)
    {
        $department = $this->departments->where('name', $departmentName)->first();
        if ($department) {
            $this->departmentFilter = $department->id;
            $this->loadEmployees();
            $this->loadReportData();
        }
    }

    public function render()
    {
        // dd($this->evaluation);
        return view('livewire.company.perfomance.evaluation-reports')
            ->title('Relatórios de Avaliação')
            ->layout('layouts.company');
    }
}
