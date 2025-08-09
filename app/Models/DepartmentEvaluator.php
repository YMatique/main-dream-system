<?php

namespace App\Models;

use App\Models\Company\Department;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentEvaluator extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'company_id',
        'is_active',
        'assigned_at',
        'assigned_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public static function userCanEvaluateDepartment(int $userId, int $departmentId): bool
    {
        return static::where('user_id', $userId)
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->exists();
    }

    public static function getUserDepartments(int $userId): array
    {
        return static::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('department_id')
            ->toArray();
    }
}
