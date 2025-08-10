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
 /**
     * ✅ MÉTODO PRINCIPAL CORRIGIDO
     */
    public function calculateScore($value)
    {
        // Primeiro, calcular o score base (0-10)
        $baseScore = $this->getBaseScore($value);
        
        // Depois, aplicar o peso da métrica
        $weightedScore = ($baseScore * $this->weight) / 100;
        
        return $weightedScore;
    }
    
    /**
     * ✅ MÉTODO AUXILIAR CORRIGIDO
     */
    public function getBaseScore($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        switch ($this->type) {
            case 'rating':
                return $this->calculateRatingScore($value);
            
            case 'boolean':
                return $this->calculateBooleanScore($value);
            
            case 'numeric':
                return $this->calculateNumericScore($value);
            
            default:
                return 0;
        }
    }

    /**
     * Calcular score para tipo rating
     */
    private function calculateRatingScore($value)
    {
        $options = $this->rating_options ?? $this->default_rating_options;
        
        if (empty($options)) {
            return 0;
        }
        
        $index = array_search($value, $options);
        if ($index === false) {
            return 0;
        }
        
        $maxIndex = count($options) - 1;
        if ($maxIndex <= 0) {
            return 10; // Se só há uma opção, considera máximo
        }
        
        // Converter índice para score 0-10
        // Péssimo (0) = 0, Satisfatório (1) = 3.33, Bom (2) = 6.67, Excelente (3) = 10
        return ($index / $maxIndex) * 10;
    }

    /**
     * Calcular score para tipo boolean
     */
    private function calculateBooleanScore($value)
    {
        // Converter para boolean se necessário
        if (is_string($value)) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        
        return $value ? 10 : 0;
    }

    /**
     * Calcular score para tipo numeric
     */
    private function calculateNumericScore($value)
    {
        $numericValue = (float) $value;
        
        // Garantir que está dentro dos limites
        $numericValue = max($this->min_value, min($this->max_value, $numericValue));
        
        // Se os valores já estão em escala 0-10, usar direto
        if ($this->min_value >= 0 && $this->max_value <= 10) {
            return $numericValue;
        }
        
        // Senão, normalizar para escala 0-10
        $range = $this->max_value - $this->min_value;
        if ($range <= 0) {
            return 10; // Se não há range, considera máximo
        }
        
        $normalizedValue = ($numericValue - $this->min_value) / $range;
        return $normalizedValue * 10;
    }

    /**
     * Validar configuração das métricas do departamento
     */
    public static function validateDepartmentMetrics($departmentId, $companyId)
    {
        $metrics = static::where('department_id', $departmentId)
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->get();

        if ($metrics->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'Nenhuma métrica configurada para este departamento.'
            ];
        }

        $totalWeight = $metrics->sum('weight');
        
        if ($totalWeight !== 100) {
            return [
                'valid' => false,
                'message' => "Peso total das métricas deve ser 100%. Atual: {$totalWeight}%"
            ];
        }

        // Verificar métricas de rating
        foreach ($metrics as $metric) {
            if ($metric->type === 'rating') {
                $options = $metric->rating_options ?? [];
                if (empty($options)) {
                    return [
                        'valid' => false,
                        'message' => "Métrica '{$metric->name}' do tipo rating não possui opções configuradas."
                    ];
                }
            }
        }

        return [
            'valid' => true,
            'message' => 'Configuração válida.'
        ];
    }
    public function validateWeight($totalWeight)
    {
        return $totalWeight <= 100;
    }
}
