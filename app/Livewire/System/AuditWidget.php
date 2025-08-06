<?php

namespace App\Livewire\System;

use App\Models\System\AuditLog;
use Carbon\Carbon;
use Livewire\Component;

class AuditWidget extends Component
{
     public $recentActivities = [];
    public $todayStats = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Atividades recentes (últimas 10)
        $this->recentActivities = AuditLog::with(['user', 'company'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user_name' => $log->user?->name ?? 'Sistema',
                    'user_type' => $log->user?->user_type ?? 'system',
                    'action' => $log->action,
                    'action_label' => $this->getActionLabel($log->action),
                    'model' => class_basename($log->auditable_type),
                    'model_label' => $this->getModelLabel($log->auditable_type),
                    'company_name' => $log->company?->name ?? 'Sistema',
                    'created_at' => $log->created_at,
                    'time_ago' => $log->created_at->diffForHumans(),
                    'icon' => $this->getActionIcon($log->action),
                    'color' => $this->getActionColor($log->action),
                ];
            });

        // Estatísticas do dia
        $today = Carbon::today();
        $this->todayStats = [
            'total_actions' => AuditLog::whereDate('created_at', $today)->count(),
            'unique_users' => AuditLog::whereDate('created_at', $today)->distinct('user_id')->count(),
            'companies_active' => AuditLog::whereDate('created_at', $today)->distinct('company_id')->count(),
            'critical_actions' => AuditLog::whereDate('created_at', $today)
                ->whereIn('action', ['deleted', 'login_failed', 'account_locked'])
                ->count(),
        ];
    }

    private function getActionLabel($action)
    {
        return match($action) {
            'created' => 'Criado',
            'updated' => 'Atualizado',
            'deleted' => 'Excluído',
            'login' => 'Login',
            'logout' => 'Logout',
            'login_failed' => 'Login Falhado',
            'password_reset' => 'Senha Redefinida',
            'account_locked' => 'Conta Bloqueada',
            default => ucfirst($action)
        };
    }

    private function getModelLabel($model)
    {
        return match(class_basename($model)) {
            'User' => 'Usuário',
            'Company' => 'Empresa',
            'Plan' => 'Plano',
            'Subscription' => 'Subscrição',
            default => class_basename($model)
        };
    }

    private function getActionIcon($action)
    {
        return match($action) {
            'created' => 'plus-circle',
            'updated' => 'pencil',
            'deleted' => 'trash',
            'login' => 'login',
            'logout' => 'logout',
            'login_failed' => 'shield-exclamation',
            'password_reset' => 'key',
            'account_locked' => 'lock',
            default => 'activity'
        };
    }

    private function getActionColor($action)
    {
        return match($action) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'login' => 'emerald',
            'logout' => 'gray',
            'login_failed' => 'red',
            'password_reset' => 'yellow',
            'account_locked' => 'red',
            default => 'gray'
        };
    }

    public function render()
    {
        return view('livewire.system.audit-widget');
    }
}
