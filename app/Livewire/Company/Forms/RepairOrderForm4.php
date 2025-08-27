<?php

namespace App\Livewire\Company\Forms;

use App\Models\Company\Location;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\Status;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RepairOrderForm4 extends Component
{
    // Propriedades do formulário
    public $repairOrder;
    public $form4Data;
    public $selectedOrderId = ''; // Para seleção de ordem

    #[Rule('required|exists:locations,id')]
    public $location_id = '';

    #[Rule('required|exists:statuses,id')]
    public $status_id = '';

    // Dados para os selects
    public $statuses = [];
    public $locations = [];
    public $availableOrders = []; // Ordens disponíveis para seleção

    // Estado do componente
    public $isEditing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    public function mount($repairOrder = null)
    {
        $this->loadFormData();
        $this->loadAvailableOrders();
        
        // Se veio com uma ordem específica (do Form3)
        if ($repairOrder) {
            $this->selectedOrderId = $repairOrder->id;
            $this->loadSelectedOrder();
        }
    }

    public function loadFormData()
    {
        $companyId = auth()->user()->company_id;

        $this->statuses = Status::where('company_id', $companyId)
            ->where('form_type', 'form4')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'color']);

        $this->locations = Location::where('company_id', $companyId)
            ->where('form_type', 'form4')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function loadAvailableOrders()
    {
        $companyId = auth()->user()->company_id;
        
        // Buscar todas as ordens que tenham Form3 completado
        $this->availableOrders = RepairOrder::where('company_id', $companyId)
            ->whereHas('form2') // Só ordens que tenham Form3
            ->whereDoesntHave('form3')
            ->with(['form1.client', 'form1.machineNumber', 'form3', 'form4'])
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
        
        // $this->repairOrder = RepairOrder::where('company_id', $companyId)
        //     ->where('id', $this->selectedOrderId)
        //     ->whereHas('form3') // Garantir que tem Form3
        //     ->with(['form1.machineNumber', 'form2', 'form3', 'form4'])
        //     ->first();
                    $this->repairOrder = RepairOrder::where('company_id', $companyId)
            ->where('id', $this->selectedOrderId)
            ->whereHas('form2') // Garantir que tem Form2
            ->with(['form1.machineNumber', 'form2', 'form3', 'form4'])
            ->first();

        if (!$this->repairOrder) {
            session()->flash('error', 'Ordem de reparação não encontrada ou sem Formulário 2 completo.');
            $this->selectedOrderId = '';
            return;
        }

        // Se já tem Form4, carregar dados existentes
        if ($this->repairOrder->form4) {
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
        // Sugerir dados baseados no Form3
        if ($this->repairOrder && $this->repairOrder->form3) {
            // Sugerir mesma localização do Form3
            $this->location_id = $this->repairOrder->form3->location_id;
        }
    }

    public function loadExistingData()
    {
        if ($this->repairOrder && $this->repairOrder->form4) {
            $form4 = $this->repairOrder->form4;
            
            $this->location_id = $form4->location_id;
            $this->status_id = $form4->status_id;
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

        // Validações
        $this->validate();

        try {
            \DB::transaction(function () {
                $this->saveForm4Data();
            });

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing 
                ? 'Formulário 4 atualizado com sucesso!' 
                : 'Formulário 4 registrado com sucesso!';

            // Atualizar status da ordem se necessário
            if (!$this->isEditing && $this->repairOrder->current_form === 'form3') {
                $this->repairOrder->advanceToNextForm();
            }

            // Recarregar dados para refletir mudanças
            $this->loadSelectedOrder();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar formulário: ' . $e->getMessage());
        }
    }

    private function saveForm4Data()
    {
        // Verificar se a ordem tem número de máquina definido no Form1
        if (!$this->repairOrder->form1 || !$this->repairOrder->form1->machine_number_id) {
            throw new \Exception('Esta ordem não possui número de máquina definido no Formulário 1.');
        }

        // Criar ou atualizar Form4
        $form4Data = [
            'repair_order_id' => $this->repairOrder->id,
            'carimbo' => now(),
            'location_id' => $this->location_id,
            'status_id' => $this->status_id,
            'machine_number_id' => $this->repairOrder->form1->machine_number_id, // Carregado dinamicamente
        ];

        $this->repairOrder->form4()->updateOrCreate(
            ['repair_order_id' => $this->repairOrder->id],
            $form4Data
        );
    }

    // =============================================
    // COMPUTED PROPERTIES
    // =============================================

    public function getMachineNumberProperty()
    {
        if ($this->repairOrder && $this->repairOrder->form1 && $this->repairOrder->form1->machineNumber) {
            return $this->repairOrder->form1->machineNumber->number;
        }
        return 'N/A';
    }

    public function getOrderSummaryProperty()
    {
        if (!$this->repairOrder || !$this->repairOrder->form1) {
            return null;
        }

        return [
            'client' => $this->repairOrder->form1->client?->name ?? 'N/A',
            'maintenance_type' => $this->repairOrder->form1->maintenanceType?->name ?? 'N/A',
            'machine_number' => $this->machineNumber,
            'description' => $this->repairOrder->form1->descricao_avaria,
            'worked_hours' => $this->repairOrder->form2?->tempo_total_horas ?? 0,
            'billed_hours' => $this->repairOrder->form3?->horas_faturadas ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.company.forms.repair-order-form4') ->layout('layouts.company', [
                'title' => 'Formulário 4 - Gestão de Máquina'
            ]);
    }
}
