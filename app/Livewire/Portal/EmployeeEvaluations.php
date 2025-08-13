<?php

namespace App\Livewire\Portal;

use App\Models\Company\Evaluation\PerformanceEvaluation;
use Illuminate\Support\Facades\Auth;
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
    public $portalUser ;

    public function mount($month = null, $year = null)
    {
       $this->portalUser = Auth::guard('employee_portal')->user();
        $this->employee = $this->portalUser->employee;

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
        return view('livewire.portal.employee-evaluations',[
            'evaluations' => $this->evaluations,
            'years' => $this->getAvailableYears(),
            'months' => $this->getMonths(),
            'statusOptions' => $this->getStatusOptions()
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

    private function getMonths()
    {
        return collect([
            '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
            '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
            '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
        ]);
    }

    private function getStatusOptions()
    {
        return [
            'approved' => 'Aprovada',
            'submitted' => 'Aguardando Aprovação',
            'rejected' => 'Rejeitada',
            'draft' => 'Rascunho'
        ];
    }
}
