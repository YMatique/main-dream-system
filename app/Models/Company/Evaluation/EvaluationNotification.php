<?php

namespace App\Models\Company\Evaluation;

use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'evaluation_id',
        'type',
        'recipients',
        'subject',
        'message',
        'status',
        'sent_at',
        'error_message',
        'retry_count'
    ];

    protected $casts = [
        'recipients' => 'array',
        'sent_at' => 'datetime',
        'retry_count' => 'integer'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(PerformanceEvaluation::class, 'evaluation_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForRetry($query)
    {
        return $query->where('status', 'failed')
                    ->where('retry_count', '<', 3);
    }
}
