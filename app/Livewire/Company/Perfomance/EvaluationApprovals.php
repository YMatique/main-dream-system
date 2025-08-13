<?php

namespace App\Livewire\Company\Perfomance;

use App\Models\Company\Department;
use App\Models\Company\Evaluation\PerformanceEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationApprovals extends Component
{

    use WithPagination;

    // Modal states
    public $showApprovalModal = false;
    public $showRejectionModal = false;
    public $showBulkModal = false;
    public $showDetailModal = false;

    // Current evaluation being processed
    public $selectedEvaluation = null;
    public $selectedEvaluations = [];

    // Form inputs
    public $approvalComments = '';
    public $rejectionComments = '';
    public $bulkComments = '';

    // Filters
    public $departmentFilter = '';
    public $statusFilter = 'submitted'; // Mudança: submitted em vez de in_approval
    public $monthFilter = '';
    public $yearFilter = '';
    public $thresholdFilter = 'all';
    public $search = '';
    public $perPage = 15;

    // Data
    public $departments = [];
    public $stats = [];

    protected $queryString = [
        'departmentFilter' => ['except' => ''],
        'statusFilter' => ['except' => 'submitted'],
        'monthFilter' => ['except' => ''],
        'yearFilter' => ['except' => ''],
        'thresholdFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $rules = [
        'approvalComments' => 'nullable|string|max:1000',
        'rejectionComments' => 'required|string|max:1000',
        'bulkComments' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        // Verificar permissões
        abort_unless(
            auth()->user()->isCompanyAdmin() || auth()->user()->hasPermission('evaluation.approve'), 
            403, 
            'Sem permissão para aprovar avaliações'
        );

        $this->loadInitialData();
    }

    public function loadInitialData()
    {
        $this->departments = Department::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->yearFilter = $this->yearFilter ?: now()->year;
        $this->monthFilter = $this->monthFilter ?: now()->month;

        $this->calculateStats();
    }

    // public function render()
    // {
    //     $evaluations = $this->getEvaluationsQuery()->paginate($this->perPage);

    //     return view('livewire.company.performance.evaluation-approvals', [
    //         'evaluations' => $evaluations,
    //         'months' => $this->getMonthsArray(),
    //         'years' => $this->getYearsArray(),
    //     ])->layout('layouts.company');
    // }

    protected function getEvaluationsQuery()
    {
        $query = PerformanceEvaluation::with([
            'employee:id,name,code,department_id',
            'employee.department:id,name',
            'evaluator:id,name',
            'approvedBy:id,name',
            'rejectedBy:id,name'
        ])
        ->where('company_id', auth()->user()->company_id);

        // Filtros
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->departmentFilter) {
            $query->whereHas('employee', function($q) {
                $q->where('department_id', $this->departmentFilter);
            });
        }

        if ($this->yearFilter) {
            $query->whereYear('evaluation_period', $this->yearFilter);
        }

        if ($this->monthFilter) {
            $query->whereMonth('evaluation_period', $this->monthFilter);
        }

        if ($this->thresholdFilter === 'below_threshold') {
            $query->where('is_below_threshold', true);
        } elseif ($this->thresholdFilter === 'above_threshold') {
            $query->where('is_below_threshold', false);
        }

        if ($this->search) {
            $query->whereHas('employee', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('is_below_threshold', 'desc')
                    ->orderBy('submitted_at', 'asc');
    }

    public function calculateStats()
    {
        $baseQuery = PerformanceEvaluation::where('company_id', auth()->user()->company_id);
        
        if ($this->yearFilter) {
            $baseQuery->whereYear('evaluation_period', $this->yearFilter);
        }
        
        if ($this->monthFilter) {
            $baseQuery->whereMonth('evaluation_period', $this->monthFilter);
        }

        $this->stats = [
            'total_pending' => (clone $baseQuery)->where('status', 'submitted')->count(),
            'below_threshold' => (clone $baseQuery)->where('is_below_threshold', true)->where('status', 'submitted')->count(),
            'total_approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'total_rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'avg_approval_time' => $this->calculateAverageApprovalTime(),
        ];
    }

    // ===== APPROVAL ACTIONS - SIMPLIFICADO =====

    public function openApprovalModal($evaluationId)
    {
        Log::info('🔍 DEBUG: Abrindo modal de aprovação', ['evaluation_id' => $evaluationId]);

        $this->selectedEvaluation = PerformanceEvaluation::with([
            'employee',
            'employee.department',
            'evaluator',
            'responses.metric'
        ])->findOrFail($evaluationId);

        $this->approvalComments = '';
        $this->showApprovalModal = true;
    }

    public function approveEvaluation()
    {
        Log::info('🔍 DEBUG: Aprovando avaliação', [
            'user_id' => auth()->id(),
            'evaluation_id' => $this->selectedEvaluation?->id,
            'comments' => $this->approvalComments
        ]);

        $this->validate(['approvalComments' => 'nullable|string|max:1000']);

        if (!$this->selectedEvaluation) {
            session()->flash('error', 'Nenhuma avaliação selecionada');
            return;
        }

        try {
            DB::transaction(function() {
                $this->selectedEvaluation->approve(auth()->id(), $this->approvalComments);
            });

            $this->showApprovalModal = false;
            $this->calculateStats();
            
            session()->flash('success', 'Avaliação aprovada com sucesso!');
            
            Log::info('✅ DEBUG: Avaliação aprovada com sucesso', [
                'evaluation_id' => $this->selectedEvaluation->id
            ]);

        } catch (\Exception $e) {
            Log::error('❌ DEBUG: Erro na aprovação', [
                'error' => $e->getMessage(),
                'evaluation_id' => $this->selectedEvaluation->id
            ]);
            
            session()->flash('error', 'Erro ao aprovar avaliação: ' . $e->getMessage());
        }
    }

    public function openRejectionModal($evaluationId)
    {
        $this->selectedEvaluation = PerformanceEvaluation::with(['employee', 'evaluator'])
            ->findOrFail($evaluationId);

        $this->rejectionComments = '';
        $this->showRejectionModal = true;
    }

    public function rejectEvaluation()
    {
        $this->validate(['rejectionComments' => 'required|string|max:1000']);

        if (!$this->selectedEvaluation) {
            session()->flash('error', 'Nenhuma avaliação selecionada');
            return;
        }

        try {
            DB::transaction(function() {
                $this->selectedEvaluation->reject(auth()->id(), $this->rejectionComments);
            });

            $this->showRejectionModal = false;
            $this->calculateStats();
            
            session()->flash('success', 'Avaliação rejeitada. O avaliador foi notificado.');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao rejeitar avaliação: ' . $e->getMessage());
        }
    }

    // ===== BULK ACTIONS =====

    public function openBulkModal()
    {
        if (empty($this->selectedEvaluations)) {
            session()->flash('error', 'Selecione pelo menos uma avaliação.');
            return;
        }

        $this->bulkComments = '';
        $this->showBulkModal = true;
    }

    public function bulkApprove()
    {
        $this->validate(['bulkComments' => 'nullable|string|max:500']);

        if (empty($this->selectedEvaluations)) {
            session()->flash('error', 'Selecione pelo menos uma avaliação.');
            return;
        }

        $approvedCount = 0;
        $errors = [];

        DB::transaction(function() use (&$approvedCount, &$errors) {
            foreach ($this->selectedEvaluations as $evaluationId) {
                try {
                    $evaluation = PerformanceEvaluation::findOrFail($evaluationId);
                    $evaluation->approve(auth()->id(), $this->bulkComments);
                    $approvedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Erro na avaliação ID {$evaluationId}: " . $e->getMessage();
                }
            }
        });

        $this->showBulkModal = false;
        $this->selectedEvaluations = [];
        $this->calculateStats();

        if ($approvedCount > 0) {
            session()->flash('success', "{$approvedCount} avaliação(ões) aprovada(s) com sucesso!");
        }

        if (!empty($errors)) {
            session()->flash('error', 'Alguns erros ocorreram: ' . implode('; ', $errors));
        }
    }

    // ===== DETAIL VIEW =====

    public function showEvaluationDetail($evaluationId)
    {
        $this->selectedEvaluation = PerformanceEvaluation::with([
            'employee',
            'employee.department',
            'evaluator',
            'responses.metric',
            'approvedBy',
            'rejectedBy'
        ])->findOrFail($evaluationId);

        // dd($this->selectedEvaluation->approvals);
        $this->showDetailModal = true;
    }

    // ===== FILTER METHODS =====

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }

    public function updatedThresholdFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }

    public function clearFilters()
    {
        $this->departmentFilter = '';
        $this->statusFilter = 'submitted';
        $this->monthFilter = now()->month;
        $this->yearFilter = now()->year;
        $this->thresholdFilter = 'all';
        $this->search = '';
        $this->resetPage();
        $this->calculateStats();
    }

    // ===== MODAL CLOSERS =====

    public function closeApprovalModal()
    {
        $this->showApprovalModal = false;
        $this->selectedEvaluation = null;
        $this->approvalComments = '';
    }

    public function closeRejectionModal()
    {
        $this->showRejectionModal = false;
        $this->selectedEvaluation = null;
        $this->rejectionComments = '';
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->bulkComments = '';
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedEvaluation = null;
    }

    // ===== HELPER METHODS =====

    protected function getMonthsArray()
    {
        return [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];
    }

    protected function getYearsArray()
    {
        $currentYear = now()->year;
        $years = [];
        for ($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    protected function calculateAverageApprovalTime()
    {
        $approvedEvaluations = PerformanceEvaluation::where('company_id', auth()->user()->company_id)
            ->where('status', 'approved')
            ->whereNotNull('submitted_at')
            ->whereNotNull('approved_at')
            ->get(['submitted_at', 'approved_at']);

        if ($approvedEvaluations->isEmpty()) {
            return 'N/A';
        }

        $totalHours = 0;
        foreach ($approvedEvaluations as $evaluation) {
            $totalHours += $evaluation->submitted_at->diffInHours($evaluation->approved_at);
        }

        $avgHours = $totalHours / $approvedEvaluations->count();
        
        if ($avgHours < 24) {
            return round($avgHours, 1) . 'h';
        } else {
            return round($avgHours / 24, 1) . 'd';
        }
    }
    public function render()
    {
        $evaluations = $this->getEvaluationsQuery()->paginate($this->perPage);

        return view('livewire.company.perfomance.evaluation-approvals', [
            'evaluations' => $evaluations,
            'months' => $this->getMonthsArray(),
            'years' => $this->getYearsArray(),
        ])->layout('layouts.company');
    }


    // public function render()
    // {
    //     return view('livewire.company.perfomance.evaluation-approvals');
    // }
}
