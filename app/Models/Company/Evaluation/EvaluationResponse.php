<?php

namespace App\Models\Company\Evaluation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'metric_id',
        'numeric_value',
        'rating_value',
        'calculated_score',
        'comments'
    ];

    protected $casts = [
        'numeric_value' => 'decimal:2',
        'calculated_score' => 'decimal:2'
    ];

    // Relationships
    public function evaluation()
    {
        return $this->belongsTo(PerformanceEvaluation::class, 'evaluation_id');
    }

    public function metric()
    {
        return $this->belongsTo(PerformanceMetric::class, 'metric_id');
    }

    // Accessors
    public function getDisplayValueAttribute()
    {
        if ($this->metric->type === 'rating') {
            return $this->rating_value;
        } elseif ($this->metric->type === 'boolean') {
            return $this->numeric_value ? 'Sim' : 'Não';
        }
        
        return $this->numeric_value;
    }

    // Methods
    public function calculateScore()
    {
        $value = $this->metric->type === 'rating' 
            ? $this->rating_value 
            : $this->numeric_value;
            
        $score = $this->metric->calculateScore($value);
        
        $this->update(['calculated_score' => $score]);
        
        return $score;
    }
}
