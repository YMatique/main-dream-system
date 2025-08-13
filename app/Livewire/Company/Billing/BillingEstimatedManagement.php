<?php

namespace App\Livewire\Company\Billing;

use App\Models\Company\Billing\BillingEstimated;
use App\Models\Company\Client;
use App\Models\Company\Employee;
use App\Models\Company\MaintenanceType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BillingEstimatedManagement extends Component
{
    use WithPagination;

    // Taxa de conversão (pode ser configurável no futuro)
    const EXCHANGE_RATE = 64; // 64 MZN = 1 USD

    // Filtros
    public $search = '';
    public $selectedMaintenanceType = '';
    public $selectedClient = '';
    public $selectedEmployee = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedCurrency = '';

    // Modal de alteração de moeda e preço
    public $showEditModal = false;
    public $selectedBilling = null;
    public $newCurrency = '';
    public $newHourlyPriceMzn = 0;
    public $newHourlyPriceUsd = 0;
    public $newEstimatedHours = 0;

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
    // MODAL DE ALTERAÇÃO DE MOEDA E PREÇO
    // =============================================

    public function openEditModal($billingId)
    {
        $this->selectedBilling = BillingEstimated::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType',
            'repairOrder.form1.machineNumber'
        ])->find($billingId);
        
        // Preencher valores atuais
        $this->newCurrency = $this->selectedBilling->billing_currency;
        $this->newHourlyPriceMzn = $this->selectedBilling->hourly_price_mzn;
        $this->newHourlyPriceUsd = $this->selectedBilling->hourly_price_usd;
        $this->newEstimatedHours = $this->selectedBilling->estimated_hours;
        
        $this->showEditModal = true;
        
        // Reset do modal de visualização
        $this->showViewModal = false;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedBilling = null;
        $this->newCurrency = '';
        $this->newHourlyPriceMzn = 0;
        $this->newHourlyPriceUsd = 0;
        $this->newEstimatedHours = 0;
    }

    // Conversão automática quando alterar preço MZN
    public function updatedNewHourlyPriceMzn()
    {
        if ($this->newHourlyPriceMzn > 0) {
            $this->newHourlyPriceUsd = round($this->newHourlyPriceMzn / self::EXCHANGE_RATE, 2);
        }
    }

    // Conversão automática quando alterar preço USD
    public function updatedNewHourlyPriceUsd()
    {
        if ($this->newHourlyPriceUsd > 0) {
            $this->newHourlyPriceMzn = round($this->newHourlyPriceUsd * self::EXCHANGE_RATE, 2);
        }
    }

    public function updateBilling()
    {
        $this->validate([
            'newCurrency' => 'required|in:MZN,USD',
            'newHourlyPriceMzn' => 'required|numeric|min:0',
            'newHourlyPriceUsd' => 'required|numeric|min:0',
            'newEstimatedHours' => 'required|numeric|min:0',
        ], [
            'newHourlyPriceMzn.required' => 'Preço por hora em MZN é obrigatório.',
            'newHourlyPriceUsd.required' => 'Preço por hora em USD é obrigatório.',
            'newEstimatedHours.required' => 'Horas estimadas são obrigatórias.',
            'newCurrency.required' => 'Moeda é obrigatória.',
        ]);

        try {
            // Atualizar preços e horas
            $this->selectedBilling->updatePrices(
                $this->newHourlyPriceMzn,
                $this->newHourlyPriceUsd,
                $this->newEstimatedHours
            );

            // Alterar moeda se necessário
            if ($this->selectedBilling->billing_currency !== $this->newCurrency) {
                $this->selectedBilling->changeCurrency($this->newCurrency);
            }
            
            session()->flash('success', 'Faturação estimada atualizada com sucesso!');
            $this->closeEditModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar faturação: ' . $e->getMessage());
        }
    }

    // =============================================
    // MODAL DE VISUALIZAÇÃO
    // =============================================

    public function openViewModal($billingId)
    {
        $this->viewBilling = BillingEstimated::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType', 
            'repairOrder.form1.machineNumber',
            'repairOrder.form1.requester',
            'repairOrder.form1.status',
            'repairOrder.form1.location',
            'repairOrder.form2.employees.employee',
            'repairOrder.form2.materials.material',
            'repairOrder.form2.additionalMaterials'
        ])->find($billingId);
        
        $this->showViewModal = true;
        
        // Reset do modal de edição
        $this->showEditModal = false;
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
        
        $query = BillingEstimated::with([
            'repairOrder.form1.client',
            'repairOrder.form1.maintenanceType',
            'repairOrder.form1.machineNumber',
            'repairOrder.form2.employees.employee'
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
            'total_billings' => BillingEstimated::where('company_id', $companyId)->count(),
            'total_amount_mzn' => BillingEstimated::where('company_id', $companyId)->sum('total_mzn'),
            'total_amount_usd' => BillingEstimated::where('company_id', $companyId)->sum('total_usd'),
            'avg_hours' => BillingEstimated::where('company_id', $companyId)->avg('estimated_hours'),
            'avg_hourly_rate_mzn' => BillingEstimated::where('company_id', $companyId)->avg('hourly_price_mzn'),
            'avg_hourly_rate_usd' => BillingEstimated::where('company_id', $companyId)->avg('hourly_price_usd'),
            'currency_distribution' => BillingEstimated::where('company_id', $companyId)
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

    // =============================================
    // MÉTODOS AUXILIARES
    // =============================================

    public function getExchangeRateProperty()
    {
        return self::EXCHANGE_RATE;
    }

    public function render()
    {
        return view('livewire.company.billing.billing-estimated-management', [
            'billings' => $this->billings,
            'statistics' => $this->statistics,
            'maintenanceTypes' => $this->maintenanceTypes,
            'clients' => $this->clients,
            'employees' => $this->employees,
            'exchangeRate' => $this->exchangeRate,
        ])->layout('layouts.company', [
            'title' => 'Faturação Estimada'
        ]);
    }
}
