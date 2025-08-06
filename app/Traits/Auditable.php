<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\System\AuditLog;

trait Auditable
{
      public static function bootAuditable()
    {
        // Auditar criação
        static::created(function ($model) {
            $model->auditAction('created', null, $model->toArray());
        });

        // Auditar atualização
        static::updated(function ($model) {
            $original = $model->getOriginal();
            $changes = $model->getChanges();
            
            // Remover timestamps dos changes se não são relevantes
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                $model->auditAction('updated', $original, $changes);
            }
        });

        // Auditar exclusão
        static::deleted(function ($model) {
            $model->auditAction('deleted', $model->toArray(), null);
        });
    }

    /**
     * Criar entrada de auditoria
     */
    protected function auditAction(string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        // Campos sensíveis que devem ser mascarados
        $sensitiveFields = ['password', 'password_confirmation', 'remember_token'];
        
        // Mascarar campos sensíveis
        if ($oldValues) {
            foreach ($sensitiveFields as $field) {
                if (isset($oldValues[$field])) {
                    $oldValues[$field] = '***MASKED***';
                }
            }
        }
        
        if ($newValues) {
            foreach ($sensitiveFields as $field) {
                if (isset($newValues[$field])) {
                    $newValues[$field] = '***MASKED***';
                }
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'company_id' => $this->company_id ?? (Auth::user()?->company_id),
        ]);
    }

    /**
     * Obter logs de auditoria para este modelo
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Auditar ação customizada
     */
    public function auditCustomAction(string $action, array $data = [])
    {
        $this->auditAction($action, null, $data);
    }
}
