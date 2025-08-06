<?php
// app/Services/ActivityLoggerService.php

namespace App\Services;

use App\Models\System\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLoggerService
{
    public function __construct()
    {
        //
    }

    /**
     * Log de autenticação
     */
    public function logLogin($user): void
    {
        ActivityLog::logAuth(
            'login',
            "Usuário {$user->name} fez login no sistema",
            'info'
        );
    }

    public function logLogout($user): void
    {
        ActivityLog::logAuth(
            'logout',
            "Usuário {$user->name} fez logout do sistema",
            'info'
        );
    }

    public function logFailedLogin($email): void
    {
        ActivityLog::logAuth(
            'failed_login',
            "Tentativa de login falhada para o email: {$email}",
            'warning'
        );
    }

    /**
     * Log de modelos (CRUD)
     */
    public function logModelCreated(Model $model, string $description = null): void
    {
        $description = $description ?? "Novo(a) " . class_basename($model) . " criado(a)";
        
        ActivityLog::logModel(
            $model,
            'create',
            $description,
            null,
            $this->getModelAttributes($model)
        );
    }

    public function logModelUpdated(Model $model, array $oldValues, string $description = null): void
    {
        $description = $description ?? class_basename($model) . " actualizado(a)";
        
        ActivityLog::logModel(
            $model,
            'update',
            $description,
            $oldValues,
            $this->getModelAttributes($model)
        );
    }

    public function logModelDeleted(Model $model, string $description = null): void
    {
        $description = $description ?? class_basename($model) . " eliminado(a)";
        
        ActivityLog::logModel(
            $model,
            'delete',
            $description,
            $this->getModelAttributes($model),
            null
        );
    }

    /**
     * Logs específicos do sistema de reparações
     */
    public function logRepairOrderCreated($repairOrder): void
    {
        ActivityLog::logModel(
            $repairOrder,
            'create',
            "Nova ordem de reparação #{$repairOrder->repair_order_number} criada",
            null,
            $this->getModelAttributes($repairOrder)
        );
    }

    public function logRepairOrderStatusChanged($repairOrder, $oldStatus, $newStatus): void
    {
        ActivityLog::logModel(
            $repairOrder,
            'status_change',
            "Status da ordem #{$repairOrder->repair_order_number} alterado de '{$oldStatus}' para '{$newStatus}'",
            ['status' => $oldStatus],
            ['status' => $newStatus]
        );
    }

    public function logFormSubmitted($form, $formNumber): void
    {
        $modelName = "OrderForm{$formNumber}";
        
        ActivityLog::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id,
            'action' => 'form_submit',
            'model' => "App\\Models\\Company\\{$modelName}",
            'model_id' => $form->id,
            'description' => "Formulário {$formNumber} submetido para ordem #{$form->repair_order_number}",
            'category' => 'repair_order',
            'level' => 'info',
            'new_values' => $this->getModelAttributes($form),
            'metadata' => ['form_number' => $formNumber]
        ]);
    }

    /**
     * Logs de faturação
     */
    public function logBillingGenerated($billing, $type): void
    {
        ActivityLog::logModel(
            $billing,
            'generate',
            "Faturação {$type} gerada para ordem #{$billing->repair_order_number}",
            null,
            $this->getModelAttributes($billing)
        );
    }

    public function logBillingCurrencyChanged($billing, $oldCurrency, $newCurrency): void
    {
        ActivityLog::logModel(
            $billing,
            'currency_change',
            "Moeda da faturação alterada de {$oldCurrency} para {$newCurrency}",
            ['currency' => $oldCurrency],
            ['currency' => $newCurrency]
        );
    }

    /**
     * Logs de avaliação de desempenho
     */
    public function logPerformanceEvaluation($evaluation): void
    {
        ActivityLog::logModel(
            $evaluation,
            'evaluate',
            "Avaliação de desempenho criada para {$evaluation->employee->name}",
            null,
            $this->getModelAttributes($evaluation)
        );
    }

    public function logPerformanceApproval($evaluation, $stage): void
    {
        ActivityLog::logModel(
            $evaluation,
            'approve',
            "Avaliação aprovada no estágio {$stage} para {$evaluation->employee->name}",
            ['approval_stage' => $stage - 1],
            ['approval_stage' => $stage]
        );
    }

    /**
     * Logs de sistema
     */
    public function logDataExport($type, $filters = []): void
    {
        ActivityLog::logSystem(
            'export',
            "Dados exportados: {$type}",
            'info'
        );
    }

    public function logSystemError($error, $context = []): void
    {
        ActivityLog::logSystem(
            'error',
            "Erro do sistema: {$error}",
            'error'
        );
    }

    public function logPermissionDenied($permission, $resource = null): void
    {
        $description = "Acesso negado à permissão: {$permission}";
        if ($resource) {
            $description .= " (recurso: {$resource})";
        }

        ActivityLog::logSystem(
            'permission_denied',
            $description,
            'warning'
        );
    }

    /**
     * Logs de empresa/usuários
     */
    public function logCompanyCreated($company): void
    {
        ActivityLog::logModel(
            $company,
            'create',
            "Nova empresa '{$company->name}' registada no sistema"
        );
    }

    public function logUserInvited($user, $invitedBy): void
    {
        ActivityLog::logModel(
            $user,
            'invite',
            "Usuário {$user->name} convidado por {$invitedBy->name}"
        );
    }

    public function logUserActivated($user): void
    {
        ActivityLog::logModel(
            $user,
            'activate',
            "Usuário {$user->name} activado no sistema"
        );
    }

    public function logUserSuspended($user, $reason = null): void
    {
        $description = "Usuário {$user->name} suspenso";
        if ($reason) {
            $description .= " - Motivo: {$reason}";
        }

        ActivityLog::logModel(
            $user,
            'suspend',
            $description,
            null,
            null
        );
    }

    /**
     * Métodos auxiliares
     */
    private function getModelAttributes(Model $model): array
    {
        // Remover campos sensíveis dos logs
        $hidden = ['password', 'remember_token', 'api_token'];
        $attributes = $model->getAttributes();
        
        return collect($attributes)
            ->except($hidden)
            ->toArray();
    }

    /**
     * Log genérico para casos especiais
     */
    public function log(
        string $action,
        string $description,
        string $category = 'system',
        string $level = 'info',
        array $metadata = []
    ): void {
        ActivityLog::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id,
            'action' => $action,
            'description' => $description,
            'category' => $category,
            'level' => $level,
            'metadata' => $metadata
        ]);
    }
}