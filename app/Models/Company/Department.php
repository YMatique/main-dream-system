<?php

namespace App\Models\Company;

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
