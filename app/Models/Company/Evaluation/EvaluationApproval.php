<?php

namespace App\Models\Company\Evaluation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationApproval extends Model
{
     use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'stage_number',
        'stage_name',
        'approver_id',
        'status',
        'comments',
        'reviewed_at'
    ];

    protected $casts = [
        'stage_number' => 'integer',
        'reviewed_at' => 'datetime'
    ];

    // Relationships
    public function evaluation()
    {
        return $this->belongsTo(PerformanceEvaluation::class, 'evaluation_id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }

    // ===== SCOPES =====
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForApprover($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    public function scopeForStage($query, $stageNumber)
    {
        return $query->where('stage_number', $stageNumber);
    }

    public function scopeForEvaluation($query, $evaluationId)
    {
        return $query->where('evaluation_id', $evaluationId);
    }

    // ===== ACCESSORS =====
    
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'waiting' => 'Aguardando',
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'cancelled' => 'Cancelado',
            default => $this->status
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'waiting' => 'gray',
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'waiting' => 'clock',
            'pending' => 'exclamation-circle',
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'cancelled' => 'ban',
            default => 'question-mark-circle'
        };
    }

    // ===== MÉTODOS ÚTEIS =====

    /**
     * Verificar se esta aprovação está ativa (pendente)
     */
    public function isActive()
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar se esta aprovação foi processada
     */
    public function isProcessed()
    {
        return in_array($this->status, ['approved', 'rejected']);
    }

    /**
     * Verificar se pode ser aprovada pelo usuário
     */
    public function canBeApprovedBy($userId)
    {
        return $this->status === 'pending' && $this->approver_id === $userId;
    }

    /**
     * Verificar se pode ser rejeitada pelo usuário
     */
    public function canBeRejectedBy($userId)
    {
        return $this->status === 'pending' && $this->approver_id === $userId;
    }

    /**
     * Aprovar este estágio
     */
    public function approve($comments = null)
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Esta aprovação não está pendente');
        }

        $this->update([
            'status' => 'approved',
            'comments' => $comments,
            'reviewed_at' => now()
        ]);

        \Log::info('Estágio aprovado individualmente', [
            'approval_id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'stage_number' => $this->stage_number,
            'approver_id' => $this->approver_id
        ]);
    }

    /**
     * Rejeitar este estágio
     */
    public function reject($reason)
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Esta aprovação não está pendente');
        }

        if (empty($reason)) {
            throw new \Exception('Motivo da rejeição é obrigatório');
        }

        $this->update([
            'status' => 'rejected',
            'comments' => $reason,
            'reviewed_at' => now()
        ]);

        \Log::info('Estágio rejeitado', [
            'approval_id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'stage_number' => $this->stage_number,
            'approver_id' => $this->approver_id,
            'reason' => $reason
        ]);
    }

    /**
     * Cancelar esta aprovação
     */
    public function cancel()
    {
        if ($this->isProcessed()) {
            throw new \Exception('Aprovação já foi processada');
        }

        $this->update([
            'status' => 'cancelled',
            'reviewed_at' => now()
        ]);
    }

    /**
     * Ativar esta aprovação (tornar pendente)
     */
    public function activate()
    {
        if ($this->status !== 'waiting') {
            throw new \Exception('Só é possível ativar aprovações em espera');
        }

        $this->update(['status' => 'pending']);

        \Log::info('Aprovação ativada (tornou-se pendente)', [
            'approval_id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'stage_number' => $this->stage_number,
            'approver_id' => $this->approver_id
        ]);
    }

    /**
     * Obter próxima aprovação na sequência
     */
    public function getNextApproval()
    {
        return static::where('evaluation_id', $this->evaluation_id)
            ->where('stage_number', '>', $this->stage_number)
            ->orderBy('stage_number')
            ->first();
    }

    /**
     * Obter aprovação anterior na sequência
     */
    public function getPreviousApproval()
    {
        return static::where('evaluation_id', $this->evaluation_id)
            ->where('stage_number', '<', $this->stage_number)
            ->orderBy('stage_number', 'desc')
            ->first();
    }

    /**
     * Verificar se é o primeiro estágio
     */
    public function isFirstStage()
    {
        return $this->stage_number === 1;
    }

    /**
     * Verificar se é o último estágio
     */
    public function isLastStage()
    {
        $maxStage = static::where('evaluation_id', $this->evaluation_id)
            ->max('stage_number');
        
        return $this->stage_number === $maxStage;
    }

    /**
     * Obter todas as aprovações da avaliação
     */
    public function getAllApprovals()
    {
        return static::where('evaluation_id', $this->evaluation_id)
            ->orderBy('stage_number')
            ->get();
    }

    /**
     * Obter estatísticas das aprovações da avaliação
     */
    public function getApprovalStats()
    {
        $allApprovals = $this->getAllApprovals();
        
        return [
            'total' => $allApprovals->count(),
            'approved' => $allApprovals->where('status', 'approved')->count(),
            'rejected' => $allApprovals->where('status', 'rejected')->count(),
            'pending' => $allApprovals->where('status', 'pending')->count(),
            'waiting' => $allApprovals->where('status', 'waiting')->count(),
            'cancelled' => $allApprovals->where('status', 'cancelled')->count(),
            'current_stage' => $allApprovals->where('status', 'pending')->first()?->stage_number,
            'progress_percentage' => $allApprovals->count() > 0 
                ? ($allApprovals->where('status', 'approved')->count() / $allApprovals->count()) * 100 
                : 0
        ];
    }

    // ===== MÉTODOS ESTÁTICOS =====

    /**
     * Criar aprovações para uma avaliação baseado nos estágios do departamento
     */
    public static function createForEvaluation(PerformanceEvaluation $evaluation)
    {
        $stages = EvaluationApprovalStage::where('company_id', $evaluation->company_id)
            ->where('target_department_id', $evaluation->employee->department_id)
            ->where('is_active', true)
            ->orderBy('stage_number')
            ->get();

        if ($stages->isEmpty()) {
            throw new \Exception('Nenhum estágio configurado para o departamento: ' . $evaluation->employee->department->name);
        }

        $approvals = [];
        foreach ($stages as $stage) {
            $approval = static::create([
                'evaluation_id' => $evaluation->id,
                'stage_number' => $stage->stage_number,
                'stage_name' => $stage->stage_name,
                'approver_id' => $stage->approver_user_id,
                'status' => $stage->stage_number === 1 ? 'pending' : 'waiting'
            ]);
            
            $approvals[] = $approval;
        }

        \Log::info('Aprovações criadas para avaliação', [
            'evaluation_id' => $evaluation->id,
            'department_id' => $evaluation->employee->department_id,
            'total_stages' => count($approvals)
        ]);

        return $approvals;
    }

    /**
     * Obter aprovações pendentes para um usuário
     */
    public static function getPendingForUser($userId, $companyId = null)
    {
        $query = static::with(['evaluation.employee', 'evaluation.employee.department'])
            ->where('approver_id', $userId)
            ->where('status', 'pending');

        if ($companyId) {
            $query->whereHas('evaluation', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        return $query->orderBy('created_at', 'asc')->get();
    }
}
