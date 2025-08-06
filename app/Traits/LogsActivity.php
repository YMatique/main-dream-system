<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Services\ActivityLoggerService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Log quando modelo é criado
        static::created(function (Model $model) {
            $logger = app(ActivityLoggerService::class);
            $logger->logModelCreated($model, static::getCreatedMessage($model));
        });

        // Log quando modelo é actualizado
        static::updated(function (Model $model) {
            $logger = app(ActivityLoggerService::class);
            $oldValues = $model->getOriginal();
            $changes = $model->getChanges();
            
            // Remover campos que não devem ser logados
            $hiddenFields = $model->getHiddenForLogs();
            $oldValues = collect($oldValues)->except($hiddenFields)->toArray();
            $changes = collect($changes)->except($hiddenFields)->toArray();
            
            if (!empty($changes)) {
                $logger->logModelUpdated($model, $oldValues, static::getUpdatedMessage($model));
            }
        });

        // Log quando modelo é eliminado
        static::deleted(function (Model $model) {
            $logger = app(ActivityLoggerService::class);
            $logger->logModelDeleted($model, static::getDeletedMessage($model));
        });
    }

    /**
     * Campos que devem ser ocultados dos logs
     */
    public function getHiddenForLogs(): array
    {
        return array_merge([
            'password',
            'remember_token',
            'api_token',
            'updated_at',
            'created_at'
        ], $this->hiddenForLogs ?? []);
    }

    /**
     * Mensagem personalizada para criação
     */
    protected static function getCreatedMessage(Model $model): string
    {
        $className = class_basename($model);
        
        // Mensagens personalizadas por modelo
        $messages = [
            'Company' => "Nova empresa '{$model->name}' registada",
            'User' => "Novo usuário '{$model->name}' criado",
            'Employee' => "Novo funcionário '{$model->name}' adicionado",
            'Client' => "Novo cliente '{$model->name}' registado",
            'Material' => "Novo material '{$model->name}' cadastrado",
            'RepairOrder' => "Nova ordem de reparação #{$model->repair_order_number} criada",
            'OrderForm1' => "Formulário 1 submetido para ordem #{$model->repair_order_number}",
            'OrderForm2' => "Formulário 2 submetido para ordem #{$model->repair_order_number}",
            'OrderForm3' => "Formulário 3 submetido para ordem #{$model->repair_order_number}",
            'OrderForm4' => "Formulário 4 submetido para ordem #{$model->repair_order_number}",
            'OrderForm5' => "Formulário 5 submetido para ordem #{$model->repair_order_number}",
            'Billing' => "Faturação gerada para ordem #{$model->repair_order_number}",
            'PerformanceEvaluation' => "Avaliação criada para funcionário '{$model->employee->name}'",
            'Department' => "Departamento '{$model->name}' criado",
            'MaintenanceType' => "Tipo de manutenção '{$model->name}' criado",
        ];

        return $messages[$className] ?? "Novo(a) {$className} criado(a)";
    }

    /**
     * Mensagem personalizada para actualização
     */
    protected static function getUpdatedMessage(Model $model): string
    {
        $className = class_basename($model);
        
        $messages = [
            'Company' => "Empresa '{$model->name}' actualizada",
            'User' => "Usuário '{$model->name}' actualizado",
            'Employee' => "Funcionário '{$model->name}' actualizado",
            'Client' => "Cliente '{$model->name}' actualizado",
            'Material' => "Material '{$model->name}' actualizado",
            'RepairOrder' => "Ordem de reparação #{$model->repair_order_number} actualizada",
            'Billing' => "Faturação da ordem #{$model->repair_order_number} actualizada",
            'PerformanceEvaluation' => "Avaliação do funcionário '{$model->employee->name}' actualizada",
            'Department' => "Departamento '{$model->name}' actualizado",
            'MaintenanceType' => "Tipo de manutenção '{$model->name}' actualizado",
        ];

        return $messages[$className] ?? "{$className} actualizado(a)";
    }

    /**
     * Mensagem personalizada para eliminação
     */
    protected static function getDeletedMessage(Model $model): string
    {
        $className = class_basename($model);
        
        $messages = [
            'Company' => "Empresa '{$model->name}' eliminada",
            'User' => "Usuário '{$model->name}' eliminado",
            'Employee' => "Funcionário '{$model->name}' eliminado",
            'Client' => "Cliente '{$model->name}' eliminado",
            'Material' => "Material '{$model->name}' eliminado",
            'RepairOrder' => "Ordem de reparação #{$model->repair_order_number} eliminada",
            'Billing' => "Faturação da ordem #{$model->repair_order_number} eliminada",
            'PerformanceEvaluation' => "Avaliação do funcionário eliminada",
            'Department' => "Departamento '{$model->name}' eliminado",
            'MaintenanceType' => "Tipo de manutenção '{$model->name}' eliminado",
        ];

        return $messages[$className] ?? "{$className} eliminado(a)";
    }

    /**
     * Log manual de actividade para o modelo
     */
    public function logActivity(string $action, string $description, array $metadata = []): void
    {
        $logger = app(ActivityLoggerService::class);
        
        \App\Models\System\ActivityLog::logActivity([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()?->company_id ?? $this->company_id,
            'action' => $action,
            'model' => get_class($this),
            'model_id' => $this->id,
            'description' => $description,
            'category' => $this->getLogCategory(),
            'level' => 'info',
            'metadata' => $metadata
        ]);
    }

    /**
     * Obter categoria do log baseada no modelo
     */
    protected function getLogCategory(): string
    {
        $className = class_basename($this);
        
        $mapping = [
            'User' => 'user',
            'Company' => 'company',
            'RepairOrder' => 'repair_order',
            'OrderForm1' => 'repair_order',
            'OrderForm2' => 'repair_order',
            'OrderForm3' => 'repair_order',
            'OrderForm4' => 'repair_order',
            'OrderForm5' => 'repair_order',
            'Billing' => 'billing',
            'Employee' => 'employee',
            'Client' => 'client',
            'Material' => 'material',
            'PerformanceEvaluation' => 'performance',
            'Department' => 'system',
            'MaintenanceType' => 'system',
            'Plan' => 'system',
            'Subscription' => 'system',
        ];

        return $mapping[$className] ?? 'system';
    }

    /**
     * Log de mudança de status (para modelos com status)
     */
    public function logStatusChange(string $oldStatus, string $newStatus, string $reason = null): void
    {
        $description = "Status alterado de '{$oldStatus}' para '{$newStatus}'";
        if ($reason) {
            $description .= " - Motivo: {$reason}";
        }

        $this->logActivity(
            'status_change',
            $description,
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason
            ]
        );
    }

    /**
     * Log específico para formulários de ordem de reparação
     */
    public function logFormSubmission(int $formNumber): void
    {
        $logger = app(ActivityLoggerService::class);
        $logger->logFormSubmitted($this, $formNumber);
    }
}