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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForApprover($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            default => $this->status
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}
