<?php

namespace App\Livewire\Company\Forms;

use App\Models\Company\Employee;
use App\Models\Company\Location;
use App\Models\Company\Material;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\Status;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RepairOrderForm2 extends Component
{
    // Propriedades do formulário
    public $repairOrder;

    public $form2Data;

    public $selectedOrderId = ''; // Para seleção de ordem

    #[Rule('required|exists:locations,id')]
    public $location_id = '';

    #[Rule('required|exists:statuses,id')]
    public $status_id = '';

    #[Rule('required|string|min:10|max:2000')]
    public $actividade_realizada = '';

    // Técnicos
    public $numero_tecnicos = 1;

    public $tecnicos = [];

    // Materiais cadastrados
    public $materiaisDisponiveis = [];

    public $materiaisSelecionados = [];

    // Materiais adicionais
    public $numero_materiais_adicionais = 0;

    public $materiaisAdicionais = [];

    // Dados para os selects
    public $employees = [];

    public $materials = [];

    public $statuses = [];

    public $locations = [];

    public $availableOrders = []; // Ordens disponíveis para seleção

    // Estado do componente
    public $isEditing = false;

    public $showSuccessMessage = false;

    public $successMessage = '';

    // Computed properties
    public $tempoTotalCalculado = 0;

    public function mount($order = null)
    {
        $this->loadFormData();
        $this->initializeTechnicians();

        // Se veio com uma ordem específica (do Form1)
        // dd($order);
        if ($order != null) {
            $this->selectedOrderId = RepairOrder::find($order)->id;
            $this->loadSelectedOrder();
            $this->isEditing = true;
        }
        $this->loadAvailableOrders();
    }

    public function loadAvailableOrders()
    {
        $companyId = auth()->user()->company_id;
        
        // Buscar todas as ordens que tenham Form1 completado
        $this->availableOrders = $this->isEditing ? RepairOrder::where('id', $this->selectedOrderId)->get() : RepairOrder::where('company_id', $companyId)
            ->whereHas('form1') // Só ordens que tenham Form1
            ->whereDoesntHave('form2')
            ->with(['form1.client', 'form1.maintenanceType', 'form2'])
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
            ->whereHas('form1') // Garantir que tem Form1
            ->with(['form1', 'form2.employees', 'form2.materials', 'form2.additionalMaterials'])
            ->first();

        if (! $this->repairOrder) {
            session()->flash('error', 'Ordem de reparação não encontrada ou sem Formulário 1.');
            $this->selectedOrderId = '';

            return;
        }

        // Se já tem Form2, carregar dados existentes
        if ($this->repairOrder->form2) {
            $this->isEditing = true;
            $this->loadExistingData();
        } else {
            $this->isEditing = false;
            $this->resetFormFields();
        }

        $this->calculateTotalHours();
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
        $this->actividade_realizada = '';
        $this->initializeTechnicians();
        $this->resetMaterials();
        $this->numero_materiais_adicionais = 0;
        $this->materiaisAdicionais = [];
        $this->tempoTotalCalculado = 0;
    }

    public function resetMaterials()
    {
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            $this->materiaisDisponiveis[$materialId]['selected'] = false;
            $this->materiaisDisponiveis[$materialId]['quantidade'] = 0;
        }
    }

    public function loadFormData()
    {
        $companyId = auth()->user()->company_id;

        $this->employees = Employee::where('company_id', $companyId)
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'department_id']);

        $this->materials = Material::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'unit', 'cost_per_unit_mzn']);

        $this->statuses = Status::where('company_id', $companyId)
            ->where('form_type', 'form2')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'color']);

        $this->locations = Location::where('company_id', $companyId)
            ->where('form_type', 'form2')
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
                'quantidade' => 0,
            ],
            ];
        })->toArray();
    }

    public function initializeTechnicians()
    {
        // Inicializar com 1 técnico por padrão
        $this->tecnicos = [
            [
                'employee_id' => '',
                'horas_trabalhadas' => 0,
            ],
        ];
    }

    public function loadExistingData()
    {
        if ($this->repairOrder && $this->repairOrder->form2) {
            $form2 = $this->repairOrder->form2;
            // dd($this->selectedOrderId , $form2->id);
            // $this->selectedOrderId = $form2->id;
            $this->location_id = $form2->location_id;
            $this->status_id = $form2->status_id;
            $this->actividade_realizada = $form2->actividade_realizada;

            // Carregar técnicos existentes
            $existingEmployees = $form2->employees;
            $this->numero_tecnicos = $existingEmployees->count();

            $this->tecnicos = $existingEmployees->map(function ($emp) {
                return [
                    'employee_id' => $emp->employee_id,
                    'horas_trabalhadas' => $emp->horas_trabalhadas,
                ];
            })->toArray();

            // Carregar materiais selecionados
            $existingMaterials = $form2->materials;
            foreach ($existingMaterials as $material) {
                if (isset($this->materiaisDisponiveis[$material->material_id])) {
                    $this->materiaisDisponiveis[$material->material_id]['selected'] = true;
                    $this->materiaisDisponiveis[$material->material_id]['quantidade'] = $material->quantidade;
                }
            }

            // Carregar materiais adicionais
            $additionalMaterials = $form2->additionalMaterials;
            $this->numero_materiais_adicionais = $additionalMaterials->count();

            $this->materiaisAdicionais = $additionalMaterials->map(function ($mat) {
                return [
                    'nome_material' => $mat->nome_material,
                    'custo_unitario' => $mat->custo_unitario,
                    'quantidade' => $mat->quantidade,
                ];
            })->toArray();

            $this->isEditing = true;
        }
    }

    // =============================================
    // MÉTODOS PARA TÉCNICOS
    // =============================================

    public function addTechnician()
    {
        $this->numero_tecnicos++;
        $this->tecnicos[] = [
            'employee_id' => '',
            'horas_trabalhadas' => 0,
        ];
    }

    public function removeTechnician($index)
    {
        if ($this->numero_tecnicos > 1) {
            unset($this->tecnicos[$index]);
            $this->tecnicos = array_values($this->tecnicos); // Reindexar
            $this->numero_tecnicos--;
            $this->calculateTotalHours();
        }
    }

    public function updatedTecnicos()
    {
        $this->calculateTotalHours();
    }

    public function calculateTotalHours()
    {
        $total = 0;
        foreach ($this->tecnicos as $tecnico) {
            $total += (float) ($tecnico['horas_trabalhadas'] ?? 0);
        }
        $this->tempoTotalCalculado = $total;
    }

    // =============================================
    // MÉTODOS PARA MATERIAIS
    // =============================================

    public function toggleMaterial($materialId)
    {
        if (isset($this->materiaisDisponiveis[$materialId])) {
            $this->materiaisDisponiveis[$materialId]['selected'] = ! $this->materiaisDisponiveis[$materialId]['selected'];

            // Se desmarcou, zerar quantidade
            if (! $this->materiaisDisponiveis[$materialId]['selected']) {
                $this->materiaisDisponiveis[$materialId]['quantidade'] = 0;
            }
        }
    }

    public function addAdditionalMaterial()
    {
        $this->numero_materiais_adicionais++;
        $this->materiaisAdicionais[] = [
            'nome_material' => '',
            'custo_unitario' => 0,
            'quantidade' => 0,
        ];
    }

    public function removeAdditionalMaterial($index)
    {
        if ($this->numero_materiais_adicionais > 0) {
            unset($this->materiaisAdicionais[$index]);
            $this->materiaisAdicionais = array_values($this->materiaisAdicionais);
            $this->numero_materiais_adicionais--;
        }
    }

    // =============================================
    // VALIDAÇÃO E SALVAMENTO
    // =============================================

    public function save()
    {
        // Validar se uma ordem foi selecionada
        if (! $this->selectedOrderId || ! $this->repairOrder) {
            session()->flash('error', 'Selecione uma ordem de reparação primeiro.');

            return;
        }

        // Validações customizadas
        $this->validateForm();

        try {
            \DB::transaction(function () {
                $this->saveForm2Data();
            });

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing
                ? 'Formulário 2 atualizado com sucesso!'
                : 'Formulário 2 salvo com sucesso!';

            // Atualizar status da ordem se necessário
            if (! $this->isEditing && $this->repairOrder->current_form === 'form1') {
                $this->repairOrder->advanceToNextForm();
            }

            // Recarregar dados para refletir mudanças
            $this->loadSelectedOrder();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar formulário: '.$e->getMessage());
        }
    }

    private function validateForm()
    {
        $this->validate();

        // Validar se pelo menos um técnico foi selecionado
        $hasValidTechnician = false;
        foreach ($this->tecnicos as $tecnico) {
            if (! empty($tecnico['employee_id']) && $tecnico['horas_trabalhadas'] > 0) {
                $hasValidTechnician = true;
                break;
            }
        }

        if (! $hasValidTechnician) {
            throw new \Exception('Selecione pelo menos um técnico com horas trabalhadas.');
        }

        // Validar técnicos duplicados
        $employeeIds = array_filter(array_column($this->tecnicos, 'employee_id'));
        if (count($employeeIds) !== count(array_unique($employeeIds))) {
            throw new \Exception('Não é possível selecionar o mesmo técnico várias vezes.');
        }
    }

    private function saveForm2Data()
    {
        // Criar ou atualizar Form2
        $form2Data = [
            'repair_order_id' => $this->repairOrder->id,
            'carimbo' => now(),
            'location_id' => $this->location_id,
            'status_id' => $this->status_id,
            'tempo_total_horas' => $this->tempoTotalCalculado,
            'actividade_realizada' => $this->actividade_realizada,
        ];

        $form2 = $this->repairOrder->form2()->updateOrCreate(
            ['repair_order_id' => $this->repairOrder->id],
            $form2Data
        );

        // Salvar técnicos
        $this->saveTechnicians($form2);

        // Salvar materiais
        $this->saveMaterials($form2);

        // Salvar materiais adicionais
        $this->saveAdditionalMaterials($form2);
    }

    private function saveTechnicians($form2)
    {
        // Remover técnicos existentes
        $form2->employees()->delete();

        // Adicionar novos técnicos
        foreach ($this->tecnicos as $tecnico) {
            if (! empty($tecnico['employee_id']) && $tecnico['horas_trabalhadas'] > 0) {
                $form2->employees()->create([
                    'employee_id' => $tecnico['employee_id'],
                    'horas_trabalhadas' => $tecnico['horas_trabalhadas'],
                ]);
            }
        }
    }

    private function saveMaterials($form2)
    {
        // Remover materiais existentes
        $form2->materials()->delete();

        // Adicionar materiais selecionados
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            if ($material['selected'] && $material['quantidade'] > 0) {
                $form2->materials()->create([
                    'material_id' => $materialId,
                    'quantidade' => $material['quantidade'],
                ]);
            }
        }
    }

    private function saveAdditionalMaterials($form2)
    {
        // Remover materiais adicionais existentes
        $form2->additionalMaterials()->delete();

        // Adicionar novos materiais adicionais
        foreach ($this->materiaisAdicionais as $material) {
            if (! empty($material['nome_material']) && $material['quantidade'] > 0) {
                $form2->additionalMaterials()->create([
                    'nome_material' => $material['nome_material'],
                    'custo_unitario' => $material['custo_unitario'],
                    'quantidade' => $material['quantidade'],
                ]);
            }
        }
    }

    public function proceedToForm3()
    {
        if (! $this->repairOrder || ! $this->repairOrder->form2) {
            session()->flash('error', 'Salve o formulário antes de prosseguir.');

            return;
        }

        return redirect()->route('company.repair-orders.form4', $this->repairOrder->id);
    }

    public function backToForm1()
    {
        return redirect()->route('company.repair-orders.form1', $this->repairOrder->id);
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

        // Custo dos materiais cadastrados
        foreach ($this->materiaisDisponiveis as $materialId => $material) {
            if ($material['selected'] && $material['quantidade'] > 0) {
                $materialData = $this->materials->firstWhere('id', $materialId);
                if ($materialData) {
                    $total += $materialData->cost_per_unit_mzn * $material['quantidade'];
                }
            }
        }

        // Custo dos materiais adicionais
        foreach ($this->materiaisAdicionais as $material) {
            if (! empty($material['nome_material']) && $material['quantidade'] > 0) {
                $total += $material['custo_unitario'] * $material['quantidade'];
            }
        }

        return $total;
    }

    public function render()
    {
        return view('livewire.company.forms.repair-order-form2')->layout('layouts.company', [
            'title' => 'Formulário 2 - Técnicos e Materiais',
        ]);
    }
}
