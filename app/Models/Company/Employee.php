<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'company_id', 'department_id', 'name', 'code', 
        'email', 'phone', 'is_active'
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

     /**
     * Avaliações de desempenho do funcionário
     */
    public function evaluations()
    {
        return $this->hasMany(\App\Models\Company\Evaluation\PerformanceEvaluation::class);
    }

    /**
     * Ordens de reparação onde o funcionário trabalhou (Form 2)
     */
    public function repairOrderForm2Employees()
    {
        return $this->hasMany(\App\Models\Company\RepairOrder\RepairOrderForm2Employee::class);
    }

    /**
     * Ordens de reparação onde o funcionário é técnico responsável (Form 5)
     */
    // public function repairOrderForm5()
    // {
    //     return $this->hasMany(\App\Models\Company\RepairOrder\RepairOrderForm5::class, 'tecnico_id');
    // }
    // ===== PERFORMANCE EVALUATION METHODS =====

    /**
     * Get latest evaluation
     */
    public function getLatestEvaluation()
    {
        return $this->evaluations()
            ->where('status', 'approved')
            ->orderBy('evaluation_period', 'desc')
            ->first();
    }

    /**
     * Get evaluations for a specific period
     */
    public function getEvaluationsForPeriod($startDate, $endDate)
    {
        return $this->evaluations()
            ->whereBetween('evaluation_period', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('evaluation_period', 'desc')
            ->get();
    }

    /**
     * Get average performance for a period
     */
    public function getAveragePerformance($startDate = null, $endDate = null): float
    {
        $query = $this->evaluations()->where('status', 'approved');
        
        if ($startDate && $endDate) {
            $query->whereBetween('evaluation_period', [$startDate, $endDate]);
        }
        
        return round($query->avg('final_percentage') ?? 0, 2);
    }

    /**
     * Check if employee has evaluations below threshold
     */
    public function hasBelowThresholdEvaluations($startDate = null, $endDate = null): bool
    {
        $query = $this->evaluations()
            ->where('status', 'approved')
            ->where('is_below_threshold', true);
        
        if ($startDate && $endDate) {
            $query->whereBetween('evaluation_period', [$startDate, $endDate]);
        }
        
        return $query->exists();
    }

    /**
     * Get total evaluations count
     */
    public function getTotalEvaluationsCount(): int
    {
        return $this->evaluations()
            ->where('status', 'approved')
            ->count();
    }

    // ===== REPAIR ORDER METHODS =====

    /**
     * Get total hours worked in repair orders
     */
    public function getTotalHoursWorked($startDate = null, $endDate = null): float
    {
        $query = $this->repairOrderForm2Employees();
        
        if ($startDate && $endDate) {
            $query->whereHas('form2', function($q) use ($startDate, $endDate) {
                $q->whereBetween('carimbo', [$startDate, $endDate]);
            });
        }
        
        return round($query->sum('horas_trabalhadas') ?? 0, 2);
    }

    /**
     * Get repair orders count where employee worked
     */
    public function getRepairOrdersCount($startDate = null, $endDate = null): int
    {
        $query = $this->repairOrderForm2Employees();
        
        if ($startDate && $endDate) {
            $query->whereHas('form2', function($q) use ($startDate, $endDate) {
                $q->whereBetween('carimbo', [$startDate, $endDate]);
            });
        }
        
        return $query->distinct('form2_id')->count();
    }

    // ===== VALIDATION METHODS =====

    /**
     * Check if employee can be evaluated by a specific user
     */
    public function canBeEvaluatedBy($user): bool
    {
        // Admin da empresa pode avaliar qualquer funcionário
        if ($user->isCompanyAdmin()) {
            return true;
        }
        
        // Usuário com permissão para o departamento específico
        if ($user->hasPermission("evaluation.department.{$this->department_id}")) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if employee belongs to user's company
     */
    public function belongsToUserCompany($user): bool
    {
        return $this->company_id === $user->company_id;
    }

    // ===== HELPER METHODS =====

    /**
     * Get employee status badge
     */
    public function getStatusBadge(): string
    {
        return $this->is_active 
            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>'
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>';
    }

    /**
     * Get performance class based on latest evaluation
     */
    public function getPerformanceClass(): string
    {
        $latestEvaluation = $this->getLatestEvaluation();
        
        if (!$latestEvaluation) {
            return 'Não Avaliado';
        }
        
        return $latestEvaluation->performance_class;
    }

    /**
     * Get performance percentage based on latest evaluation
     */
    public function getPerformancePercentage(): float
    {
        $latestEvaluation = $this->getLatestEvaluation();
        
        return $latestEvaluation ? $latestEvaluation->final_percentage : 0;
    }
}
