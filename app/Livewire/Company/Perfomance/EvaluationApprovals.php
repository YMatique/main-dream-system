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
    public $statusFilter = 'pending_for_me'; // NOVO: filtro específico
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
        'statusFilter' => ['except' => 'pending_for_me'],
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

    /**
     * NOVA QUERY - FILTRO MULTI-ESTÁGIO
     */
    protected function getEvaluationsQuery()
    {
        $query = PerformanceEvaluation::with([
            'employee:id,name,code,department_id',
            'employee.department:id,name',
            'evaluator:id,name',
            'approvedBy:id,name',
            'rejectedBy:id,name',
            // 'currentStageApproval.approver:id,name', // NOVO
            'approvals.approver:id,name' // NOVO
        ])
            ->where('company_id', auth()->user()->company_id);

        // FILTRO PRINCIPAL POR STATUS
        switch ($this->statusFilter) {
            case 'pending_for_me':
                // Só avaliações onde EU sou o aprovador do estágio atual
                $query->pendingForApprover(auth()->id());
                break;

            case 'in_approval':
                // Todas em processo de aprovação
                $query->where('status', 'in_approval');
                break;

            case 'submitted':
                // Antigas submetidas (compatibilidade)
                $query->where('status', 'submitted');
                break;

            case 'approved':
                $query->where('status', 'approved');
                break;

            case 'rejected':
                $query->where('status', 'rejected');
                break;

            default:
                // Todos os status
                break;
        }

        // Outros filtros
        if ($this->departmentFilter) {
            $query->whereHas('employee', function ($q) {
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
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('is_below_threshold', 'desc')
            ->orderBy('submitted_at', 'asc');
    }

    /**
     * NOVAS ESTATÍSTICAS - MULTI-ESTÁGIO
     */
    public function calculateStats()
    {
        $userId = auth()->id();
        $companyId = auth()->user()->company_id;
        $baseQuery = PerformanceEvaluation::where('company_id', $companyId);

        if ($this->yearFilter) {
            $baseQuery->whereYear('evaluation_period', $this->yearFilter);
        }

        if ($this->monthFilter) {
            $baseQuery->whereMonth('evaluation_period', $this->monthFilter);
        }

        $this->stats = [
            // Pendentes para MIM especificamente
            'pending_for_me' => (clone $baseQuery)->pendingForApprover($userId)->count(),

            // Total em processo de aprovação
            'total_in_approval' => (clone $baseQuery)->where('status', 'in_approval')->count(),

            // Abaixo do threshold E pendentes para mim
            'critical_for_me' => (clone $baseQuery)
                ->pendingForApprover($userId)
                ->where('is_below_threshold', true)
                ->count(),

            // Total aprovadas
            'total_approved' => (clone $baseQuery)->where('status', 'approved')->count(),

            // Total rejeitadas
            'total_rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),

            // Tempo médio de aprovação
            'avg_approval_time' => $this->calculateAverageApprovalTime(),
        ];
    }

    // ===== APPROVAL ACTIONS - MULTI-ESTÁGIO =====

    public function openApprovalModal($evaluationId)
    {
        // Log::info('🔍 DEBUG: Abrindo modal de aprovação multi-estágio', ['evaluation_id' => $evaluationId]);

        $this->selectedEvaluation = PerformanceEvaluation::with([
            'employee',
            'employee.department',
            'employee.repairOrderForm2Employees',
            'evaluator',
            'responses.metric',
            // 'currentStageApproval',
            'approvals.approver'
        ])->findOrFail($evaluationId);

        // Verificar se usuário pode aprovar estágio atual
        if (!$this->selectedEvaluation->canUserApproveCurrentStage(auth()->id())) {
            session()->flash('error', 'Você não tem permissão para aprovar este estágio.');
            return;
        }

        $this->approvalComments = '';
        $this->showApprovalModal = true;
    }

    public function approveEvaluation()
    {
        Log::info('🔍 DEBUG: Aprovando estágio atual', [
            'user_id' => auth()->id(),
            'evaluation_id' => $this->selectedEvaluation?->id,
            'current_stage' => $this->selectedEvaluation?->current_stage_number,
            'comments' => $this->approvalComments
        ]);

        $this->validate(['approvalComments' => 'nullable|string|max:1000']);

        if (!$this->selectedEvaluation) {
            session()->flash('error', 'Nenhuma avaliação selecionada');
            return;
        }

        try {
            DB::transaction(function () {
                $this->selectedEvaluation->approveCurrentStage(auth()->id(), $this->approvalComments);
            });

            $this->showApprovalModal = false;
            $this->calculateStats();

            // Verificar se foi aprovação final ou avançou estágio
            if ($this->selectedEvaluation->fresh()->status === 'approved') {
                session()->flash('success', 'Avaliação aprovada definitivamente!');
            } else {
                session()->flash('success', 'Estágio aprovado! Avaliação avançou para próximo aprovador.');
            }

            Log::info('✅ DEBUG: Estágio aprovado com sucesso', [
                'evaluation_id' => $this->selectedEvaluation->id,
                'new_status' => $this->selectedEvaluation->fresh()->status
            ]);
        } catch (\Exception $e) {
            Log::error('❌ DEBUG: Erro na aprovação do estágio', [
                'error' => $e->getMessage(),
                'evaluation_id' => $this->selectedEvaluation->id
            ]);

            session()->flash('error', 'Erro ao aprovar estágio: ' . $e->getMessage());
        }
    }

    public function openRejectionModal($evaluationId)
    {
        $this->selectedEvaluation = PerformanceEvaluation::with([
            'employee',
            'evaluator',
            'approvals.approver'
            // 'currentStageApproval'
        ])->findOrFail($evaluationId);

        // Verificar se usuário pode rejeitar estágio atual
        if (!$this->selectedEvaluation->canUserApproveCurrentStage(auth()->id())) {
            session()->flash('error', 'Você não tem permissão para rejeitar este estágio.');
            return;
        }

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
            DB::transaction(function () {
                $this->selectedEvaluation->rejectAtCurrentStage(auth()->id(), $this->rejectionComments);
            });

            $this->showRejectionModal = false;
            $this->calculateStats();

            session()->flash('success', 'Avaliação rejeitada. O avaliador foi notificado.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao rejeitar avaliação: ' . $e->getMessage());
        }
    }

    // ===== BULK ACTIONS - MULTI-ESTÁGIO =====

    public function openBulkModal()
    {
        if (empty($this->selectedEvaluations)) {
            session()->flash('error', 'Selecione pelo menos uma avaliação.');
            return;
        }

        // Verificar se todas as avaliações selecionadas podem ser aprovadas pelo usuário
        $canApproveAll = true;
        foreach ($this->selectedEvaluations as $evaluationId) {
            $evaluation = PerformanceEvaluation::find($evaluationId);
            if (!$evaluation || !$evaluation->canUserApproveCurrentStage(auth()->id())) {
                $canApproveAll = false;
                break;
            }
        }

        if (!$canApproveAll) {
            session()->flash('error', 'Você não tem permissão para aprovar todas as avaliações selecionadas.');
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
        $advancedCount = 0;
        $errors = [];

        DB::transaction(function () use (&$approvedCount, &$advancedCount, &$errors) {
            foreach ($this->selectedEvaluations as $evaluationId) {
                try {
                    $evaluation = PerformanceEvaluation::findOrFail($evaluationId);

                    if (!$evaluation->canUserApproveCurrentStage(auth()->id())) {
                        $errors[] = "Sem permissão para aprovar: {$evaluation->employee->name}";
                        continue;
                    }

                    $wasAtLastStage = $evaluation->isAtLastStage();
                    $evaluation->approveCurrentStage(auth()->id(), $this->bulkComments);

                    if ($wasAtLastStage) {
                        $approvedCount++;
                    } else {
                        $advancedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Erro na avaliação ID {$evaluationId}: " . $e->getMessage();
                }
            }
        });

        $this->showBulkModal = false;
        $this->selectedEvaluations = [];
        $this->calculateStats();

        // Mensagens de sucesso
        $messages = [];
        if ($approvedCount > 0) {
            $messages[] = "{$approvedCount} avaliação(ões) aprovada(s) definitivamente";
        }
        if ($advancedCount > 0) {
            $messages[] = "{$advancedCount} avaliação(ões) avançou/avançaram para próximo estágio";
        }

        if (!empty($messages)) {
            session()->flash('success', implode('; ', $messages));
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
            'rejectedBy',
            'approvals.approver' // NOVO: histórico de aprovações por estágio
        ])->findOrFail($evaluationId);

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
        $this->statusFilter = 'pending_for_me';
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

    /**
     * NOVO: Opções de status para filtro
     */
    protected function getStatusOptions()
    {
        return [
            'pending_for_me' => 'Pendentes para Mim',
            'in_approval' => 'Em Processo de Aprovação',
            'submitted' => 'Submetidas (Legado)',
            'approved' => 'Aprovadas',
            'rejected' => 'Rejeitadas',
            '' => 'Todos os Status'
        ];
    }

    protected function getMonthsArray()
    {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
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

    /**
     * NOVO: Obter informações do estágio atual para exibição
     */
    public function getCurrentStageInfo($evaluation)
    {

        if ($evaluation->status !== 'in_approval') {
            return null;
        }

        $currentApproval = $evaluation->getCurrentStageApproval();
        if (!$currentApproval) {
            return null;
        }

        return [
            'stage_number' => $evaluation->current_stage_number,
            'stage_name' => $currentApproval->stage_name,
            'approver_name' => $currentApproval->approver->name ?? 'N/A',
            'is_my_turn' => $currentApproval->approver_id === auth()->id()
        ];
    }

    /**
     * NOVO: Verificar se avaliação está aguardando aprovação do usuário atual
     */
    public function isWaitingForMe($evaluation)
    {
        return $evaluation->status === 'in_approval' &&
            $evaluation->canUserApproveCurrentStage(auth()->id());
    }
    public function render()
    {
        $evaluations = $this->getEvaluationsQuery()->paginate($this->perPage);

        return view('livewire.company.perfomance.evaluation-approvals', [
            'evaluations' => $evaluations,
            'months' => $this->getMonthsArray(),
            'years' => $this->getYearsArray(),
            'statusOptions' => $this->getStatusOptions(), // NOVO
        ])->layout('layouts.company');
    }
}
