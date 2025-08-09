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
        'current_approval_stage',
        'submitted_at',
        'approved_at',
        'approved_by',
        'is_below_threshold',
        'notifications_sent'
    ];

    protected $casts = [
        'evaluation_period' => 'date',
        'total_score' => 'decimal:2',
        'final_percentage' => 'decimal:2',
        'current_approval_stage' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
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

    public function responses()
    {
        return $this->hasMany(EvaluationResponse::class, 'evaluation_id');
    }

    public function approvals()
    {
        return $this->hasMany(EvaluationApproval::class, 'evaluation_id');
    }

    public function notifications()
    {
        return $this->hasMany(EvaluationNotification::class, 'evaluation_id');
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
        return $query->where('status', 'in_approval');
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
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

    // Methods
    public function calculateFinalScore()
    {
        $totalScore = $this->responses()->sum('calculated_score');
        $this->update([
            'total_score' => $totalScore,
            'final_percentage' => min(100, $totalScore), // Cap at 100%
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

    public function canBeApproved($userId)
    {
        if ($this->status !== 'in_approval') {
            return false;
        }

        return $this->approvals()
            ->where('stage_number', $this->current_approval_stage)
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    public function submit()
    {
        $this->update([
            'status' => 'in_approval',
            'submitted_at' => now(),
            'current_approval_stage' => 1
        ]);

        // Criar registros de aprovação
        $this->createApprovalStages();

        // Enviar notificações se abaixo do threshold
        if ($this->is_below_threshold) {
            $this->sendLowPerformanceNotifications();
        }
    }

    public function approve($approverId, $comments = null)
    {
        $approval = $this->approvals()
            ->where('stage_number', $this->current_approval_stage)
            ->where('approver_id', $approverId)
            ->first();

        if (!$approval) {
            throw new \Exception('Aprovação não encontrada');
        }

        $approval->update([
            'status' => 'approved',
            'comments' => $comments,
            'reviewed_at' => now()
        ]);

        // Verificar se há próximo estágio
        $nextStage = $this->current_approval_stage + 1;
        $hasNextStage = $this->approvals()
            ->where('stage_number', $nextStage)
            ->exists();

        if ($hasNextStage) {
            $this->update(['current_approval_stage' => $nextStage]);
        } else {
            // Aprovação final
            $this->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $approverId
            ]);
        }
    }

    public function reject($approverId, $comments)
    {
        $approval = $this->approvals()
            ->where('stage_number', $this->current_approval_stage)
            ->where('approver_id', $approverId)
            ->first();

        if (!$approval) {
            throw new \Exception('Aprovação não encontrada');
        }

        $approval->update([
            'status' => 'rejected',
            'comments' => $comments,
            'reviewed_at' => now()
        ]);

        $this->update(['status' => 'rejected']);
    }

    protected function createApprovalStages()
    {
        $stages = EvaluationApprovalStage::where('company_id', $this->company_id)
            ->where('is_active', true)
            ->orderBy('stage_number')
            ->get();

        foreach ($stages as $stage) {
            // Determinar aprovadores baseado nas regras do estágio
            $approvers = $this->getApproversForStage($stage);

            foreach ($approvers as $approverId) {
                EvaluationApproval::create([
                    'evaluation_id' => $this->id,
                    'stage_number' => $stage->stage_number,
                    'stage_name' => $stage->stage_name,
                    'approver_id' => $approverId,
                    'status' => 'pending'
                ]);
            }
        }
    }

    protected function getApproversForStage($stage)
    {
        // Lógica para determinar aprovadores baseado no estágio
        // Por enquanto retorna o admin master da empresa
        return [\App\Models\User::where('company_id', $this->company_id)
                                ->where('user_type', 'company_admin')
                                ->first()?->id ?? 1];
    }

    protected function sendLowPerformanceNotifications()
    {
        if ($this->notifications_sent) {
            return;
        }

        // Lógica para enviar notificações
        // Implementar em um Job/Service separado
        
        $this->update(['notifications_sent' => true]);
    }
}
