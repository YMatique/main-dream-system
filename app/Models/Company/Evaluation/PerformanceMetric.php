<?php

namespace App\Models\Company\Evaluation;

use App\Models\Company\Department;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'description',
        'type',
        'weight',
        'min_value',
        'max_value',
        'rating_options',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'weight' => 'integer',
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'rating_options' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function evaluationResponses()
    {
        return $this->hasMany(EvaluationResponse::class, 'metric_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors & Mutators
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'numeric' => 'Numérico (0-10)',
            'rating' => 'Avaliação Rápida',
            'boolean' => 'Sim/Não',
            default => $this->type
        };
    }

    public function getDefaultRatingOptionsAttribute()
    {
        return ['Péssimo', 'Satisfatório', 'Bom', 'Excelente'];
    }

    // Methods
    public function calculateScore($value)
    {
        if ($this->type === 'rating') {
            $options = $this->rating_options ?? $this->default_rating_options;
            $index = array_search($value, $options);
            if ($index !== false) {
                // Converte rating para escala 0-10
                $maxIndex = count($options) - 1;
                $numericValue = ($index / $maxIndex) * 10;
            } else {
                $numericValue = 0;
            }
        } elseif ($this->type === 'boolean') {
            $numericValue = $value ? 10 : 0;
        } else {
            $numericValue = (float) $value;
        }

        // Aplica o peso da métrica
        return ($numericValue * $this->weight) / 100;
    }

    public function validateWeight($totalWeight)
    {
        return $totalWeight <= 100;
    }
}
