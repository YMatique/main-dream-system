<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeePerformanceHistory extends Component
{
 public $employee;
    public $yearFilter;
    public $chartData = [];
    public $performanceMetrics = [];
    public $comparisonData;
    public $trendAnalysis;
    public $portalUser;

    public function mount()
    {
        $this->portalUser = Auth::guard('portal')->user();
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
    }

    private function prepareChartData($evaluations)
    {
        if ($evaluations->isEmpty()) {
            return [];
        }

        return $evaluations->map(function ($evaluation) {
            return [
                'period' => $evaluation->evaluation_period_formatted,
                'percentage' => floatval($evaluation->final_percentage),
                'total_score' => floatval($evaluation->total_score),
                'class' => $evaluation->performance_class,
                'color' => $evaluation->performance_color,
                'date' => $evaluation->evaluation_period->format('M Y')
            ];
        })->toArray();
    }

    private function calculateMetricsPerformance($evaluations)
    {
        if ($evaluations->isEmpty()) {
            return [];
        }

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
                        'weight' => $response->metric->weight ?? 0
                    ];
                }

                $metricsPerformance[$metricName]['scores'][] = [
                    'period' => $evaluation->evaluation_period_formatted,
                    'score' => floatval($response->calculated_score),
                    'display_value' => $response->display_value
                ];
            }
        }

        // Calcular médias e tendências
        foreach ($metricsPerformance as $metric => &$data) {
            $scores = array_column($data['scores'], 'score');
            if (!empty($scores)) {
                $data['average'] = round(array_sum($scores) / count($scores), 2);
                $data['trend'] = $this->calculateTrend($scores);
            }
        }

        return array_values($metricsPerformance);
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

        if (!$myAvg && !$departmentAvg) {
            return null;
        }

        return [
            'department_average' => round($departmentAvg ?? 0, 2),
            'my_average' => round($myAvg ?? 0, 2),
            'difference' => round(($myAvg ?? 0) - ($departmentAvg ?? 0), 2)
        ];
    }

    private function analyzeTrends($evaluations)
    {
        if ($evaluations->count() < 2) {
            return [
                'trend' => 'insufficient_data',
                'change' => 0,
                'description' => 'Dados insuficientes para análise de tendência. Necessário pelo menos 2 avaliações.',
                'consistency' => 'N/A'
            ];
        }

        $scores = $evaluations->pluck('final_percentage')->toArray();

        if (empty($scores) || count($scores) < 2) {
            return [
                'trend' => 'no_data',
                'change' => 0,
                'description' => 'Sem dados suficientes para análise',
                'consistency' => 'N/A'
            ];
        }

        $trend = $this->calculateTrend($scores);
        $first = floatval($evaluations->first()->final_percentage ?? 0);
        $last = floatval($evaluations->last()->final_percentage ?? 0);
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
        if (count($scores) < 2) {
            return 'stable';
        }

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

        $denominator = ($n * $sumXX - $sumX * $sumX);
        if ($denominator == 0) {
            return 'stable';
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / $denominator;

        return match (true) {
            $slope > 2 => 'improving',
            $slope < -2 => 'declining',
            default => 'stable'
        };
    }

    private function getTrendDescription($trend, $change)
    {
        $absChange = abs($change);
        
        return match ($trend) {
            'improving' => "Seu desempenho melhorou {$absChange}% no período analisado. Continue com o excelente trabalho!",
            'declining' => "Houve um declínio de {$absChange}% no seu desempenho. Considere conversar com seu gestor sobre estratégias de melhoria.",
            'stable' => 'Seu desempenho manteve-se estável no período. Consistência é uma qualidade valiosa!',
            'insufficient_data' => 'Dados insuficientes para análise de tendência.',
            default => 'Não foi possível analisar a tendência com os dados disponíveis.'
        };
    }

    private function calculateConsistency($scores)
    {
        if (count($scores) < 2) {
            return 'N/A';
        }

        $mean = array_sum($scores) / count($scores);
        
        if ($mean == 0) {
            return 'N/A';
        }

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
        $years = PerformanceEvaluation::forEmployee($this->employee->id)
            ->selectRaw('YEAR(evaluation_period) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Se não há dados, retornar pelo menos o ano atual
        if ($years->isEmpty()) {
            return collect([now()->year]);
        }

        return $years;
    }
}
