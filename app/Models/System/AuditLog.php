<?php

namespace App\Models\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'auditable_type',
        'auditable_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'company_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Get the company related to this audit
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope para filtrar por ação
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por empresa
     */
    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get action label for display
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'Criado',
            'updated' => 'Atualizado', 
            'deleted' => 'Excluído',
            'login' => 'Login',
            'logout' => 'Logout',
            'password_reset' => 'Senha Redefinida',
            'status_changed' => 'Status Alterado',
            'permission_changed' => 'Permissões Alteradas',
            default => ucfirst($this->action)
        };
    }

    /**
     * Get model name for display
     */
    public function getModelNameAttribute(): string
    {
        $modelClass = $this->auditable_type;
        $modelName = class_basename($modelClass);
        
        return match($modelName) {
            'User' => 'Usuário',
            'Company' => 'Empresa',
            'Subscription' => 'Subscrição',
            'Plan' => 'Plano',
            'Employee' => 'Funcionário',
            'RepairOrder' => 'Ordem de Reparação',
            default => $modelName
        };
    }

    /**
     * Get changes summary
     */
    public function getChangesSummary(): array
    {
        $changes = [];
        
        if ($this->action === 'updated' && $this->old_values && $this->new_values) {
            foreach ($this->new_values as $field => $newValue) {
                $oldValue = $this->old_values[$field] ?? null;
                
                if ($oldValue !== $newValue) {
                    $changes[] = [
                        'field' => $this->getFieldLabel($field),
                        'old' => $this->formatValue($oldValue),
                        'new' => $this->formatValue($newValue)
                    ];
                }
            }
        }
        
        return $changes;
    }

    /**
     * Format field label for display
     */
    private function getFieldLabel(string $field): string
    {
        return match($field) {
            'name' => 'Nome',
            'email' => 'Email',
            'phone' => 'Telefone',
            'status' => 'Status',
            'user_type' => 'Tipo de Usuário',
            'company_id' => 'Empresa',
            'permissions' => 'Permissões',
            'last_login_at' => 'Último Login',
            default => ucfirst(str_replace('_', ' ', $field))
        };
    }

    /**
     * Format value for display
     */
    private function formatValue($value): string
    {
        if (is_null($value)) {
            return '(vazio)';
        }
        
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }
        
        if (is_array($value)) {
            return implode(', ', $value);
        }
        
        if ($value instanceof \Carbon\Carbon) {
            return $value->format('d/m/Y H:i');
        }
        
        return (string) $value;
    }
}
