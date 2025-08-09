<?php

namespace App\Models\Company\Evaluation;

use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationApprovalStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'stage_number',
        'stage_name',
        'description',
        'approver_roles',
        'approver_departments',
        'is_required',
        'is_active'
    ];

    protected $casts = [
        'stage_number' => 'integer',
        'approver_roles' => 'array',
        'approver_departments' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('stage_number');
    }
}
