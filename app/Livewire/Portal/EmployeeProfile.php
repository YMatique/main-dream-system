<?php

namespace App\Livewire\Portal;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeeProfile extends Component
{
    public $employee;
    public $workStats;
    public $evaluationStats;
    public $portalUser;
    public function mount()
    {
       $this->portalUser = Auth::guard('employee_portal')->user();
        $this->employee = $this->portalUser->employee;

        if (!$this->employee) {
            abort(403, 'Funcionário não encontrado.');
        }

        $this->loadProfileData();
    }

    public function loadProfileData()
    {
        // Estatísticas de trabalho (ordens de reparação)
        $this->workStats = [
            'total_hours_worked' => $this->employee->getTotalHoursWorked(),
            'total_repair_orders' => $this->employee->getRepairOrdersCount(),
            'hours_this_year' => $this->employee->getTotalHoursWorked(now()->startOfYear(), now()->endOfYear()),
            'orders_this_year' => $this->employee->getRepairOrdersCount(now()->startOfYear(), now()->endOfYear()),
        ];

        // Estatísticas de avaliação
        $this->evaluationStats = [
            'total_evaluations' => $this->employee->getTotalEvaluationsCount(),
            'average_performance' => $this->employee->getAveragePerformance(),
            'current_performance_class' => $this->employee->getPerformanceClass(),
            'has_below_threshold' => $this->employee->hasBelowThresholdEvaluations(),
            'latest_evaluation' => $this->employee->getLatestEvaluation(),
        ];
    }

    public function render()
    {
        return view('livewire.portal.employee-profile')->layout('layouts.portal');
    }
}
