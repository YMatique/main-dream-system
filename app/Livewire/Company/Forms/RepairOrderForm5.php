<?php

namespace App\Livewire\Company\Forms;

use App\Models\Company\Employee;
use App\Models\Company\RepairOrder\RepairOrder;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RepairOrderForm5 extends Component
{
      // Propriedades do formulário
    public $repairOrder;
    public $form5Data;
    public $selectedOrderId = ''; // Para seleção de ordem

    #[Rule('required|date')]
    public $data_faturacao_1 = '';

    #[Rule('required|numeric|min:0|max:999.99')]
    public $horas_faturadas_1 = '';

    #[Rule('required|date')]
    public $data_faturacao_2 = '';

    #[Rule('required|numeric|min:0|max:999.99')]
    public $horas_faturadas_2 = '';

    #[Rule('required|string|min:10|max:1000')]
    public $descricao_actividades = '';

    #[Rule('required|exists:employees,id')]
    public $employee_id = '';

    // Dados para os selects
    public $employees = [];
    public $availableOrders = []; // Ordens disponíveis para seleção

    // Estado do componente
    public $isEditing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $dateValidationError = '';

    public function mount($order = null)
    {
        // Definir data padrão como hoje para primeira data
        $this->data_faturacao_1 = date('Y-m-d');
        // Segunda data 2 dias depois (sugestão)
        $this->data_faturacao_2 = date('Y-m-d', strtotime('+2 days'));
        
        $this->loadFormData();

        
        // Se veio com uma ordem específica (do Form4)
        // if ($repairOrder) {
        //     $this->selectedOrderId = $repairOrder->id;
        //     $this->loadSelectedOrder();
        // }
        if ($order != null) {
            $this->selectedOrderId = RepairOrder::findOrFail($order)->id;
            $this->loadSelectedOrder();
            $this->isEditing = true;
        }
                $this->loadAvailableOrders();
    }

    public function loadFormData()
    {
        $companyId = auth()->user()->company_id;

        $this->employees = Employee::where('company_id', $companyId)
            ->where('is_active', true)
            ->with('department')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'department_id']);
    }

    public function loadAvailableOrders()
    {
        $companyId = auth()->user()->company_id;
        
        // Buscar todas as ordens que tenham Form4 completado
        $this->availableOrders = $this->isEditing ? RepairOrder::where('id', $this->selectedOrderId)->get() :RepairOrder::where('company_id', $companyId)
            ->whereHas('form3') // Só ordens que tenham Form4
            ->with(['form1.client', 'form1.machineNumber', 'form4', 'form5'])
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

    public function updatedDataFaturacao1()
    {
        $this->validateDateDifference();
    }

    public function updatedDataFaturacao2()
    {
        $this->validateDateDifference();
    }

    public function validateDateDifference()
    {
        $this->dateValidationError = '';

        if ($this->data_faturacao_1 && $this->data_faturacao_2) {
            $date1 = Carbon::parse($this->data_faturacao_1);
            $date2 = Carbon::parse($this->data_faturacao_2);
            
            // Verificar se date2 é depois de date1
            if ($date2->lt($date1)) {
                $this->dateValidationError = 'A segunda data deve ser posterior à primeira data.';
                return false;
            }
            
            // Verificar diferença máxima de 4 dias
            $daysDiff = $date1->diffInDays($date2);
            if ($daysDiff > 4) {
                $this->dateValidationError = 'A diferença entre as datas não pode ser superior a 4 dias. Diferença atual: ' . $daysDiff . ' dias.';
                return false;
            }
        }
        
        return true;
    }

    public function loadSelectedOrder()
    {
        $companyId = auth()->user()->company_id;
        
        $this->repairOrder = RepairOrder::where('company_id', $companyId)
            ->where('id', $this->selectedOrderId)
            ->whereHas('form3') // Garantir que tem Form4
            ->with(['form1.client', 'form1.machineNumber', 'form2', 'form3', 'form4', 'form5'])
            ->first();

        if (!$this->repairOrder) {
            session()->flash('error', 'Ordem de reparação não encontrada ou sem Formulário 4 completo.');
            $this->selectedOrderId = '';
            return;
        }

        // Se já tem Form5, carregar dados existentes
        if ($this->repairOrder->form5) {
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
        // Sugerir dados baseados nos formulários anteriores
        if ($this->repairOrder && $this->repairOrder->form3) {
            // Sugerir primeira data baseada na data de faturação do Form3
            $form3Date = $this->repairOrder->form3->data_faturacao;
            $this->data_faturacao_1 = $form3Date->format('Y-m-d');
            
            // Segunda data 2 dias depois
            $this->data_faturacao_2 = $form3Date->addDays(2)->format('Y-m-d');
            
            // Sugerir horas baseadas no Form3
            $this->horas_faturadas_1 = $this->repairOrder->form3->horas_faturadas / 2;
            $this->horas_faturadas_2 = $this->repairOrder->form3->horas_faturadas / 2;
        }

        // Sugerir técnico se houver apenas um no Form2
        if ($this->repairOrder && $this->repairOrder->form2 && $this->repairOrder->form2->employees->count() === 1) {
            $this->employee_id = $this->repairOrder->form2->employees->first()->id;
        }
    }

    public function loadExistingData()
    {
        if ($this->repairOrder && $this->repairOrder->form5) {
            $form5 = $this->repairOrder->form5;
            
            $this->data_faturacao_1 = $form5->data_faturacao_1->format('Y-m-d');
            $this->horas_faturadas_1 = $form5->horas_faturadas_1;
            $this->data_faturacao_2 = $form5->data_faturacao_2->format('Y-m-d');
            $this->horas_faturadas_2 = $form5->horas_faturadas_2;
            $this->descricao_actividades = $form5->descricao_actividades;
            $this->employee_id = $form5->employee_id;
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
        $this->data_faturacao_1 = date('Y-m-d');
        $this->data_faturacao_2 = date('Y-m-d', strtotime('+2 days'));
        $this->horas_faturadas_1 = '';
        $this->horas_faturadas_2 = '';
        $this->descricao_actividades = '';
        $this->employee_id = '';
        $this->dateValidationError = '';
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

        // Validar diferença de datas
        if (!$this->validateDateDifference()) {
            return;
        }

        // Validações básicas
        $this->validate();

        // Validações customizadas
        $this->validateForm();

        try {
            \DB::transaction(function () {
                $this->saveForm5Data();
            });

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing 
                ? 'Formulário 5 atualizado com sucesso! Ordem FINALIZADA!' 
                : 'Formulário 5 concluído com sucesso! Ordem FINALIZADA!';

            // Marcar ordem como completa
            if (!$this->isEditing && $this->repairOrder->current_form === 'form3') {
                $this->repairOrder->advanceToNextForm(); // vai para form5
                $this->repairOrder->is_completed = true;
                $this->repairOrder->save();
            }

            // Recarregar dados para refletir mudanças
            $this->loadSelectedOrder();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar formulário: ' . $e->getMessage());
        }
    }

    private function validateForm()
    {
        // Validar se datas não são futuras
        if ($this->data_faturacao_1 > date('Y-m-d')) {
            throw new \Exception('A primeira data de faturação não pode ser no futuro.');
        }

        if ($this->data_faturacao_2 > date('Y-m-d')) {
            throw new \Exception('A segunda data de faturação não pode ser no futuro.');
        }

        // Validar se funcionário pertence à empresa
        $employee = Employee::where('company_id', auth()->user()->company_id)
            ->where('id', $this->employee_id)
            ->where('is_active', true)
            ->first();
            
        if (!$employee) {
            throw new \Exception('Funcionário inválido ou inativo.');
        }

        // Validar total de horas
        $totalHours = $this->horas_faturadas_1 + $this->horas_faturadas_2;
        if ($this->repairOrder->form3) {
            $form3Hours = $this->repairOrder->form3->horas_faturadas;
            if ($totalHours > ($form3Hours * 1.2)) { // 20% de tolerância
                session()->flush('error', 'Total de horas (' . $totalHours . 'h) excede 20% das horas faturadas no Form3 (' . $form3Hours . 'h).');
                // throw new \Exception('Total de horas (' . $totalHours . 'h) excede significativamente as horas faturadas no Form3 (' . $form3Hours . 'h).');
            }
        }
    }

    private function saveForm5Data()
    {
        // Verificar dados necessários
        if (!$this->repairOrder->form1 || !$this->repairOrder->form1->client_id) {
            throw new \Exception('Esta ordem não possui cliente definido no Formulário 1.');
        }

        if (!$this->repairOrder->form1->machine_number_id) {
            throw new \Exception('Esta ordem não possui número de máquina definido no Formulário 1.');
        }

        // Criar ou atualizar Form5
        $form5Data = [
            'repair_order_id' => $this->repairOrder->id,
            'carimbo' => now(),
            'machine_number_id' => $this->repairOrder->form1->machine_number_id, // Carregado dinamicamente
            'data_faturacao_1' => $this->data_faturacao_1,
            'horas_faturadas_1' => $this->horas_faturadas_1,
            'data_faturacao_2' => $this->data_faturacao_2,
            'horas_faturadas_2' => $this->horas_faturadas_2,
            'client_id' => $this->repairOrder->form1->client_id, // Carregado dinamicamente
            'descricao_actividades' => $this->descricao_actividades,
            'employee_id' => $this->employee_id,
        ];

        $this->repairOrder->form5()->updateOrCreate(
            ['repair_order_id' => $this->repairOrder->id],
            $form5Data
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

    public function getClientNameProperty()
    {
        if ($this->repairOrder && $this->repairOrder->form1 && $this->repairOrder->form1->client) {
            return $this->repairOrder->form1->client->name;
        }
        return 'N/A';
    }

    public function getTotalHoursProperty()
    {
        return ($this->horas_faturadas_1 ?: 0) + ($this->horas_faturadas_2 ?: 0);
    }

    public function getDaysDifferenceProperty()
    {
        if ($this->data_faturacao_1 && $this->data_faturacao_2) {
            $date1 = Carbon::parse($this->data_faturacao_1);
            $date2 = Carbon::parse($this->data_faturacao_2);
            return $date1->diffInDays($date2);
        }
        return 0;
    }

    public function getOrderSummaryProperty()
    {
        if (!$this->repairOrder || !$this->repairOrder->form1) {
            return null;
        }

        return [
            'client' => $this->clientName,
            'maintenance_type' => $this->repairOrder->form1->maintenanceType?->name ?? 'N/A',
            'machine_number' => $this->machineNumber,
            'description' => $this->repairOrder->form1->descricao_avaria,
            'worked_hours' => $this->repairOrder->form2?->tempo_total_horas ?? 0,
            'billed_hours_form3' => $this->repairOrder->form3?->horas_faturadas ?? 0,
            'total_hours_form5' => $this->totalHours,
        ];
    }

    public function getSelectedEmployeeProperty()
    {
        if ($this->employee_id) {
            return $this->employees->firstWhere('id', $this->employee_id);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.company.forms.repair-order-form5') ->layout('layouts.company', [
                'title' => 'Formulário 5 - Equipamento e Validação Final'
            ]);;
    }
}
