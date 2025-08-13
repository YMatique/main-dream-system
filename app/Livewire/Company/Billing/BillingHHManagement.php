<?php

namespace App\Livewire\Company\Billing;

use App\Models\Company\Billing\BillingHH;
use App\Models\Company\Client;
use App\Models\Company\Employee;
use App\Models\Company\MaintenanceType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BillingHHManagement extends Component
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

    // Modal de alteração de moeda
    public $showCurrencyModal = false;
    public $selectedBilling = null;
    public $newCurrency = '';

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

    public function openCurrencyModal($billingId)
    {
        $this->selectedBilling = BillingHH::find($billingId);
        $this->newCurrency = $this->selectedBilling->billing_currency;
        $this->showCurrencyModal = true;
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
            
            session()->flash('success', 'Moeda alterada com sucesso!');
            $this->closeCurrencyModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar moeda: ' . $e->getMessage());
        }
    }

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

    public function getBillingsProperty()
    {
        $query = BillingHH::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType',
            'repairOrder.form1.machineNumber',
            'repairOrder.form2.employees.employee'
        ])
        ->forCompany(auth()->user()->company_id);

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

        // Filtro por data
        if ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo . ' 23:59:59');
        }

        // Filtro por moeda
        if ($this->selectedCurrency) {
            $query->where('billing_currency', $this->selectedCurrency);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getStatisticsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total_billings' => BillingHH::forCompany($companyId)->count(),
            'total_amount_mzn' => BillingHH::forCompany($companyId)->sum('total_mzn'),
            'total_amount_usd' => BillingHH::forCompany($companyId)->sum('total_usd'),
            'avg_hours' => BillingHH::forCompany($companyId)->avg('total_hours'),
            'currency_distribution' => BillingHH::forCompany($companyId)
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
        return view('livewire.company.billing.billing-h-h-management',[
            'billings' => $this->billings,
            'statistics' => $this->statistics,
            'maintenanceTypes' => $this->maintenanceTypes,
            'clients' => $this->clients,
            'employees' => $this->employees,
        ])->layout('layouts.company', [
                'title' => 'Faturação HH'
            ]);
    }
}
