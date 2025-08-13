<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeEvaluations extends Component
{
    use WithPagination;

    public $employee;
    public $selectedEvaluation = null;
    public $showDetailsModal = false;
    
    // Filtros
    public $yearFilter = '';
    public $monthFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    public function mount($month = null, $year = null)
    {
        $this->employee = auth()->user()->company->employees()
            ->where('email', auth()->user()->email)
            ->first();

        if (!$this->employee) {
            abort(403, 'Funcionário não encontrado.');
        }

        // Se veio da URL com mês/ano específico
        if ($month && $year) {
            $this->monthFilter = str_pad($month, 2, '0', STR_PAD_LEFT);
            $this->yearFilter = $year;
        } else {
            $this->yearFilter = now()->year;
        }
    }

    public function getEvaluationsProperty()
    {
        $query = PerformanceEvaluation::forEmployee($this->employee->id)
            ->with(['evaluator', 'responses.metric', 'employee.department'])
            ->orderBy('evaluation_period', 'desc');

        // Aplicar filtros
        if ($this->yearFilter) {
            $query->forPeriod($this->yearFilter);
        }

        if ($this->monthFilter) {
            $query->forPeriod($this->yearFilter, $this->monthFilter);
        }

        if ($this->statusFilter) {
            $query->byStatus($this->statusFilter);
        }

        return $query->paginate($this->perPage);
    }

    public function viewDetails($evaluationId)
    {
        $this->selectedEvaluation = PerformanceEvaluation::with([
            'evaluator', 
            'responses.metric', 
            'employee.department',
            'approvedBy',
            'rejectedBy'
        ])->find($evaluationId);
        
        // Verificar se a avaliação pertence ao funcionário
        if (!$this->selectedEvaluation || $this->selectedEvaluation->employee_id !== $this->employee->id) {
            session()->flash('error', 'Avaliação não encontrada.');
            return;
        }
        
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedEvaluation = null;
    }

    public function downloadEvaluation($evaluationId)
    {
        $evaluation = PerformanceEvaluation::find($evaluationId);
        
        if (!$evaluation || $evaluation->employee_id !== $this->employee->id) {
            session()->flash('error', 'Avaliação não encontrada.');
            return;
        }

        // TODO: Implementar geração de PDF
        session()->flash('info', 'Download de PDF será implementado em breve.');
    }

    public function printEvaluation($evaluationId)
    {
        $evaluation = PerformanceEvaluation::find($evaluationId);
        
        if (!$evaluation || $evaluation->employee_id !== $this->employee->id) {
            session()->flash('error', 'Avaliação não encontrada.');
            return;
        }

        // Abrir página de impressão em nova aba
        $this->dispatch('open-print-page', route('employee.evaluations.print', $evaluationId));
    }

    // Lifecycle hooks para reset de paginação
    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingMonthFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.portal.employee-evaluations');
    }
}
