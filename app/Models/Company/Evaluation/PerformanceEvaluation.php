<?php

namespace App\Models\Company\Evaluation;

use App\Models\Company\Employee;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'notifications_sent' => 'boolean'
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

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'submitted' => 'Aguardando Aprovação',
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
        return match(true) {
            $this->final_percentage >= 90 => 'Excelente',
            $this->final_percentage >= 70 => 'Bom',
            $this->final_percentage >= 50 => 'Satisfatório',
            default => 'Péssimo'
        };
    }

    public function getPerformanceColorAttribute()
    {
        return match(true) {
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

    /**
     * SUBMETER AVALIAÇÃO PARA APROVAÇÃO
     */
    public function submit()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        // Enviar notificações se abaixo do threshold
        if ($this->is_below_threshold) {
            $this->sendLowPerformanceNotifications();
        }
    }

    /**
     * APROVAR AVALIAÇÃO - MÉTODO SIMPLIFICADO
     */
    public function approve($approverId, $comments = null)
    {
        // Verificar se pode ser aprovada
        if ($this->status !== 'submitted') {
            throw new \Exception('Avaliação não pode ser aprovada. Status atual: ' . $this->status);
        }

        // Verificar se usuário pode aprovar
        $user = \App\Models\User::find($approverId);
        if (!$user || $user->company_id !== $this->company_id) {
            throw new \Exception('Usuário não tem permissão para aprovar esta avaliação');
        }

        if (!$user->isCompanyAdmin() && !$user->hasPermission('evaluation.approve')) {
            throw new \Exception('Usuário não tem permissão para aprovar avaliações');
        }

        // Aprovar
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approverId,
            'approval_comments' => $comments
        ]);

        \Log::info('Avaliação aprovada', [
            'evaluation_id' => $this->id,
            'approved_by' => $approverId,
            'employee' => $this->employee->name
        ]);
    }

    /**
     * REJEITAR AVALIAÇÃO - MÉTODO SIMPLIFICADO
     */
    public function reject($approverId, $reason)
    {
        // Verificar se pode ser rejeitada
        if ($this->status !== 'submitted') {
            throw new \Exception('Avaliação não pode ser rejeitada. Status atual: ' . $this->status);
        }

        // Verificar se usuário pode rejeitar
        $user = \App\Models\User::find($approverId);
        if (!$user || $user->company_id !== $this->company_id) {
            throw new \Exception('Usuário não tem permissão para rejeitar esta avaliação');
        }

        if (!$user->isCompanyAdmin() && !$user->hasPermission('evaluation.approve')) {
            throw new \Exception('Usuário não tem permissão para rejeitar avaliações');
        }

        // Rejeitar
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $approverId,
            'rejection_reason' => $reason
        ]);

        \Log::info('Avaliação rejeitada', [
            'evaluation_id' => $this->id,
            'rejected_by' => $approverId,
            'reason' => $reason
        ]);
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
