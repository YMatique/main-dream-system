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
    public $accessibleDepartments = [];

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
        $this->checkPermissions();
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

  /**
     * Verificar permissões do usuário - REGRA CORRETA
     */
    public function checkPermissions()
    {
        $user = auth()->user();
        
        // ✅ Company Admin tem acesso total
        if ($user->user_type === 'company_admin') {
            return; // Admin pode avaliar qualquer funcionário
        }
        
        // ✅ Company User precisa de permissão de avaliação
        if (!$user->hasPermission('evaluation.create')) {
            abort(403, 'Sem permissão para criar avaliações de desempenho');
        }
        
        // Verificar se tem pelo menos um departamento atribuído para avaliação
        $accessibleDepartments = $this->getAccessibleDepartments();
        if (empty($accessibleDepartments)) {
            abort(403, 'Usuário não tem departamentos atribuídos para avaliação');
        }
    }

    /**
     * Obter departamentos acessíveis para o usuário - REGRA CORRETA
     */
    public function getAccessibleDepartments()
    {
        $user = auth()->user();
        
        if ($user->user_type === 'company_admin') {
            // ✅ Admin pode avaliar funcionários de todos os departamentos
            return Department::where('company_id', $user->company_id)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
        }

        // ✅ Company User: Verificar departamentos específicos atribuídos via permissões
        
        if (!$user->hasPermission('evaluation.create')) {
            return []; // Sem permissão, sem acesso
        }
        
         // Buscar departamentos atribuídos na tabela department_evaluators
        $departmentIds = \App\Models\DepartmentEvaluator::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->where('is_active', true)
            ->pluck('department_id')
            ->toArray();
        
        return $departmentIds;
        /*
        // Verificar se tem permissões específicas de departamento
        $departmentIds = [];
        
        // Buscar permissões do tipo 'evaluation.department.{id}'
        $userPermissions = $user->userPermissions()
            ->whereHas('permission', function($q) {
                $q->where('name', 'like', 'evaluation.department.%');
            })
            ->with('permission')
            ->get();
            dd($userPermissions);

        foreach ($userPermissions as $userPermission) {
            $permissionName = $userPermission->permission->name;
            // Extrair ID do departamento de 'evaluation.department.{id}'
            if (preg_match('/evaluation\.department\.(\d+)/', $permissionName, $matches)) {
                $departmentIds[] = (int) $matches[1];
            }
        }
        
        // Se não tem permissões específicas de departamento, verificar se tem permissão geral
        if (empty($departmentIds)) {
            // Se tem permissão geral 'evaluation.create', pode avaliar todos os departamentos
            // (isso pode ser configurado conforme sua regra de negócio)
            return Department::where('company_id', $user->company_id)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
        }
        
        return array_unique($departmentIds);
        */
    }

    public function loadData()
    {
        $user = auth()->user();
        $this->accessibleDepartments = $this->getAccessibleDepartments();
        
        if (empty($this->accessibleDepartments)) {
            $this->employees = collect();
            $this->departments = collect();
            return;
        }

        // dd($this->accessibleDepartments);
        // Carregar departamentos acessíveis
        $this->departments = Department::where('company_id', $user->company_id)
            ->whereIn('id', $this->accessibleDepartments)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Carregar funcionários dos departamentos acessíveis
        $this->employees = Employee::where('company_id', $user->company_id)
            ->whereIn('department_id', $this->accessibleDepartments)
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get();
    }

    public function getEvaluations()
    {
        $user = auth()->user();
        
        // Se não tem departamentos acessíveis, retorna vazio
        if (empty($this->accessibleDepartments)) {
            return collect()->paginate($this->perPage);
        }

        $query = PerformanceEvaluation::where('company_id', $user->company_id)
            ->whereHas('employee', function($q) {
                $q->whereIn('department_id', $this->accessibleDepartments);
            })
            ->with(['employee.department', 'evaluator'])
            ->when($this->search, function ($q) {
                $q->whereHas('employee', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('employee_code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->departmentFilter, function ($q) {
                // Verificar se o departamento está nos acessíveis
                if (in_array($this->departmentFilter, $this->accessibleDepartments)) {
                    $q->whereHas('employee', function ($query) {
                        $query->where('department_id', $this->departmentFilter);
                    });
                }
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
        $user = auth()->user();
        
        // Se não tem departamentos acessíveis, retorna zeros
        if (empty($this->accessibleDepartments)) {
            return [
                'total_evaluations' => 0,
                'this_month' => 0,
                'pending_approval' => 0,
                'below_threshold' => 0,
                'average_performance' => 0
            ];
        }
        
        $baseQuery = PerformanceEvaluation::where('company_id', $user->company_id)
            ->whereHas('employee', function($q) {
                $q->whereIn('department_id', $this->accessibleDepartments);
            });
            
        return [
            'total_evaluations' => (clone $baseQuery)->count(),
            'this_month' => (clone $baseQuery)
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->count(),
            'pending_approval' => (clone $baseQuery)
                ->where('status', 'in_approval')
                ->count(),
            'below_threshold' => (clone $baseQuery)
                ->where('is_below_threshold', true)
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->count(),
            'average_performance' => (clone $baseQuery)
                ->where('status', 'approved')
                ->whereYear('evaluation_period', now()->year)
                ->whereMonth('evaluation_period', now()->month)
                ->avg('final_percentage') ?? 0
        ];
    }

    public function createEvaluation()
    {
        // Verificar se tem departamentos acessíveis
        if (empty($this->accessibleDepartments)) {
            session()->flash('error', 'Sem permissão para criar avaliações. Contacte o administrador.');
            return;
        }
        
        $this->resetEvaluationForm();
        $this->showEvaluationModal = true;
    }

    public function selectEmployee()
    {
        if (!$this->selectedEmployeeId) {
            return;
        }

        $employee = Employee::findOrFail($this->selectedEmployeeId);
        
         // ✅ RECARREGAR departamentos acessíveis ANTES da verificação
        $this->accessibleDepartments = $this->getAccessibleDepartments();
        // Verificar se o usuário pode avaliar este funcionário
        if (!$this->canEvaluateEmployee($employee)) {
            dd('ásasas');
            session()->flash('error', 'Sem permissão para avaliar funcionários deste departamento.');
            $this->selectedEmployeeId = null;
            return;
        }
        
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

    /**
     * Verificar se o usuário pode avaliar um funcionário específico - REGRA CORRETA
     */
    public function canEvaluateEmployee($employee)
    {
        $user = auth()->user();
        
        // ✅ Admin pode avaliar qualquer funcionário da empresa
        if ($user->user_type === 'company_admin' && $user->company_id === $employee->company_id) {
            return true;
        }

        // ✅ Company User: verificar se tem permissão para o departamento do funcionário
        

        if (!$user->hasPermission('evaluation.create')) {
            return false;
        }
        // Verificar se o departamento do funcionário está nos departamentos acessíveis
        return in_array($employee->department_id, $this->accessibleDepartments) 
            && $employee->company_id === $user->company_id;
    }

    // ... resto dos métodos permanecem iguais ...

    public function loadDepartmentMetrics()
    {
        if (!$this->selectedDepartmentId) {
            return;
        }
         // ✅ GARANTIR que accessibleDepartments está atualizado
        if (empty($this->accessibleDepartments)) {
            $this->accessibleDepartments = $this->getAccessibleDepartments();
        }

        // dd($this->accessibleDepartments, $this->selectedDepartmentId, in_array($this->selectedDepartmentId, $this->accessibleDepartments));
        // Verificar se pode acessar este departamento
        if (!in_array($this->selectedDepartmentId, $this->accessibleDepartments)) {
            session()->flash('error', 'Sem permissão para acessar métricas deste departamento.');
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

            if ($value !== null && $value !== '') {
                // ✅ CORREÇÃO: Seu model já aplica o peso, só somar
                $score = $metric->calculateScore($value);
                $totalScore += $score;
                $totalWeight += $metric->weight;
            }
        }

        $this->totalScore = $totalScore;
        
        // ✅ CORREÇÃO: totalScore já está ponderado pelo peso
        // Se peso total for 100%, totalScore será o percentual final
        if ($totalWeight > 0) {
            $this->finalPercentage = min(100, $totalScore);
        } else {
            $this->finalPercentage = 0;
        }

        // Debug para verificar
        \Log::info('Score Calculation Debug', [
            'total_score' => $totalScore,
            'total_weight' => $totalWeight,
            'final_percentage' => $this->finalPercentage,
            'responses' => array_map(function($response) {
                return array_filter($response);
            }, $this->responses)
        ]);
    }

    public function saveEvaluation()
    {
        // Validar permissões novamente
        if (!$this->selectedEmployeeId) {
            session()->flash('error', 'Funcionário não selecionado.');
            return;
        }
        
        $employee = Employee::findOrFail($this->selectedEmployeeId);
        if (!$this->canEvaluateEmployee($employee)) {
            session()->flash('error', 'Sem permissão para avaliar este funcionário.');
            return;
        }

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

    // ... métodos restantes permanecem iguais ...

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
            ->whereHas('employee', function($q) {
                $q->whereIn('department_id', $this->accessibleDepartments);
            })
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
