<?php

namespace App\Models\Company;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use App\Models\Company\Evaluation\PerformanceMetric;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['company_id', 'name', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
     /**
     * ✅ NOVA RELAÇÃO - Avaliações através dos funcionários
     */
    public function evaluations()
    {
        return $this->hasManyThrough(
            PerformanceEvaluation::class,  // Modelo final
            Employee::class,               // Modelo intermediário
            'department_id',               // Foreign key na tabela employees (employees.department_id)
            'employee_id',                 // Foreign key na tabela performance_evaluations (evaluations.employee_id)
            'id',                         // Local key na tabela departments (departments.id)
            'id'                          // Local key na tabela employees (employees.id)
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function performanceMetrics()
    {
        return $this->hasMany(PerformanceMetric::class);
    }
    public function approvalStages()
    {
        return $this->hasMany(\App\Models\Company\Evaluation\EvaluationApprovalStage::class, 'target_department_id');
    }
}
