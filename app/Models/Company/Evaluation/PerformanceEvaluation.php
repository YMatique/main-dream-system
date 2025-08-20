<?php

namespace App\Models\Company\Evaluation;

use App\Models\Company\Employee;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'evaluator_id',
        'evaluation_period',
        'total_score',
        'final_percentage',
        'recommendations',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'approval_comments',
        'is_below_threshold',
        'current_stage_number',
        'notifications_sent'
    ];

    protected $casts = [
        'evaluation_period' => 'date',
        'total_score' => 'decimal:2',
        'final_percentage' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'is_below_threshold' => 'boolean',
        'notifications_sent' => 'boolean',
        'current_stage_number' => 'integer',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(\App\Models\User::class, 'evaluator_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'rejected_by');
    }

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class, 'evaluation_id');
    }

    /**
     * Aprovações por estágio
     */
    public function approvals()
    {
        return $this->hasMany(EvaluationApproval::class, 'evaluation_id')
            ->orderBy('stage_number');
    }

    /**
     * ✅ MÉTODO CORRIGIDO - Obter aprovação do estágio atual
     */
    public function getCurrentStageApproval()
    {
        if (!$this->current_stage_number) {
            return null;
        }

        return $this->approvals()
            ->where('stage_number', $this->current_stage_number)
            ->first();
    }

    /**
     * Configuração dos estágios do departamento
     */
    public function departmentStages()
    {
        return EvaluationApprovalStage::where('company_id', $this->company_id)
            ->where('target_department_id', $this->employee->department_id)
            ->where('is_active', true)
            ->orderBy('stage_number')
            ->get();
    }

    // Scopes
    public function scopeForPeriod($query, $year, $month = null)
    {
        if ($month) {
            return $query->whereYear('evaluation_period', $year)
                ->whereMonth('evaluation_period', $month);
        }
        return $query->whereYear('evaluation_period', $year);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBelowThreshold($query)
    {
        return $query->where('is_below_threshold', true);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * ✅ SCOPE CORRIGIDO - Avaliações pendentes para um aprovador específico
     */
    public function scopePendingForApprover($query, $userId)
    {
        return $query->where('status', 'in_approval')
            ->whereExists(function ($subquery) use ($userId) {
                $subquery->select(DB::raw(1))
                    ->from('evaluation_approvals')
                    ->whereColumn('evaluation_approvals.evaluation_id', 'performance_evaluations.id')
                    ->whereColumn('evaluation_approvals.stage_number', 'performance_evaluations.current_stage_number')
                    ->where('evaluation_approvals.approver_id', $userId)
                    ->where('evaluation_approvals.status', 'pending');
            });
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'draft' => 'Rascunho',
            'submitted' => 'Submetida',
            'in_approval' => 'Em Aprovação',
            'approved' => 'Aprovada',
            'rejected' => 'Rejeitada',
            default => $this->status
        };
    }

    public function getEvaluationPeriodFormattedAttribute()
    {
        return $this->evaluation_period->format('m/Y');
    }

    public function getPerformanceClassAttribute()
    {
        return match (true) {
            $this->final_percentage >= 90 => 'Excelente',
            $this->final_percentage >= 70 => 'Bom',
            $this->final_percentage >= 50 => 'Satisfatório',
            default => 'Péssimo'
        };
    }

    public function getPerformanceColorAttribute()
    {
        return match (true) {
            $this->final_percentage >= 90 => 'green',
            $this->final_percentage >= 70 => 'blue',
            $this->final_percentage >= 50 => 'yellow',
            default => 'red'
        };
    }

    // ===== MÉTODOS SIMPLIFICADOS =====

    public function calculateFinalScore()
    {
        $totalScore = $this->responses()->sum('calculated_score');
        $this->update([
            'total_score' => $totalScore,
            'final_percentage' => min(100, $totalScore),
            'is_below_threshold' => $totalScore < 50
        ]);

        return $this->final_percentage;
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canBeSubmitted()
    {
        return $this->status === 'draft' && $this->responses()->count() > 0;
    }

    public function canBeApproved($userId = null)
    {
        if ($this->status !== 'submitted') {
            return false;
        }

        // Se não especificar usuário, só verifica o status
        if (!$userId) {
            return true;
        }

        // Verificar se usuário pode aprovar
        $user = \App\Models\User::find($userId);
        return $user &&
            $user->company_id === $this->company_id &&
            ($user->isCompanyAdmin() || $user->hasPermission('evaluation.approve'));
    }

    // =========== APROVAÇÃO MULTI-ESTÁGIO =============

    /**
     * SUBMETER AVALIAÇÃO → CRIAR ESTÁGIOS DE APROVAÇÃO
     */
    public function submit()
    {
        // Verificar se pode ser submetida
        if (!$this->canBeSubmitted()) {
            throw new \Exception('Avaliação não pode ser submetida');
        }

        // Obter estágios configurados para o departamento
        $stages = $this->departmentStages();

        if ($stages->isEmpty()) {
            throw new \Exception('Nenhum estágio de aprovação configurado para o departamento: ' . $this->employee->department->name);
        }

        DB::transaction(function () use ($stages) {
            // Atualizar status da avaliação
            $this->update([
                'status' => 'in_approval',
                'current_stage_number' => 1,
                'submitted_at' => now()
            ]);

            // Criar registros de aprovação para cada estágio
            foreach ($stages as $stage) {
                EvaluationApproval::create([
                    'evaluation_id' => $this->id,
                    'stage_number' => $stage->stage_number,
                    'stage_name' => $stage->stage_name,
                    'approver_id' => $stage->approver_user_id,
                    'status' => $stage->stage_number === 1 ? 'pending' : 'waiting',
                    'reviewed_at' => null
                ]);
            }

            // Enviar notificações se abaixo do threshold
            if ($this->is_below_threshold) {
                $this->sendLowPerformanceNotifications();
            }

            // Notificar primeiro aprovador
            $this->notifyCurrentStageApprover();
        });

        Log::info('Avaliação submetida para aprovação multi-estágio', [
            'evaluation_id' => $this->id,
            'employee' => $this->employee->name,
            'total_stages' => $stages->count()
        ]);
    }

    /**
     * ✅ MÉTODO CORRIGIDO - APROVAR ESTÁGIO ATUAL
     */
    public function approveCurrentStage($approverId, $comments = null)
    {
        // Verificar se pode ser aprovada
        if ($this->status !== 'in_approval') {
            throw new \Exception('Avaliação não está em processo de aprovação');
        }

        // Obter aprovação do estágio atual
        $currentApproval = $this->getCurrentStageApproval();
        if (!$currentApproval) {
            throw new \Exception('Estágio atual não encontrado');
        }

        // Verificar se usuário pode aprovar este estágio
        if ($currentApproval->approver_id !== $approverId) {
            throw new \Exception('Usuário não tem permissão para aprovar este estágio');
        }

        DB::transaction(function () use ($currentApproval, $approverId, $comments) {
            // Aprovar estágio atual
            $currentApproval->update([
                'status' => 'approved',
                'comments' => $comments,
                'reviewed_at' => now()
            ]);

            // Verificar se é o último estágio
            if ($this->isAtLastStage()) {
                $this->finalizeApproval($approverId, $comments);
            } else {
                $this->advanceToNextStage();
            }
        });

        Log::info('Estágio aprovado', [
            'evaluation_id' => $this->id,
            'stage_number' => $this->current_stage_number,
            'approved_by' => $approverId,
            'is_final' => $this->isAtLastStage()
        ]);
    }

    /**
     * ✅ MÉTODO CORRIGIDO - REJEITAR AVALIAÇÃO (qualquer estágio)
     */
    public function rejectAtCurrentStage($approverId, $reason)
    {
        // Verificar se pode ser rejeitada
        if ($this->status !== 'in_approval') {
            throw new \Exception('Avaliação não está em processo de aprovação');
        }

        // Obter aprovação do estágio atual
        $currentApproval = $this->getCurrentStageApproval();
        if (!$currentApproval || $currentApproval->approver_id !== $approverId) {
            throw new \Exception('Usuário não tem permissão para rejeitar este estágio');
        }

        DB::transaction(function () use ($currentApproval, $approverId, $reason) {
            // Rejeitar estágio atual
            $currentApproval->update([
                'status' => 'rejected',
                'comments' => $reason,
                'reviewed_at' => now()
            ]);

            // Marcar todas as aprovações restantes como canceladas
            $this->approvals()
                ->where('stage_number', '>', $this->current_stage_number)
                ->update(['status' => 'cancelled']);

            // Voltar avaliação para rascunho
            $this->update([
                'status' => 'rejected',
                'current_stage_number' => null,
                'rejected_at' => now(),
                'rejected_by' => $approverId,
                'rejection_reason' => $reason
            ]);
        });

        // Notificar avaliador original
        $this->notifyEvaluatorOfRejection($reason);

        Log::info('Avaliação rejeitada', [
            'evaluation_id' => $this->id,
            'stage_number' => $this->current_stage_number,
            'rejected_by' => $approverId,
            'reason' => $reason
        ]);
    }

    /**
     * AVANÇAR PARA PRÓXIMO ESTÁGIO
     */
    protected function advanceToNextStage()
    {
        $nextStageNumber = $this->current_stage_number + 1;

        // Atualizar estágio atual
        $this->update(['current_stage_number' => $nextStageNumber]);

        // Ativar próximo estágio
        $nextApproval = $this->approvals()
            ->where('stage_number', $nextStageNumber)
            ->first();

        if ($nextApproval) {
            $nextApproval->update(['status' => 'pending']);
            $this->notifyCurrentStageApprover();
        }

        Log::info('Avaliação avançou para próximo estágio', [
            'evaluation_id' => $this->id,
            'new_stage' => $nextStageNumber
        ]);
    }

    /**
     * FINALIZAR APROVAÇÃO (último estágio)
     */
    protected function finalizeApproval($approverId, $comments)
    {
        $this->update([
            'status' => 'approved',
            'current_stage_number' => null,
            'approved_at' => now(),
            'approved_by' => $approverId,
            'approval_comments' => $comments
        ]);

        // Notificar avaliador e funcionário
        $this->notifyEvaluatorOfApproval();
        $this->notifyEmployeeOfApproval();

        Log::info('Avaliação totalmente aprovada', [
            'evaluation_id' => $this->id,
            'approved_by' => $approverId
        ]);
    }

    /**
     * Verificar se está no último estágio
     */
    public function isAtLastStage()
    {
        $stages = $this->departmentStages();
        $maxStage = $stages->max('stage_number');

        return $this->current_stage_number === $maxStage;
    }

    /**
     * ✅ MÉTODO CORRIGIDO - Obter aprovador do estágio atual
     */
    public function getCurrentStageApprover()
    {
        $currentApproval = $this->getCurrentStageApproval();
        return $currentApproval ? $currentApproval->approver : null;
    }

    /**
     * ✅ MÉTODO CORRIGIDO - Verificar se usuário pode aprovar estágio atual
     */
    public function canUserApproveCurrentStage($userId)
    {
        if ($this->status !== 'in_approval') {
            return false;
        }

        $currentApproval = $this->getCurrentStageApproval();
        return $currentApproval && $currentApproval->approver_id === $userId;
    }

    // ===== NOTIFICAÇÕES =====

    protected function notifyCurrentStageApprover()
    {
        $approver = $this->getCurrentStageApprover();
        if ($approver) {
            // Implementar notificação
            Log::info('Notificando aprovador do estágio atual', [
                'evaluation_id' => $this->id,
                'stage' => $this->current_stage_number,
                'approver' => $approver->name
            ]);
        }
    }

    protected function notifyEvaluatorOfRejection($reason)
    {
        if ($this->evaluator) {
            // Implementar notificação
            Log::info('Notificando avaliador sobre rejeição', [
                'evaluation_id' => $this->id,
                'evaluator' => $this->evaluator->name,
                'reason' => $reason
            ]);
        }
    }

    protected function notifyEvaluatorOfApproval()
    {
        if ($this->evaluator) {
            // Implementar notificação
            Log::info('Notificando avaliador sobre aprovação final', [
                'evaluation_id' => $this->id,
                'evaluator' => $this->evaluator->name
            ]);
        }
    }

    protected function notifyEmployeeOfApproval()
    {
        // Implementar notificação para o funcionário
        Log::info('Notificando funcionário sobre aprovação', [
            'evaluation_id' => $this->id,
            'employee' => $this->employee->name
        ]);
    }

    /**
     * APROVAR AVALIAÇÃO - MÉTODO SIMPLIFICADO
     */
    public function approve($approverId, $comments = null)
    {
        if ($this->status === 'submitted') {
            // Se ainda não tem estágios, criar e submeter
            $this->submit();
        }

        return $this->approveCurrentStage($approverId, $comments);
    }

    /**
     * REJEITAR AVALIAÇÃO - MÉTODO SIMPLIFICADO
     */
    public function reject($approverId, $reason)
    {
        return $this->rejectAtCurrentStage($approverId, $reason);
    }

    /**
     * Verificar se usuário específico pode aprovar
     */
    public function canUserApprove($userId)
    {
        return $this->canBeApproved($userId);
    }

    /**
     * Enviar notificações de performance baixa
     */
    protected function sendLowPerformanceNotifications()
    {
        if ($this->notifications_sent) {
            return;
        }

        // Implementar notificações conforme necessário
        // Por enquanto só marcar como enviado
        $this->update(['notifications_sent' => true]);
    }

    /**
     * Obter histórico de avaliações do funcionário
     */
    public function getEmployeeHistory($limit = 6)
    {
        return static::where('employee_id', $this->employee_id)
            ->where('status', 'approved')
            ->where('id', '!=', $this->id)
            ->orderBy('evaluation_period', 'desc')
            ->limit($limit)
            ->get(['evaluation_period', 'final_percentage', 'performance_class', 'approved_at']);
    }
}
