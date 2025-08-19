<?php

namespace App\Models\Company\Evaluation;

use App\Models\Company\Department;
use App\Models\System\Company;
use App\Models\User;
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
        'is_active',
        'is_final_stage',
        'approver_user_id',
        'target_department_id',

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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
    public function targetDepartment()
    {
        return $this->belongsTo(Department::class, 'target_department_id');
    }
    // Método simples para verificar:
    public function isFinalStage()
    {
        return $this->is_final_stage;
    }

    // Scope para buscar último estágio:
    public function scopeFinalStage($query)
    {
        return $query->where('is_final_stage', true);
    }

       /**
     * Aprovações que usaram este estágio
     */
    public function evaluationApprovals()
    {
        return $this->hasMany(EvaluationApproval::class, 'stage_number', 'stage_number')
            ->whereHas('evaluation.employee', function($query) {
                $query->where('department_id', $this->target_department_id);
            });
    }


    /**
     * NOVO: Buscar estágios de um departamento específico
     */
    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('target_department_id', $departmentId);
    }


    /**
     * NOVO: Buscar por aprovador específico
     */
    public function scopeForApprover($query, $userId)
    {
        return $query->where('approver_user_id', $userId);
    }

    // ===== MÉTODOS ÚTEIS =====


    /**
     * Obter próximo estágio deste departamento
     */
    public function getNextStage()
    {
        if ($this->is_final_stage) {
            return null;
        }

        return static::where('target_department_id', $this->target_department_id)
            ->where('company_id', $this->company_id)
            ->where('stage_number', '>', $this->stage_number)
            ->where('is_active', true)
            ->orderBy('stage_number')
            ->first();
    }

    /**
     * Obter estágio anterior deste departamento
     */
    public function getPreviousStage()
    {
        return static::where('target_department_id', $this->target_department_id)
            ->where('company_id', $this->company_id)
            ->where('stage_number', '<', $this->stage_number)
            ->where('is_active', true)
            ->orderBy('stage_number', 'desc')
            ->first();
    }

    /**
     * Verificar se usuário pode aprovar este estágio
     */
    public function canUserApprove($userId)
    {
        // Verificação direta por usuário específico
        if ($this->approver_user_id) {
            return $this->approver_user_id == $userId;
        }

        // Fallback para verificação por roles (compatibilidade)
        $user = User::find($userId);
        if (!$user || $user->company_id !== $this->company_id) {
            return false;
        }

        // Admin Master sempre pode aprovar
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // Verificar por roles antigas (se configurado)
        if ($this->approver_roles && is_array($this->approver_roles)) {
            return $user->hasAnyRole($this->approver_roles);
        }

        return false;
    }

    /**
     * Obter todos os estágios do departamento em ordem
     */
    public static function getStagesForDepartment($departmentId, $companyId)
    {
        return static::where('target_department_id', $departmentId)
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('stage_number')
            ->get();
    }

    /**
     * Obter estágio específico por número
     */
    public static function getStageByNumber($departmentId, $companyId, $stageNumber)
    {
        return static::where('target_department_id', $departmentId)
            ->where('company_id', $companyId)
            ->where('stage_number', $stageNumber)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Validar configuração dos estágios de um departamento
     */
    public static function validateDepartmentStages($departmentId, $companyId)
    {
        $stages = static::getStagesForDepartment($departmentId, $companyId);

        if ($stages->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'Nenhum estágio configurado para este departamento.'
            ];
        }

        // Verificar sequência
        $expectedNumber = 1;
        foreach ($stages as $stage) {
            if ($stage->stage_number !== $expectedNumber) {
                return [
                    'valid' => false,
                    'message' => "Sequência de estágios quebrada. Esperado: {$expectedNumber}, Encontrado: {$stage->stage_number}"
                ];
            }
            $expectedNumber++;
        }

        // Verificar se tem estágio final
        $finalStages = $stages->where('is_final_stage', true);
        if ($finalStages->count() !== 1) {
            return [
                'valid' => false,
                'message' => 'Deve haver exatamente um estágio final por departamento.'
            ];
        }

        // Verificar se o último estágio é realmente o final
        $lastStage = $stages->last();
        if (!$lastStage->is_final_stage) {
            return [
                'valid' => false,
                'message' => 'O último estágio deve ser marcado como final.'
            ];
        }

        // Verificar se todos têm aprovadores
        $stagesWithoutApprover = $stages->whereNull('approver_user_id');
        if ($stagesWithoutApprover->isNotEmpty()) {
            return [
                'valid' => false,
                'message' => 'Todos os estágios devem ter um aprovador definido.'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Configuração válida.',
            'total_stages' => $stages->count()
        ];
    }

    // ===== EVENTOS =====

    protected static function booted()
    {
        // Quando criar/atualizar, recalcular is_final_stage
        static::saved(function ($stage) {
            $stage->updateFinalStageFlags();
        });

        // Quando deletar, recalcular is_final_stage
        static::deleted(function ($stage) {
            $stage->updateFinalStageFlags();
        });
    }

    /**
     * Atualizar flags de estágio final após mudanças
     */
    public function updateFinalStageFlags()
    {
        if (!$this->target_department_id) {
            return;
        }

        // Limpar todas as flags do departamento
        static::where('target_department_id', $this->target_department_id)
            ->where('company_id', $this->company_id)
            ->update(['is_final_stage' => false]);

        // Marcar o último estágio ativo como final
        $maxStageNumber = static::where('target_department_id', $this->target_department_id)
            ->where('company_id', $this->company_id)
            ->where('is_active', true)
            ->max('stage_number');

        if ($maxStageNumber) {
            static::where('target_department_id', $this->target_department_id)
                ->where('company_id', $this->company_id)
                ->where('stage_number', $maxStageNumber)
                ->update(['is_final_stage' => true]);
        }
    }
}
