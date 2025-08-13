<?php

namespace App\Livewire\Company\Billing;

use App\Models\Company\Billing\BillingReal;
use App\Models\Company\Client;
use App\Models\Company\Employee;
use App\Models\Company\MaintenanceType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BillingRealManagement extends Component
{

    use WithPagination;

    // Filtros
    public $search = '';
    public $selectedMaintenanceType = '';
    public $selectedClient = '';
    public $selectedEmployee = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedCurrency = '';

    // Modal de alteração de moeda (apenas moeda, não valores)
    public $showCurrencyModal = false;
    public $selectedBilling = null;
    public $newCurrency = '';

    // Modal de visualização
    public $showViewModal = false;
    public $viewBilling = null;

    // Propriedades para resetar paginação
    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'selectedMaintenanceType' => ['except' => ''],
        'selectedClient' => ['except' => ''],
        'selectedEmployee' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'selectedCurrency' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        // Definir datas padrão (último mês)
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
    }

    // =============================================
    // MODAL DE ALTERAÇÃO DE MOEDA (APENAS MOEDA)
    // =============================================

    public function openCurrencyModal($billingId)
    {
        $this->selectedBilling = BillingReal::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType',
            'repairOrder.form1.machineNumber'
        ])->find($billingId);
        
        $this->newCurrency = $this->selectedBilling->billing_currency;
        $this->showCurrencyModal = true;
        
        // Reset do modal de visualização
        $this->showViewModal = false;
    }

    public function closeCurrencyModal()
    {
        $this->showCurrencyModal = false;
        $this->selectedBilling = null;
        $this->newCurrency = '';
    }

    public function updateCurrency()
    {
        $this->validate([
            'newCurrency' => 'required|in:MZN,USD',
        ]);

        try {
            $this->selectedBilling->changeCurrency($this->newCurrency);
            
            session()->flash('success', 'Moeda da faturação alterada com sucesso!');
            $this->closeCurrencyModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar moeda: ' . $e->getMessage());
        }
    }

    // =============================================
    // MODAL DE VISUALIZAÇÃO
    // =============================================

    public function openViewModal($billingId)
    {
        $this->viewBilling = BillingReal::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType', 
            'repairOrder.form1.machineNumber',
            'repairOrder.form1.requester',
            'repairOrder.form1.status',
            'repairOrder.form1.location',
            'repairOrder.form2.employees.employee',
            'repairOrder.form2.materials.material',
            'repairOrder.form2.additionalMaterials',
            'repairOrder.form3.materials.material'
        ])->find($billingId);
        
        $this->showViewModal = true;
        
        // Reset do modal de moeda
        $this->showCurrencyModal = false;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewBilling = null;
    }

    // =============================================
    // FILTROS
    // =============================================

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedMaintenanceType = '';
        $this->selectedClient = '';
        $this->selectedEmployee = '';
        $this->selectedCurrency = '';
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->resetPage();
    }

    // =============================================
    // PROPRIEDADES COMPUTADAS
    // =============================================

    public function getBillingsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        $query = BillingReal::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType',
            'repairOrder.form1.machineNumber',
            'repairOrder.form2.employees.employee',
            'repairOrder.form3.materials.material'
        ])
        ->where('company_id', $companyId);

        // Filtro por ordem de reparação
        if ($this->search) {
            $query->whereHas('repairOrder', function($q) {
                $q->where('order_number', 'LIKE', "%{$this->search}%");
            });
        }

        // Filtro por tipo de manutenção
        if ($this->selectedMaintenanceType) {
            $query->whereHas('repairOrder.form1.maintenanceType', function($q) {
                $q->where('id', $this->selectedMaintenanceType);
            });
        }

        // Filtro por cliente
        if ($this->selectedClient) {
            $query->whereHas('repairOrder.form1.client', function($q) {
                $q->where('id', $this->selectedClient);
            });
        }

        // Filtro por funcionário
        if ($this->selectedEmployee) {
            $query->whereHas('repairOrder.form2.employees.employee', function($q) {
                $q->where('id', $this->selectedEmployee);
            });
        }

        // Filtro por data de faturação
        if ($this->dateFrom) {
            $query->where('billing_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('billing_date', '<=', $this->dateTo);
        }

        // Filtro por moeda
        if ($this->selectedCurrency) {
            $query->where('billing_currency', $this->selectedCurrency);
        }

        return $query->orderBy('billing_date', 'desc')->paginate(15);
    }

    public function getStatisticsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total_billings' => BillingReal::where('company_id', $companyId)->count(),
            'total_amount_mzn' => BillingReal::where('company_id', $companyId)->sum('total_mzn'),
            'total_amount_usd' => BillingReal::where('company_id', $companyId)->sum('total_usd'),
            'avg_hours' => BillingReal::where('company_id', $companyId)->avg('billed_hours'),
            'avg_hourly_rate_mzn' => BillingReal::where('company_id', $companyId)->avg('hourly_price_mzn'),
            'avg_hourly_rate_usd' => BillingReal::where('company_id', $companyId)->avg('hourly_price_usd'),
            'total_billed_hours' => BillingReal::where('company_id', $companyId)->sum('billed_hours'),
            'currency_distribution' => BillingReal::where('company_id', $companyId)
                ->select('billing_currency', DB::raw('count(*) as count'))
                ->groupBy('billing_currency')
                ->pluck('count', 'billing_currency')
                ->toArray(),
        ];
    }

    public function getMaintenanceTypesProperty()
    {
        return MaintenanceType::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getClientsProperty()
    {
        return Client::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getEmployeesProperty()
    {
        return Employee::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
    public function render()
    {
        return view('livewire.company.billing.billing-real-management', [
            'billings' => $this->billings,
            'statistics' => $this->statistics,
            'maintenanceTypes' => $this->maintenanceTypes,
            'clients' => $this->clients,
            'employees' => $this->employees,
        ])->layout('layouts.company', [
            'title' => 'Faturação Real'
        ]);
    }
}
