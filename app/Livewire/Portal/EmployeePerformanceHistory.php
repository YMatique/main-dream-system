<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeePerformanceHistory extends Component
{
    public $employee;
    public $yearFilter;
    public $chartData;
    public $performanceMetrics;
    public $comparisonData;
    public $trendAnalysis;
    public $portalUser;

    public function mount()
    {
        $this->portalUser = Auth::guard('employee_portal')->user();
        $this->employee = $this->portalUser->employee;

        if (!$this->employee) {
            abort(403, 'Funcionário não encontrado.');
        }

        $this->yearFilter = now()->year;
        $this->loadPerformanceData();
    }

    public function updatedYearFilter()
    {
        $this->loadPerformanceData();
    }

    public function loadPerformanceData()
    {
        $evaluations = PerformanceEvaluation::forEmployee($this->employee->id)
            ->byStatus('approved')
            ->forPeriod($this->yearFilter)
            ->with('responses.metric')
            ->orderBy('evaluation_period')
            ->get();

        $this->chartData = $this->prepareChartData($evaluations);
        $this->performanceMetrics = $this->calculateMetricsPerformance($evaluations);
        $this->comparisonData = $this->prepareComparisonData();
        $this->trendAnalysis = $this->analyzeTrends($evaluations);
        // dd($this->trendAnalysis);
    }

    private function prepareChartData($evaluations)
    {
        return $evaluations->map(function ($evaluation) {
            return [
                'period' => $evaluation->evaluation_period_formatted,
                'percentage' => $evaluation->final_percentage,
                'total_score' => $evaluation->total_score,
                'class' => $evaluation->performance_class,
                'color' => $evaluation->performance_color,
                'date' => $evaluation->evaluation_period->format('M Y')
            ];
        })->toArray();
    }

    private function calculateMetricsPerformance($evaluations)
    {
        $metricsPerformance = [];

        foreach ($evaluations as $evaluation) {
            foreach ($evaluation->responses as $response) {
                $metricName = $response->metric->name;

                if (!isset($metricsPerformance[$metricName])) {
                    $metricsPerformance[$metricName] = [
                        'name' => $metricName,
                        'scores' => [],
                        'average' => 0,
                        'trend' => 'stable',
                        'weight' => $response->metric->weight
                    ];
                }

                $metricsPerformance[$metricName]['scores'][] = [
                    'period' => $evaluation->evaluation_period_formatted,
                    'score' => $response->calculated_score,
                    'display_value' => $response->display_value
                ];
            }
        }

        // Calcular médias e tendências
        foreach ($metricsPerformance as $metric => &$data) {
            $scores = array_column($data['scores'], 'score');
            $data['average'] = round(array_sum($scores) / count($scores), 2);
            $data['trend'] = $this->calculateTrend($scores);
        }

        return $metricsPerformance;
    }

    private function prepareComparisonData()
    {
        // Comparar com média do departamento
        $departmentAvg = PerformanceEvaluation::whereHas('employee', function ($query) {
            $query->where('department_id', $this->employee->department_id)
                ->where('company_id', $this->employee->company_id);
        })
            ->byStatus('approved')
            ->forPeriod($this->yearFilter)
            ->avg('final_percentage');

        $myAvg = PerformanceEvaluation::forEmployee($this->employee->id)
            ->byStatus('approved')
            ->forPeriod($this->yearFilter)
            ->avg('final_percentage');

        return [
            'department_average' => round($departmentAvg ?? 0, 2),
            'my_average' => round($myAvg ?? 0, 2),
            'difference' => round(($myAvg ?? 0) - ($departmentAvg ?? 0), 2)
        ];
    }

    private function analyzeTrends($evaluations)
    {
        // Garantir que sempre retorna um array válido
        if ($evaluations->count() < 2) {
            return [
                'trend' => 'insufficient_data',
                'change' => 0,
                'description' => 'Dados insuficientes para análise',
                'consistency' => 'N/A'
            ];
        }

        $scores = $evaluations->pluck('final_percentage')->toArray();

        // Verificar se há scores válidos
        if (empty($scores) || count($scores) < 2) {
            return [
                'trend' => 'no_data',
                'change' => 0,
                'description' => 'Sem dados suficientes',
                'consistency' => 'N/A'
            ];
        }

        $trend = $this->calculateTrend($scores);

        $first = $evaluations->first()->final_percentage ?? 0;
        $last = $evaluations->last()->final_percentage ?? 0;
        $change = $last - $first;

        return [
            'trend' => $trend,
            'change' => round($change, 2),
            'description' => $this->getTrendDescription($trend, $change),
            'consistency' => $this->calculateConsistency($scores)
        ];
    }

    private function calculateTrend($scores)
    {
        if (count($scores) < 2) return 'stable';

        // Calcular tendência usando regressão linear simples
        $n = count($scores);
        $x = range(1, $n);
        $sumX = array_sum($x);
        $sumY = array_sum($scores);
        $sumXY = 0;
        $sumXX = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $scores[$i];
            $sumXX += $x[$i] * $x[$i];
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);

        return match (true) {
            $slope > 2 => 'improving',
            $slope < -2 => 'declining',
            default => 'stable'
        };
    }

    private function getTrendDescription($trend, $change)
    {
        return match ($trend) {
            'improving' => "Desempenho em melhoria (+{$change}%)",
            'declining' => "Desempenho em declínio ({$change}%)",
            'stable' => 'Desempenho estável',
            default => 'Dados insuficientes'
        };
    }

    private function calculateConsistency($scores)
    {
        if (count($scores) < 2) return 'N/A';

        $mean = array_sum($scores) / count($scores);
        $variance = array_sum(array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $scores)) / count($scores);

        $stdDev = sqrt($variance);
        $cv = ($stdDev / $mean) * 100; // Coeficiente de variação

        return match (true) {
            $cv < 10 => 'Muito Consistente',
            $cv < 20 => 'Consistente',
            $cv < 30 => 'Moderadamente Variável',
            default => 'Muito Variável'
        };
    }
    public function render()
    {
        return view('livewire.portal.employee-performance-history', [
            'availableYears' => $this->getAvailableYears()
        ])->layout('layouts.portal');
    }
    private function getAvailableYears()
    {
        return PerformanceEvaluation::forEmployee($this->employee->id)
            ->selectRaw('YEAR(evaluation_period) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
    }
}
