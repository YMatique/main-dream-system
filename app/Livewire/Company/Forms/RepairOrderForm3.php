<?php

namespace App\Livewire\Company\Forms;


use App\Models\Company\Material;
use App\Models\Company\Status;
use App\Models\Company\Location;
use App\Models\Company\RepairOrder\RepairOrder;
use Livewire\Component;
use Livewire\Attributes\Rule;

class RepairOrderForm3 extends Component
{
    // Propriedades do formulário
    public $repairOrder;
    public $form3Data;
    public $selectedOrderId = ''; // Para seleção de ordem

    #[Rule('required|exists:locations,id')]
    public $location_id = '';

    #[Rule('required|exists:statuses,id')]
    public $status_id = '';

    #[Rule('required|date')]
    public $data_faturacao = '';

    #[Rule('required|numeric|min:0|max:999.99')]
    public $horas_faturadas = '';

    // Materiais faturados
    public $materiaisDisponiveis = [];

    // Dados para os selects
    public $materials = [];
    public $statuses = [];
    public $locations = [];
    public $availableOrders = []; // Ordens disponíveis para seleção

    // Estado do componente
    public $isEditing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    public function mount($repairOrder = null)
    {
        // Definir data padrão como hoje
        $this->data_faturacao = date('Y-m-d');
        
        $this->loadFormData();
        $this->loadAvailableOrders();
        
        // Se veio com uma ordem específica (do Form2)
        if ($repairOrder) {
            $this->selectedOrderId = $repairOrder->id;
            $this->loadSelectedOrder();
        }
    }

