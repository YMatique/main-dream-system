<?php

namespace App\Livewire\Company\Perfomance;

use App\Models\Company\Department;
use App\Models\Company\Evaluation\EvaluationApprovalStage;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ApprovalStageManagement extends Component
{
     // Props principais
    public $departments = [];
    public $companyUsers = [];
    
    // Modal state
    public $showModal = false;
    public $editingStage = null;
    
    // Form fields
    #[Validate('required')]
    public $selectedDepartmentId = '';
    
    #[Validate('required|min:3')]
    public $stageName = '';
    
    #[Validate('required')]
    public $approverUserId = '';
    
    #[Validate('required|integer|min:1')]
    public $stageNumber = 1;
    
    public $isRequired = true;
    public $isActive = true;
    public $description = '';
    
    // UI State
    public $selectedDepartmentFilter = '';
    
    public function mount()
    {
        $this->loadData();
    }
    
    public function loadData()
    {
        $companyId = auth()->user()->company_id;
        
        // Carregar departamentos com seus estágios
        $this->departments = Department::where('company_id', $companyId)
            ->where('is_active', true)
            ->with(['approvalStages' => function($query) {
                $query->orderBy('stage_number')
                      ->with('approver:id,name,email');
            }])
            ->orderBy('name')
            ->get()
            ->toArray();
        
        // Carregar usuários da empresa para dropdown
        $this->companyUsers = User::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->toArray();
    }
    
    public function openModal($departmentId = null)
    {
        $this->resetForm();
        $this->selectedDepartmentId = $departmentId ?: '';
        
        if ($departmentId) {
            // Calcular próximo número de estágio para este departamento
            $this->stageNumber = $this->getNextStageNumber($departmentId);
        }
        
        $this->showModal = true;
    }
    
    public function editStage($stageId)
    {
        $stage = EvaluationApprovalStage::find($stageId);
        
        if (!$stage || $stage->company_id !== auth()->user()->company_id) {
            $this->addError('general', 'Estágio não encontrado.');
            return;
        }
        
        $this->editingStage = $stageId;
        $this->selectedDepartmentId = $stage->target_department_id;
        $this->stageName = $stage->stage_name;
        $this->approverUserId = $stage->approver_user_id;
        $this->stageNumber = $stage->stage_number;
        $this->isRequired = $stage->is_required;
        $this->isActive = $stage->is_active;
        $this->description = $stage->description ?? '';
        
        $this->showModal = true;
    }
    
    public function saveStage()
    {
        $this->validate();
        
        $companyId = auth()->user()->company_id;
        
        // Validar se departamento pertence à empresa
        $department = Department::where('id', $this->selectedDepartmentId)
            ->where('company_id', $companyId)
            ->first();
            
        if (!$department) {
            $this->addError('selectedDepartmentId', 'Departamento inválido.');
            return;
        }
        
        // Validar se aprovador pertence à empresa
        $approver = User::where('id', $this->approverUserId)
            ->where('company_id', $companyId)
            ->first();
            
        if (!$approver) {
            $this->addError('approverUserId', 'Aprovador inválido.');
            return;
        }
        
        try {
            $data = [
                'company_id' => $companyId,
                'target_department_id' => $this->selectedDepartmentId,
                'stage_number' => $this->stageNumber,
                'stage_name' => $this->stageName,
                'description' => $this->description,
                'approver_user_id' => $this->approverUserId,
                'is_required' => $this->isRequired,
                'is_active' => $this->isActive,
            ];
            
            if ($this->editingStage) {
                // Editar estágio existente
                $stage = EvaluationApprovalStage::find($this->editingStage);
                $stage->update($data);
                $message = 'Estágio atualizado com sucesso!';
            } else {
                // Criar novo estágio
                EvaluationApprovalStage::create($data);
                $message = 'Estágio criado com sucesso!';
            }
            
            // Recalcular is_final_stage
            $this->updateFinalStageFlags($this->selectedDepartmentId);
            
            $this->loadData();
            $this->closeModal();
            
            session()->flash('success', $message);
            
        } catch (\Exception $e) {
            $this->addError('general', 'Erro ao salvar estágio: ' . $e->getMessage());
        }
    }
    
    public function deleteStage($stageId)
    {
        $stage = EvaluationApprovalStage::find($stageId);
        
        if (!$stage || $stage->company_id !== auth()->user()->company_id) {
            $this->addError('general', 'Estágio não encontrado.');
            return;
        }
        
        try {
            $departmentId = $stage->target_department_id;
            $stage->delete();
            
            // Recalcular números e final stage
            $this->reorderStages($departmentId);
            $this->updateFinalStageFlags($departmentId);
            
            $this->loadData();
            
            session()->flash('success', 'Estágio removido com sucesso!');
            
        } catch (\Exception $e) {
            $this->addError('general', 'Erro ao remover estágio: ' . $e->getMessage());
        }
    }
    
    public function reorderStage($stageId, $direction)
    {
        $stage = EvaluationApprovalStage::find($stageId);
        
        if (!$stage || $stage->company_id !== auth()->user()->company_id) {
            return;
        }
        
        $departmentId = $stage->target_department_id;
        $currentNumber = $stage->stage_number;
        $newNumber = $direction === 'up' ? $currentNumber - 1 : $currentNumber + 1;
        
        if ($newNumber < 1) return;
        
        // Verificar se existe estágio no destino
        $targetStage = EvaluationApprovalStage::where('target_department_id', $departmentId)
            ->where('stage_number', $newNumber)
            ->first();
            
        if (!$targetStage) return;
        
        // Trocar posições
        $stage->update(['stage_number' => $newNumber]);
        $targetStage->update(['stage_number' => $currentNumber]);
        
        // Recalcular final stage
        $this->updateFinalStageFlags($departmentId);
        
        $this->loadData();
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->editingStage = null;
        $this->selectedDepartmentId = '';
        $this->stageName = '';
        $this->approverUserId = '';
        $this->stageNumber = 1;
        $this->isRequired = true;
        $this->isActive = true;
        $this->description = '';
        $this->resetErrorBag();
    }
    
    private function getNextStageNumber($departmentId)
    {
        return EvaluationApprovalStage::where('target_department_id', $departmentId)
            ->max('stage_number') + 1 ?: 1;
    }
    
    private function updateFinalStageFlags($departmentId)
    {
        // Limpar todas as flags
        EvaluationApprovalStage::where('target_department_id', $departmentId)
            ->update(['is_final_stage' => false]);
        
        // Marcar o último estágio
        $maxStage = EvaluationApprovalStage::where('target_department_id', $departmentId)
            ->where('is_active', true)
            ->max('stage_number');
            
        if ($maxStage) {
            EvaluationApprovalStage::where('target_department_id', $departmentId)
                ->where('stage_number', $maxStage)
                ->update(['is_final_stage' => true]);
        }
    }
    
    private function reorderStages($departmentId)
    {
        $stages = EvaluationApprovalStage::where('target_department_id', $departmentId)
            ->orderBy('stage_number')
            ->get();
            
        foreach ($stages as $index => $stage) {
            $stage->update(['stage_number' => $index + 1]);
        }
    }
    
    public function getDepartmentStagesProperty()
    {
        if ($this->selectedDepartmentFilter) {
            return collect($this->departments)->where('id', $this->selectedDepartmentFilter);
        }
        
        return collect($this->departments);
    }
    
    public function render()
    {
        return view('livewire.company.perfomance.approval-stage-management');
    }
}
