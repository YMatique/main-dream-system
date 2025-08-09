<?php

namespace App\Livewire\Company\Perfomance;

use App\Models\Company\Department;
use App\Models\Company\Employee;
use App\Models\Company\Evaluation\PerformanceEvaluation;
use App\Models\Company\Evaluation\PerformanceMetric;
use App\Services\PerformanceEvaluationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showEvaluationModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;

    // Evaluation management
    public $selectedEmployeeId = null;
    public $selectedDepartmentId = null;
    public $evaluationPeriod = '';
    public $recommendations = '';
    public $currentEvaluationId = null;
    public $viewingEvaluationId = null;
    public $deleteEvaluationId = null;

    // Form data
    public $responses = [];
    public $metrics = [];
    public $totalScore = 0;
    public $finalPercentage = 0;

    // Filters
    public $search = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $periodFilter = '';
    public $perPage = 15;

    // Data collections
    public $employees = [];
    public $departments = [];

    protected $evaluationService;

    public function boot(PerformanceEvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    protected function rules()
    {
        $rules = [
            'selectedEmployeeId' => 'required|exists:employees,id',
            'evaluationPeriod' => 'required|date',
            'recommendations' => 'required|string|min:10|max:1000',
        ];

        // Validação dinâmica das respostas baseada nas métricas
        foreach ($this->metrics as $metric) {
            if ($metric->type === 'numeric') {
                $rules["responses.{$metric->id}.numeric_value"] = "required|numeric|min:{$metric->min_value}|max:{$metric->max_value}";
            } elseif ($metric->type === 'rating') {
                $rules["responses.{$metric->id}.rating_value"] = 'required|string';
            } elseif ($metric->type === 'boolean') {
                $rules["responses.{$metric->id}.numeric_value"] = 'required|boolean';
            }
        }

        return $rules;
    }

    protected $messages = [
        'selectedEmployeeId.required' => 'Selecione um funcionário.',
        'evaluationPeriod.required' => 'Selecione o período de avaliação.',
        'recommendations.required' => 'As recomendações são obrigatórias.',
        'recommendations.min' => 'As recomendações devem ter pelo menos 10 caracteres.',
        'responses.*.numeric_value.required' => 'Este campo é obrigatório.',
        'responses.*.rating_value.required' => 'Selecione uma opção.',
    ];

    public function mount()
    {
        $this->loadData();
        $this->evaluationPeriod = now()->format('Y-m');
    }

    public function render()
    {
        $evaluations = $this->getEvaluations();
        
        return view('livewire.company.perfomance.evaluation-management', [
            'evaluations' => $evaluations,
            'stats' => $this->getStats(),
        ])
        ->title('Avaliações de Desempenho')
        ->layout('layouts.company');
    }

    public function loadData()
    {
        $companyId = auth()->user()->company_id;
        
        $this->departments = Department::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->employees = Employee::where('company_id', $companyId)
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get();
    }

    public function getEvaluations()
    {
        $query = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->with(['employee.department', 'evaluator'])
            ->when($this->search, function ($q) {
                $q->whereHas('employee', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('employee_code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->departmentFilter, function ($q) {
                $q->whereHas('employee', function ($query) {
                    $query->where('department_id', $this->departmentFilter);
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->periodFilter, function ($q) {
                $date = Carbon::parse($this->periodFilter);
                $q->whereYear('evaluation_period', $date->year)
                  ->whereMonth('evaluation_period', $date->month);
            });

        return $query->orderBy('evaluation_period', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate($this->perPage);
    }

    public function getStats()
    {
        $companyId = auth()->user()->company_id;
        $currentMonth = now()->format('Y-m');
        
        return [
            'total_evaluations' => PerformanceEvaluation::where('company_id', $companyId)->count(),
            'this_month' => PerformanceEvaluation::where('company_id', $companyId)
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->count(),
            'pending_approval' => PerformanceEvaluation::where('company_id', $companyId)
                ->where('status', 'in_approval')
                ->count(),
            'below_threshold' => PerformanceEvaluation::where('company_id', $companyId)
                ->where('is_below_threshold', true)
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->count(),
            'average_performance' => PerformanceEvaluation::where('company_id', $companyId)
                ->where('status', 'approved')
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->avg('final_percentage') ?? 0
        ];
    }

    public function createEvaluation()
    {
        $this->resetEvaluationForm();
        $this->showEvaluationModal = true;
    }

    public function selectEmployee()
    {
        if (!$this->selectedEmployeeId) {
            return;
        }

        $employee = Employee::findOrFail($this->selectedEmployeeId);
        $this->selectedDepartmentId = $employee->department_id;
        
        // Verificar se já existe avaliação para este período
        $period = Carbon::parse($this->evaluationPeriod);
        $existing = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->where('employee_id', $this->selectedEmployeeId)
            ->whereYear('evaluation_period', $period->year)
            ->whereMonth('evaluation_period', $period->month)
            ->first();

        if ($existing) {
            session()->flash('error', 'Já existe uma avaliação para este funcionário neste período.');
            return;
        }

        // Carregar métricas do departamento
        $this->loadDepartmentMetrics();
    }

    public function loadDepartmentMetrics()
    {
        if (!$this->selectedDepartmentId) {
            return;
        }

        $this->metrics = PerformanceMetric::where('company_id', auth()->user()->company_id)
            ->where('department_id', $this->selectedDepartmentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Validar se as métricas estão configuradas corretamente
        $validation = $this->evaluationService->validateDepartmentMetrics(
            $this->selectedDepartmentId, 
            auth()->user()->company_id
        );

        if (!$validation['valid']) {
            session()->flash('error', $validation['message']);
            $this->metrics = collect();
            return;
        }

        // Inicializar respostas vazias
        $this->responses = [];
        foreach ($this->metrics as $metric) {
            $this->responses[$metric->id] = [
                'numeric_value' => null,
                'rating_value' => null,
                'comments' => ''
            ];
        }
    }

    public function calculateScore()
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($this->metrics as $metric) {
            $response = $this->responses[$metric->id] ?? null;
            if (!$response) continue;

            $value = null;
            if ($metric->type === 'numeric') {
                $value = $response['numeric_value'];
            } elseif ($metric->type === 'rating') {
                $value = $response['rating_value'];
            } elseif ($metric->type === 'boolean') {
                $value = $response['numeric_value'];
            }

            if ($value !== null) {
                $score = $metric->calculateScore($value);
                $totalScore += $score;
                $totalWeight += $metric->weight;
            }
        }

        $this->totalScore = $totalScore;
        $this->finalPercentage = $totalWeight > 0 ? min(100, $totalScore) : 0;
    }

    public function saveEvaluation()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Criar avaliação
                $evaluation = $this->evaluationService->createEvaluation(
                    $this->selectedEmployeeId,
                    auth()->id(),
                    $this->evaluationPeriod
                );

                // Salvar respostas
                $this->evaluationService->saveEvaluationResponses(
                    $evaluation->id,
                    $this->responses
                );

                // Submeter para aprovação
                $this->evaluationService->submitEvaluation(
                    $evaluation->id,
                    $this->recommendations
                );

                $this->currentEvaluationId = $evaluation->id;
            });

            session()->flash('success', 'Avaliação criada e submetida para aprovação com sucesso!');
            $this->closeEvaluationModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar avaliação: ' . $e->getMessage());
        }
    }

    public function viewEvaluation($evaluationId)
    {
        $this->viewingEvaluationId = $evaluationId;
        $this->showViewModal = true;
    }

    public function editEvaluation($evaluationId)
    {
        $evaluation = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->findOrFail($evaluationId);

        if (!$evaluation->canBeEdited()) {
            session()->flash('error', 'Esta avaliação não pode mais ser editada.');
            return;
        }

        $this->currentEvaluationId = $evaluationId;
        $this->selectedEmployeeId = $evaluation->employee_id;
        $this->selectedDepartmentId = $evaluation->employee->department_id;
        $this->evaluationPeriod = $evaluation->evaluation_period->format('Y-m');
        $this->recommendations = $evaluation->recommendations;

        // Carregar métricas e respostas existentes
        $this->loadDepartmentMetrics();
        
        foreach ($evaluation->responses as $response) {
            $this->responses[$response->metric_id] = [
                'numeric_value' => $response->numeric_value,
                'rating_value' => $response->rating_value,
                'comments' => $response->comments ?? ''
            ];
        }

        $this->calculateScore();
        $this->showEvaluationModal = true;
    }

    public function confirmDeleteEvaluation($evaluationId)
    {
        $this->deleteEvaluationId = $evaluationId;
        $this->showDeleteModal = true;
    }

    public function deleteEvaluation()
    {
        try {
            $evaluation = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteEvaluationId);

            if (!$evaluation->canBeEdited()) {
                session()->flash('error', 'Esta avaliação não pode ser eliminada.');
                return;
            }

            $evaluation->delete();
            session()->flash('success', 'Avaliação eliminada com sucesso!');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar avaliação: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteEvaluationId = null;
    }

    // Helper methods
    public function resetEvaluationForm()
    {
        $this->currentEvaluationId = null;
        $this->selectedEmployeeId = null;
        $this->selectedDepartmentId = null;
        $this->evaluationPeriod = now()->format('Y-m');
        $this->recommendations = '';
        $this->responses = [];
        $this->metrics = collect();
        $this->totalScore = 0;
        $this->finalPercentage = 0;
        $this->resetValidation();
    }

    public function closeEvaluationModal()
    {
        $this->showEvaluationModal = false;
        $this->resetEvaluationForm();
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingEvaluationId = null;
    }

    public function getEvaluationForViewing()
    {
        if (!$this->viewingEvaluationId) {
            return null;
        }

        return PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->with(['employee.department', 'evaluator', 'responses.metric'])
            ->findOrFail($this->viewingEvaluationId);
    }

    // Lifecycle hooks
    public function updatedSelectedEmployeeId()
    {
        $this->selectEmployee();
    }

    public function updatedEvaluationPeriod()
    {
        if ($this->selectedEmployeeId) {
            $this->selectEmployee();
        }
    }

    public function updatedResponses()
    {
        $this->calculateScore();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPeriodFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    // public function render()
    // {
    //     return view('livewire.company.perfomance.evaluation-management');
    // }
}
