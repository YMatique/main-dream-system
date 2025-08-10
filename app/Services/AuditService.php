<?php

namespace App\Services;

use App\Models\System\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuditService
{
    
    public function logSystemAction(string $action, array $data = [], ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? auth()->id(),
            'auditable_type' => 'System',
            'auditable_id' => 0,
            'action' => $action,
            'old_values' => null,
            'new_values' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'company_id' => auth()->user()?->company_id,
        ]);
    }

    /**
     * Log user login
     */
    public function logLogin(User $user): void
    {
        AuditLog::create([
            'user_id' => $user->id,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'login',
            'old_values' => null,
            'new_values' => [
                'login_time' => now()->toISOString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'company_id' => $user->company_id,
        ]);

        // Atualizar last_login_at do usuário
        $user->update(['last_login_at' => now()]);
    }

    public function logFailedLogin()
    {
        AuditLog::create([
            'user_id' => null,
            'auditable_type' => 'System',
            'auditable_id' => 0,
            'action' => 'failed_login',
            'old_values' => null,
            'new_values' => [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'company_id' => null,
        ]);
    }

    /**
     * Log user logout
     */
    public function logLogout(User $user): void
    {
        AuditLog::create([
            'user_id' => $user->id,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => 'logout',
            'old_values' => null,
            'new_values' => [
                'logout_time' => now()->toISOString(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'company_id' => $user->company_id,
        ]);
    }

    /**
     * Get audit statistics
     */
    public function getStatistics(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_actions' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'unique_users' => AuditLog::where('created_at', '>=', $startDate)
                ->distinct('user_id')->count('user_id'),
            'by_action' => AuditLog::where('created_at', '>=', $startDate)
                ->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
            'by_model' => AuditLog::where('created_at', '>=', $startDate)
                ->select('auditable_type', DB::raw('count(*) as count'))
                ->groupBy('auditable_type')
                ->pluck('count', 'auditable_type')
                ->toArray(),
            'recent_activities' => AuditLog::with(['user'])
                ->where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Get user activity report
     */
    public function getUserActivity(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $activities = AuditLog::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total_actions' => $activities->count(),
            'actions_by_type' => $activities->groupBy('action')
                ->map->count()
                ->toArray(),
            'models_affected' => $activities->groupBy('auditable_type')
                ->map->count()
                ->toArray(),
            'daily_activity' => $activities->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })->map->count()->toArray(),
            'recent_activities' => $activities->take(20)
        ];
    }

    /**
     * Get company audit trail
     */
    public function getCompanyAuditTrail(int $companyId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $activities = AuditLog::with(['user'])
            ->where('company_id', $companyId)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total_actions' => $activities->count(),
            'unique_users' => $activities->unique('user_id')->count(),
            'by_action' => $activities->groupBy('action')->map->count()->toArray(),
            'by_user' => $activities->groupBy('user.name')->map->count()->toArray(),
            'timeline' => $activities->take(50)
        ];
    }

    /**
     * Clean old audit logs
     */
    public function cleanupOldLogs(int $daysToKeep = 365): int
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        return AuditLog::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Export audit logs to CSV
     */
    public function exportAuditLogs(array $filters = []): string
    {
        $query = AuditLog::with(['user']);
        
        // Apply filters
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        
        if (isset($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        // Generate CSV content
        $csvContent = "Data,Usuário,Ação,Modelo,ID,IP,Detalhes\n";
        
        foreach ($logs as $log) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $log->created_at->format('d/m/Y H:i:s'),
                $log->user ? $log->user->name : 'Sistema',
                $log->action_label,
                $log->model_name,
                $log->auditable_id,
                $log->ip_address,
                json_encode($log->new_values)
            );
        }
        
        // Save to temporary file
        $filename = 'audit_log_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);
        
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        file_put_contents($filepath, $csvContent);
        
        return $filepath;
    }
}
