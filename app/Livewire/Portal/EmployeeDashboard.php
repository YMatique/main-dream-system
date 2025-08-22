<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use App\Models\Company\RepairOrder\RepairOrderForm2Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class EmployeeDashboard extends Component
{
    public $employee;
    public $portalUser;
    public $recentEvaluations;
    public $stats;
    public $performanceChart;
    public $repairOrderStats;

    public function mount()
    {
        $this->portalUser = Auth::guard('portal')->user();
        $this->employee = $this->portalUser->employee;
        
        if (!$this->employee) {
            abort(403, 'Acesso negado. Funcionário não encontrado.');
        }
        
        $this->loadDashboardData();
        $this->loadRepairOrderStats();
    }

    public function loadDashboardData()
    {
        // Buscar avaliações aprovadas do funcionário
        $evaluations = PerformanceEvaluation::forEmployee($this->employee->id)
            ->byStatus('approved')
            ->orderBy('evaluation_period', 'desc');

        $this->recentEvaluations = $evaluations->take(6)->get();

        // Calcular estatísticas de performance
        $this->stats = [
            'total_evaluations' => $evaluations->count(),
            'current_year_evaluations' => $evaluations->forPeriod(now()->year)->count(),
            'average_performance' => $this->employee->getAveragePerformance(),
            'latest_evaluation' => $this->employee->getLatestEvaluation(),
            'has_below_threshold' => $this->employee->hasBelowThresholdEvaluations(),
            'performance_class' => $this->employee->getPerformanceClass(),
            'performance_percentage' => $this->employee->getPerformancePercentage(),
            'improvement_trend' => $this->calculateImprovementTrend(),
        ];

        // Dados para gráfico de performance
        $this->performanceChart = $this->prepareChartData();
    }

    public function loadRepairOrderStats()
    {
        $employeeId = $this->employee->id;

        // Contar total de ordens onde participou
        $totalOrders = RepairOrderForm2Employee::where('employee_id', $employeeId)->count();

        // Contar ordens deste ano
        $currentYearOrders = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->whereHas('form2', function($query) {
                $query->whereYear('carimbo', now()->year);
            })->count();

        // Contar ordens deste mês
        $currentMonthOrders = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->whereHas('form2', function($query) {
                $query->whereYear('carimbo', now()->year)
                      ->whereMonth('carimbo', now()->month);
            })->count();

        // Somar horas trabalhadas total
        $totalHours = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->sum('horas_trabalhadas');

        // Somar horas deste ano
        $currentYearHours = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->whereHas('form2', function($query) {
                $query->whereYear('carimbo', now()->year);
            })->sum('horas_trabalhadas');

        // Somar horas deste mês
        $currentMonthHours = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->whereHas('form2', function($query) {
                $query->whereYear('carimbo', now()->year)
                      ->whereMonth('carimbo', now()->month);
            })->sum('horas_trabalhadas');

        // Calcular horas faturadas
        $billedHours = $this->calculateBilledHours($employeeId);

        // Buscar ordens recentes
        $recentOrders = RepairOrderForm2Employee::where('employee_id', $employeeId)
            ->with(['form2.repairOrder', 'form2.location', 'form2.status'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            // dd($recentOrders[0]->form2);

        $this->repairOrderStats = [
            'total_orders' => $totalOrders,
            'current_year_orders' => $currentYearOrders,
            'current_month_orders' => $currentMonthOrders,
            'total_hours' => $totalHours,
            'current_year_hours' => $currentYearHours,
            'current_month_hours' => $currentMonthHours,
            'billed_hours' => $billedHours,
            'average_hours_per_order' => $totalOrders > 0 ? round($totalHours / $totalOrders, 2) : 0,
            'productivity_rate' => $totalHours > 0 ? round(($billedHours / $totalHours) * 100, 1) : 0,
            'recent_orders' => $recentOrders,
        ];
    }

    private function calculateBilledHours($employeeId)
    {
        // Tentar buscar horas faturadas via Form3
        $form2Hours = 0;
        
       $form2Hours = RepairOrderForm2Employee::where('employee_id', $employeeId)->sum('horas_trabalhadas')??0;

        return $form2Hours;
    }

    private function calculateImprovementTrend()
    {
        if ($this->recentEvaluations->count() < 2) {
            return ['trend' => 'stable', 'change' => 0];
        }

        $latest = $this->recentEvaluations->first()->final_percentage;
        $previous = $this->recentEvaluations->skip(1)->first()->final_percentage;
       
        $change = $latest - $previous;
       
        return [
            'trend' => $change > 0 ? 'improving' : ($change < 0 ? 'declining' : 'stable'),
            'change' => round($change, 1)
        ];
    }

    private function prepareChartData()
    {
        return $this->recentEvaluations->map(function ($evaluation) {
            return [
                'period' => $evaluation->evaluation_period_formatted,
                'percentage' => $evaluation->final_percentage,
                'class' => $evaluation->performance_class,
                'date' => $evaluation->evaluation_period->format('M Y')
            ];
        })->reverse()->values()->toArray();
    }
    public function render()
    {
        return view('livewire.portal.employee-dashboard')->layout('layouts.portal');
    }
}
