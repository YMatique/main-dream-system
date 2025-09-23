<?php

namespace App\Livewire\Company\Forms;

use App\Models\Company\Client;
use App\Models\Company\Location;
use App\Models\Company\MachineNumber;
use App\Models\Company\MaintenanceType;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\RepairOrder\RepairOrderForm1 as RepairOrderRepairOrderForm1;
use App\Models\Company\Requester;
use App\Models\Company\Status;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RepairOrderForm1 extends Component
{
    // Propriedades do formulário
    #[Rule('required|exists:maintenance_types,id')]
    public $maintenance_type_id = '';

    #[Rule('required|exists:clients,id')]
    public $client_id = '';

    #[Rule('required|exists:statuses,id')]
    public $status_id = '';

    #[Rule('required|exists:locations,id')]
    public $location_id = '';

    #[Rule('required|string|min:10|max:1000')]
    public $descricao_avaria = '';

    #[Rule('required|integer|min:1|max:12')]
    public $mes = '';

    #[Rule('required|integer|min:2020|max:2030')]
    public $ano = '';

    #[Rule('required|exists:requesters,id')]
    public $requester_id = '';

    #[Rule('required|exists:machine_numbers,id')]
    public $machine_number_id = '';

    // Ordem de reparação (pode ser manual ou automática)
    #[Rule('nullable|string|max:50')]
    public $order_number = '';

    // Estado do componente
    public $repairOrder = null;
    public $isEditing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    // Collections para os selects
    public $clients = [];
    public $maintenanceTypes = [];
    public $statuses = [];
    public $locations = [];
    public $requesters = [];
    public $machineNumbers = [];

    // Propriedades computadas
    public $currentYear;
    public $months = [
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


    public function mount($order = null)
    {
        $this->currentYear = date('Y');
        $this->ano = $this->currentYear;
        $this->mes = date('n');

        // Carrega dados para os selects
        $this->loadFormData();

        // Se está editando uma ordem existente
        if ($order != null) {
            // dd($order);
            $this->repairOrder = RepairOrder::find($order);
            $this->loadExistingData();
            $this->isEditing = true;
        }
    }


    public function loadFormData()
    {
        $companyId = auth()->user()->company_id;

        $this->clients = Client::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->maintenanceTypes = MaintenanceType::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->statuses = Status::where('company_id', $companyId)
            ->where('form_type', 'form1')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'color']);

        $this->locations = Location::where('company_id', $companyId)
            ->where('form_type', 'form1')
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->requesters = Requester::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->machineNumbers = MachineNumber::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('number')
            ->get(['id', 'number']);
    }

    public function loadExistingData()
    {
        if ($this->repairOrder && $this->repairOrder->form1) {
            $form1 = $this->repairOrder->form1;

            $this->order_number = $this->repairOrder->order_number;
            $this->maintenance_type_id = $form1->maintenance_type_id;
            $this->client_id = $form1->client_id;
            $this->status_id = $form1->status_id;
            $this->location_id = $form1->location_id;
            $this->descricao_avaria = $form1->descricao_avaria;
            $this->mes = $form1->mes;
            $this->ano = $form1->ano;
            $this->requester_id = $form1->requester_id;
            $this->machine_number_id = $form1->machine_number_id;
        }
    }

    public function generateOrderNumber()
    {
        $companyId = auth()->user()->company_id;
        $this->order_number = RepairOrder::generateOrderNumber($companyId);
        // dd($this->order_number);
        // Disparar evento para atualizar a interface
            $this->dispatch('orderNumberGenerated', $this->order_number);
    }

    public function save()
    {
        $this->validate();

        try {
            \DB::transaction(function () {
                $companyId = auth()->user()->company_id;

                if ($this->isEditing) {
                    // Atualiza ordem existente
                    $this->updateExistingOrder();
                } else {
                    // Cria nova ordem
                    $this->createNewOrder($companyId);
                }
            });

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing
                ? 'Ordem de reparação atualizada com sucesso!'
                : 'Ordem de reparação criada com sucesso!';

            // Reset do formulário se for nova ordem
            if (!$this->isEditing) {
                $this->resetForm();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar ordem de reparação: ' . $e->getMessage());
        }
    }

    private function createNewOrder($companyId)
    {
        // Cria a ordem principal
        $repairOrder = RepairOrder::create([
            'company_id' => $companyId,
            'order_number' => $this->order_number ?: RepairOrder::generateOrderNumber($companyId),
            'current_form' => 'form1',
            'is_completed' => false,
        ]);

        // Cria o Form1
        RepairOrderRepairOrderForm1::create([
            'repair_order_id' => $repairOrder->id,
            'carimbo' => now(),
            'maintenance_type_id' => $this->maintenance_type_id,
            'client_id' => $this->client_id,
            'status_id' => $this->status_id,
            'location_id' => $this->location_id,
            'descricao_avaria' => $this->descricao_avaria,
            'mes' => $this->mes,
            'ano' => $this->ano,
            'requester_id' => $this->requester_id,
            'machine_number_id' => $this->machine_number_id,
        ]);

        $this->repairOrder = $repairOrder;
    }

    private function updateExistingOrder()
    {
        // Atualiza o número da ordem se foi modificado
        if ($this->order_number && $this->order_number !== $this->repairOrder->order_number) {
            $this->repairOrder->update(['order_number' => $this->order_number]);
        }

        // Atualiza ou cria o Form1
        $this->repairOrder->form1()->updateOrCreate(
            ['repair_order_id' => $this->repairOrder->id],
            [
                'carimbo' => now(),
                'maintenance_type_id' => $this->maintenance_type_id,
                'client_id' => $this->client_id,
                'status_id' => $this->status_id,
                'location_id' => $this->location_id,
                'descricao_avaria' => $this->descricao_avaria,
                'mes' => $this->mes,
                'ano' => $this->ano,
                'requester_id' => $this->requester_id,
                'machine_number_id' => $this->machine_number_id,
            ]
        );
    }

    public function resetForm()
    {
        $this->reset([
            'maintenance_type_id',
            'client_id',
            'status_id',
            'location_id',
            'descricao_avaria',
            'requester_id',
            'machine_number_id',
            'order_number'
        ]);

        $this->mes = date('n');
        $this->ano = $this->currentYear;
        $this->showSuccessMessage = false;
    }

    public function proceedToForm2()
    {
        if (!$this->repairOrder) {
            session()->flash('error', 'Salve o formulário antes de prosseguir.');
            return;
        }

        // Avança para o Form2
        $this->repairOrder->advanceToNextForm();

        return redirect()->route('company.repair-orders.form2', $this->repairOrder->id);
    }

    // Computed properties para exibição
    public function getSelectedClientNameProperty()
    {
        if (!$this->client_id) return '';
        $client = $this->clients->firstWhere('id', $this->client_id);
        return $client ? $client->name : '';
    }

    public function getSelectedMaintenanceTypeNameProperty()
    {
        if (!$this->maintenance_type_id) return '';
        $type = $this->maintenanceTypes->firstWhere('id', $this->maintenance_type_id);
        return $type ? $type->name : '';
    }


    // Listeners para integração com Select2
    protected $listeners = ['select2Updated' => 'updateSelectValue'];

    public function updateSelectValue($field, $value)
    {
        $this->$field = $value;
        $this->validateOnly($field);
    }

    // Método para atualizar Select2 via JavaScript
    public function updatedMaintenanceTypeId()
    {
        $this->dispatch('updateSelect2', field: 'maintenance_type_id', value: $this->maintenance_type_id);
    }

    public function updatedClientId()
    {
        $this->dispatch('updateSelect2', field: 'client_id', value: $this->client_id);
    }

    public function updatedRequesterId()
    {
        $this->dispatch('updateSelect2', field: 'requester_id', value: $this->requester_id);
    }

    public function updatedMachineNumberId()
    {
        $this->dispatch('updateSelect2', field: 'machine_number_id', value: $this->machine_number_id);
    }
    public function render()
    {
        return view('livewire.company.forms.repair-order-form1', ['title' => $this->isEditing ? 'Editar Ordem de Reparação' : 'Nova Ordem de Reparação'])->title('Formulário 1 - Ordem de Reparação')
            ->layout('layouts.company');;
    }
}