    public function loadFormData()
    {
        $companyId = auth()->user()->company_id;

        $this->materials = Material::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'unit', 'cost_per_unit_mzn']);

        $this->statuses = Status::where('company_id', $companyId)
            ->where('form_type', 'form3')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'color']);

        $this->locations = Location::where('company_id', $companyId)
            ->where('form_type', 'form3')
            ->orderBy('name')
            ->get(['id', 'name']);

        // Prepara array de materiais disponíveis para checkbox
        $this->materiaisDisponiveis = $this->materials->mapWithKeys(function ($material) {
            return [$material->id => [
                'id' => $material->id,
                'name' => $material->name,
                'unit' => $material->unit,
                'cost' => $material->cost_per_unit_mzn,
                'selected' => false,
                'quantidade' => 0
            ]];
        })->toArray();
    }

    public function loadAvailableOrders()
    {
        $companyId = auth()->user()->company_id;
        
        // Buscar todas as ordens que tenham Form2 completado
        $this->availableOrders = RepairOrder::where('company_id', $companyId)
            ->whereHas('form2') // Só ordens que tenham Form2
            ->with(['form1.client', 'form2', 'form3'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'order_number', 'created_at']);
    }

    public function updatedSelectedOrderId()
    {
        if ($this->selectedOrderId) {
            $this->loadSelectedOrder();
        } else {
            $this->resetOrderData();
        }
    }

    public function loadSelectedOrder()
    {
        $companyId = auth()->user()->company_id;
        
        $this->repairOrder = RepairOrder::where('company_id', $companyId)
            ->where('id', $this->selectedOrderId)
            ->whereHas('form2') // Garantir que tem Form2
            ->with(['form1', 'form2', 'form3.materials'])
            ->first();

        if (!$this->repairOrder) {
            session()->flash('error', 'Ordem de reparação não encontrada ou sem Formulário 2 completo.');
            $this->selectedOrderId = '';
            return;
        }

        // Se já tem Form3, carregar dados existentes
        if ($this->repairOrder->form3) {
            $this->isEditing = true;
            $this->loadExistingData();
        } else {
            $this->isEditing = false;
            $this->resetFormFields();
            $this->loadDefaultData();
        }
    }

    public function loadDefaultData()
    {
        // Sugerir dados baseados no Form2
        if ($this->repairOrder && $this->repairOrder->form2) {
            // Sugerir mesma localização do Form2
            $this->location_id = $this->repairOrder->form2->location_id;
            
            // Sugerir horas faturadas baseadas no tempo total do Form2
            $this->horas_faturadas = $this->repairOrder->form2->tempo_total_horas;
            
            // Pré-selecionar materiais que foram usados no Form2
            $this->preselectMaterialsFromForm2();
        }
    }

    public function preselectMaterialsFromForm2()
    {
        if (!$this->repairOrder || !$this->repairOrder->form2) return;

        // Materiais cadastrados usados no Form2
        $form2Materials = $this->repairOrder->form2->materials;
        
        foreach ($form2Materials as $form2Material) {
            if (isset($this->materiaisDisponiveis[$form2Material->material_id])) {
                $this->materiaisDisponiveis[$form2Material->material_id]['selected'] = true;
                $this->materiaisDisponiveis[$form2Material->material_id]['quantidade'] = $form2Material->quantidade;
            }
        }
    }

    public function loadExistingData()
    {
        if ($this->repairOrder && $this->repairOrder->form3) {
            $form3 = $this->repairOrder->form3;
            
            $this->location_id = $form3->location_id;
            $this->status_id = $form3->status_id;
            $this->data_faturacao = $form3->data_faturacao->format('Y-m-d');
            $this->horas_faturadas = $form3->horas_faturadas;
            
            // Carregar materiais faturados
            $existingMaterials = $form3->materials;
            foreach ($existingMaterials as $material) {
                if (isset($this->materiaisDisponiveis[$material->material_id])) {
                    $this->materiaisDisponiveis[$material->material_id]['selected'] = true;
                    $this->materiaisDisponiveis[$material->material_id]['quantidade'] = $material->quantidade;
                }
            }
        }
    }

    public function resetOrderData()
    {
        $this->repairOrder = null;
        $this->isEditing = false;
        $this->resetFormFields();
    }

    public function resetFormFields()
    {
        $this->location_id = '';
        $this->status_id = '';
        $this->data_faturacao = date('Y-m-d');
        $this->horas_faturadas = '';
        $this->resetMaterials();
    }

    public function resetMaterials()
    {
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            $this->materiaisDisponiveis[$materialId]['selected'] = false;
            $this->materiaisDisponiveis[$materialId]['quantidade'] = 0;
        }
    }

    // =============================================
    // MÉTODOS PARA MATERIAIS
    // =============================================

    public function toggleMaterial($materialId)
    {
        if (isset($this->materiaisDisponiveis[$materialId])) {
            $this->materiaisDisponiveis[$materialId]['selected'] = !$this->materiaisDisponiveis[$materialId]['selected'];
            
            // Se desmarcou, zerar quantidade
            if (!$this->materiaisDisponiveis[$materialId]['selected']) {
                $this->materiaisDisponiveis[$materialId]['quantidade'] = 0;
            }
        }
    }

    // =============================================
    // VALIDAÇÃO E SALVAMENTO
    // =============================================

    public function save()
    {
        // Validar se uma ordem foi selecionada
        if (!$this->selectedOrderId || !$this->repairOrder) {
            session()->flash('error', 'Selecione uma ordem de reparação primeiro.');
            return;
        }

        // Validações customizadas
        $this->validateForm();

        try {
            \DB::transaction(function () {
                $this->saveForm3Data();
                
                // Gerar faturação real automaticamente
                $this->generateRealBilling();
            });

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing 
                ? 'Faturação Real atualizada com sucesso!' 
                : 'Faturação Real criada com sucesso!';

            // Atualizar status da ordem se necessário
            if (!$this->isEditing && $this->repairOrder->current_form === 'form2') {
                $this->repairOrder->advanceToNextForm();
            }

            // Recarregar dados para refletir mudanças
            $this->loadSelectedOrder();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar faturação: ' . $e->getMessage());
        }
    }

    private function validateForm()
    {
        $this->validate();

        // Validar se data não é futura
        if ($this->data_faturacao > date('Y-m-d')) {
            throw new \Exception('A data de faturação não pode ser no futuro.');
        }

        // Validar se horas faturadas não excedem muito as horas trabalhadas
        if ($this->repairOrder && $this->repairOrder->form2) {
            $horasTrabalho = $this->repairOrder->form2->tempo_total_horas;
            if ($this->horas_faturadas > ($horasTrabalho * 1.5)) { // 50% de tolerância
                throw new \Exception('As horas faturadas não podem exceder significativamente as horas trabalhadas (' . $horasTrabalho . 'h).');
            }
        }
    }

    private function saveForm3Data()
    {
        // Criar ou atualizar Form3
        $form3Data = [
            'repair_order_id' => $this->repairOrder->id,
            'carimbo' => now(),
            'location_id' => $this->location_id,
            'status_id' => $this->status_id,
            'data_faturacao' => $this->data_faturacao,
            'horas_faturadas' => $this->horas_faturadas,
        ];

        $form3 = $this->repairOrder->form3()->updateOrCreate(
            ['repair_order_id' => $this->repairOrder->id],
            $form3Data
        );

        // Salvar materiais faturados
        $this->saveMaterials($form3);
    }

    private function saveMaterials($form3)
    {
        // Remover materiais existentes
        $form3->materials()->delete();

        // Adicionar materiais selecionados
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            if ($material['selected'] && $material['quantidade'] > 0) {
                $form3->materials()->create([
                    'material_id' => $materialId,
                    'quantidade' => $material['quantidade']
                ]);
            }
        }
    }

    private function generateRealBilling()
    {
        // TODO: Implementar geração da faturação real
        // Será implementado quando criarmos o sistema de faturação
        \Log::info('Faturação Real gerada para ordem: ' . $this->repairOrder->order_number);
    }

    // =============================================
    // COMPUTED PROPERTIES
    // =============================================

    public function getSelectedMaterialsCountProperty()
    {
        return collect($this->materiaisDisponiveis)->where('selected', true)->count();
    }

    public function getTotalMaterialCostProperty()
    {
        $total = 0;
        
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            if ($material['selected'] && $material['quantidade'] > 0) {
                $materialData = $this->materials->firstWhere('id', $materialId);
                if ($materialData) {
                    $total += $materialData->cost_per_unit_mzn * $material['quantidade'];
                }
            }
        }
        
        return $total;
    }

    public function getEstimatedLaborCostProperty()
    {
        if (!$this->repairOrder || !$this->repairOrder->form1 || !$this->horas_faturadas) {
            return 0;
        }

        $maintenanceType = $this->repairOrder->form1->maintenanceType;
        if ($maintenanceType) {
            return $this->horas_faturadas * $maintenanceType->hourly_rate_mzn;
        }

        return 0;
    }

    public function getTotalEstimatedCostProperty()
    {
        return $this->totalMaterialCost + $this->estimatedLaborCost;
    }

    public function render()
    {
        return view('livewire.company.forms.repair-order-form3')
            ->layout('layouts.company', [
                'title' => 'Formulário 3 - Faturação Real'
            ]);
    }
}
