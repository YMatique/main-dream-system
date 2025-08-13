<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeeDashboard extends Component
{
    public $employee;
     public $portalUser;
    public $recentEvaluations;
    public $stats;
    public $performanceChart;

    public function mount()
    {
        $this->portalUser = Auth::guard('employee_portal')->user();
        $this->employee = $this->portalUser->employee;

        if (!$this->employee) {
            abort(403, 'Acesso negado. Funcionário não encontrado.');
        }

        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Buscar avaliações aprovadas do funcionário
        $evaluations = PerformanceEvaluation::forEmployee($this->employee->id)
            ->byStatus('approved')
            ->orderBy('evaluation_period', 'desc');

        $this->recentEvaluations = $evaluations->take(6)->get();

        // Calcular estatísticas
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
