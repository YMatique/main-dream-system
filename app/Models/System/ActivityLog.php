<?php

namespace App\Models\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'action', 'model', 'model_id', 'description',
        'old_values', 'new_values', 'metadata', 'ip_address', 'user_agent',
        'route', 'method', 'level', 'category'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Scopes para filtros
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Métodos estáticos para criar logs
    public static function logActivity(array $data): self
    {
        return self::create(array_merge($data, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'route' => request()->route()?->getName(),
            'method' => request()->method(),
        ]));
    }

    // Métodos de conveniência para diferentes tipos de log
    public static function logAuth($action, $description, $level = 'info'): self
    {
        return self::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id,
            'action' => $action,
            'description' => $description,
            'category' => 'auth',
            'level' => $level
        ]);
    }

    public static function logSystem($action, $description, $level = 'info'): self
    {
        return self::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id,
            'action' => $action,
            'description' => $description,
            'category' => 'system',
            'level' => $level
        ]);
    }

    public static function logModel($model, $action, $description, $oldValues = null, $newValues = null): self
    {
        return self::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id,
            'action' => $action,
            'model' => get_class($model),
            'model_id' => $model->id ?? null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'category' => self::getCategoryFromModel(get_class($model))
        ]);
    }

    // Mapear modelos para categorias
    private static function getCategoryFromModel($modelClass): string
    {
        $mapping = [
            'App\Models\User' => 'user',
            'App\Models\System\Company' => 'company',
            'App\Models\Company\RepairOrder' => 'repair_order',
            'App\Models\Company\OrderForm1' => 'repair_order',
            'App\Models\Company\OrderForm2' => 'repair_order',
            'App\Models\Company\OrderForm3' => 'repair_order',
            'App\Models\Company\OrderForm4' => 'repair_order',
            'App\Models\Company\OrderForm5' => 'repair_order',
            'App\Models\Company\Billing' => 'billing',
            'App\Models\Company\Employee' => 'employee',
            'App\Models\Company\Client' => 'client',
            'App\Models\Company\Material' => 'material',
            'App\Models\Company\PerformanceEvaluation' => 'performance',
        ];

        return $mapping[$modelClass] ?? 'system';
    }

    // Getters para interface
    public function getUserNameAttribute(): string
    {
        return $this->user?->name ?? 'Sistema';
    }

    public function getCompanyNameAttribute(): string
    {
        return $this->company?->name ?? 'Sistema';
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getLevelColorAttribute(): string
    {
        return match($this->level) {
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'critical' => 'purple',
            default => 'gray'
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'auth' => 'key',
            'system' => 'cog',
            'company' => 'building',
            'user' => 'user',
            'repair_order' => 'wrench',
            'billing' => 'currency-dollar',
            'employee' => 'users',
            'client' => 'user-group',
            'material' => 'cube',
            'performance' => 'chart-bar',
            default => 'information-circle'
        };
    }
}
