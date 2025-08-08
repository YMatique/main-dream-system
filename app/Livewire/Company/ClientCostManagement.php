<?php

namespace App\Livewire\Company;

use App\Models\Company\ClientCost;
use App\Models\Company\Client;
use App\Models\Company\MaintenanceType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ClientCostManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showBulkModal = false;
    public $editingId = null;
    public $deleteId = null;

    // Form properties
    public $client_id = '';
    public $maintenance_type_id = '';
    public $cost_mzn = 0;
    public $cost_usd = 0;
    public $effective_date = '';

    // Bulk update properties
    public $bulk_client_id = '';
    public $bulk_adjustment_type = 'percentage'; // 'percentage', 'fixed'
    public $bulk_adjustment_value = 0;
    public $bulk_currency = 'both'; // 'mzn', 'usd', 'both'
    public $bulk_maintenance_types = [];

    // Filter properties
    public $search = '';
    public $clientFilter = '';
    public $maintenanceTypeFilter = '';
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $costRangeMin = '';
    public $costRangeMax = '';
    public $currencyFilter = 'mzn'; // 'mzn', 'usd'
    public $perPage = 10;
    public $sortBy = 'effective_date';
    public $sortDirection = 'desc';

    // Data collections
    public $clients = [];
    public $maintenanceTypes = [];

    // Exchange rate (could be dynamic from API)
    public $exchangeRate = 65; // MZN per USD

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'maintenance_type_id' => [
                'required',
                'exists:maintenance_types,id',
                Rule::unique('client_costs')
                    ->where('company_id', auth()->user()->company_id)
                    ->where('client_id', $this->client_id)
                    ->ignore($this->editingId)
            ],
            'cost_mzn' => 'required|numeric|min:0|max:999999.99',
            'cost_usd' => 'required|numeric|min:0|max:999999.99',
            'effective_date' => 'required|date|before_or_equal:today',
        ];
    }

    protected $messages = [
        'client_id.required' => 'Selecione um cliente.',
        'client_id.exists' => 'O cliente selecionado não existe.',
        'maintenance_type_id.required' => 'Selecione um tipo de manutenção.',
        'maintenance_type_id.exists' => 'O tipo de manutenção não existe.',
        'maintenance_type_id.unique' => 'Já existe um custo para este cliente e tipo de manutenção.',
        'cost_mzn.required' => 'O custo em MZN é obrigatório.',
        'cost_mzn.numeric' => 'O custo em MZN deve ser um número.',
        'cost_usd.required' => 'O custo em USD é obrigatório.',
        'cost_usd.numeric' => 'O custo em USD deve ser um número.',
        'effective_date.required' => 'A data de vigência é obrigatória.',
        'effective_date.date' => 'Digite uma data válida.',
        'effective_date.before_or_equal' => 'A data não pode ser futura.',
    ];

    public function mount()
    {
        $this->loadData();
        $this->effective_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $clientCosts = $this->getClientCosts();
        
        return view('livewire.company.client-cost-management', compact('clientCosts'))
            ->title('Custos por Cliente')
            ->layout('layouts.company');
    }

    public function loadData()
    {
        $this->clients = Client::where('company_id', auth()->user()->company_id)
            ->active()
            ->orderBy('name')
            ->get();
            
        $this->maintenanceTypes = MaintenanceType::where('company_id', auth()->user()->company_id)
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getClientCosts()
    {
        return ClientCost::with(['client', 'maintenanceType'])
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('client', function ($clientQuery) {
                        $clientQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('maintenanceType', function ($typeQuery) {
                        $typeQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->clientFilter, function ($query) {
                $query->where('client_id', $this->clientFilter);
            })
            ->when($this->maintenanceTypeFilter, function ($query) {
                $query->where('maintenance_type_id', $this->maintenanceTypeFilter);
            })
            ->when($this->dateFromFilter, function ($query) {
                $query->where('effective_date', '>=', $this->dateFromFilter);
            })
            ->when($this->dateToFilter, function ($query) {
                $query->where('effective_date', '<=', $this->dateToFilter);
            })
            ->when($this->costRangeMin, function ($query) {
                $column = $this->currencyFilter === 'usd' ? 'cost_usd' : 'cost_mzn';
                $query->where($column, '>=', $this->costRangeMin);
            })
            ->when($this->costRangeMax, function ($query) {
                $column = $this->currencyFilter === 'usd' ? 'cost_usd' : 'cost_mzn';
                $query->where($column, '<=', $this->costRangeMax);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->client_id = '';
        $this->maintenance_type_id = '';
        $this->cost_mzn = 0;
        $this->cost_usd = 0;
        $this->effective_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'client_id' => $this->client_id,
                'maintenance_type_id' => $this->maintenance_type_id,
                'cost_mzn' => $this->cost_mzn,
                'cost_usd' => $this->cost_usd,
                'effective_date' => $this->effective_date,
            ];

            if ($this->editingId) {
                $clientCost = ClientCost::findOrFail($this->editingId);
                $clientCost->update($data);
                session()->flash('success', 'Custo atualizado com sucesso!');
            } else {
                ClientCost::create($data);
                session()->flash('success', 'Custo criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar custo: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $clientCost = ClientCost::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->client_id = $clientCost->client_id;
        $this->maintenance_type_id = $clientCost->maintenance_type_id;
        $this->cost_mzn = $clientCost->cost_mzn;
        $this->cost_usd = $clientCost->cost_usd;
        $this->effective_date = $clientCost->effective_date->format('Y-m-d');
        
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $clientCost = ClientCost::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // TODO: Check if cost is being used in active repair orders
            
            $clientCost->delete();
            session()->flash('success', 'Custo eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar custo: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    // Auto-calculate USD from MZN
    public function updatedCostMzn()
    {
        if ($this->cost_mzn > 0) {
            $this->cost_usd = round($this->cost_mzn / $this->exchangeRate, 2);
        }
    }

    // Auto-calculate MZN from USD
    public function updatedCostUsd()
    {
        if ($this->cost_usd > 0) {
            $this->cost_mzn = round($this->cost_usd * $this->exchangeRate, 2);
        }
    }

    // Load maintenance type default rate
    public function updatedMaintenanceTypeId()
    {
        if ($this->maintenance_type_id) {
            $maintenanceType = MaintenanceType::find($this->maintenance_type_id);
            if ($maintenanceType) {
                $this->cost_mzn = $maintenanceType->default_hourly_rate;
                $this->cost_usd = round($maintenanceType->default_hourly_rate / $this->exchangeRate, 2);
            }
        }
    }

    // Bulk operations
    public function openBulkModal()
    {
        $this->resetBulkForm();
        $this->showBulkModal = true;
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->resetBulkForm();
    }

    public function resetBulkForm()
    {
        $this->bulk_client_id = '';
        $this->bulk_adjustment_type = 'percentage';
        $this->bulk_adjustment_value = 0;
        $this->bulk_currency = 'both';
        $this->bulk_maintenance_types = [];
    }

    public function bulkUpdateCosts()
    {
        $this->validate([
            'bulk_client_id' => 'required|exists:clients,id',
            'bulk_adjustment_value' => 'required|numeric',
            'bulk_maintenance_types' => 'required|array|min:1',
            'bulk_maintenance_types.*' => 'exists:maintenance_types,id'
        ], [
            'bulk_client_id.required' => 'Selecione um cliente.',
            'bulk_adjustment_value.required' => 'Digite o valor de ajuste.',
            'bulk_maintenance_types.required' => 'Selecione pelo menos um tipo de manutenção.',
        ]);

        try {
            $costs = ClientCost::where('company_id', auth()->user()->company_id)
                ->where('client_id', $this->bulk_client_id)
                ->whereIn('maintenance_type_id', $this->bulk_maintenance_types)
                ->get();

            $updated = 0;
            foreach ($costs as $cost) {
                $updateData = [];

                if ($this->bulk_currency === 'mzn' || $this->bulk_currency === 'both') {
                    if ($this->bulk_adjustment_type === 'percentage') {
                        $newCostMzn = $cost->cost_mzn * (1 + $this->bulk_adjustment_value / 100);
                    } else {
                        $newCostMzn = $cost->cost_mzn + $this->bulk_adjustment_value;
                    }
                    $updateData['cost_mzn'] = max(0, round($newCostMzn, 2));
                }

                if ($this->bulk_currency === 'usd' || $this->bulk_currency === 'both') {
                    if ($this->bulk_adjustment_type === 'percentage') {
                        $newCostUsd = $cost->cost_usd * (1 + $this->bulk_adjustment_value / 100);
                    } else {
                        $newCostUsd = $cost->cost_usd + $this->bulk_adjustment_value;
                    }
                    $updateData['cost_usd'] = max(0, round($newCostUsd, 2));
                }

                $cost->update($updateData);
                $updated++;
            }

            session()->flash('success', "{$updated} custos atualizados com sucesso!");
            $this->closeBulkModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar custos: ' . $e->getMessage());
        }
    }

    // Create costs for all clients
    public function createCostsForAllClients($maintenanceTypeId)
    {
        try {
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($maintenanceTypeId);

            $clients = Client::where('company_id', auth()->user()->company_id)
                ->active()
                ->get();

            $created = 0;
            foreach ($clients as $client) {
                // Check if cost already exists
                $exists = ClientCost::where('company_id', auth()->user()->company_id)
                    ->where('client_id', $client->id)
                    ->where('maintenance_type_id', $maintenanceTypeId)
                    ->exists();

                if (!$exists) {
                    ClientCost::create([
                        'company_id' => auth()->user()->company_id,
                        'client_id' => $client->id,
                        'maintenance_type_id' => $maintenanceTypeId,
                        'cost_mzn' => $maintenanceType->default_hourly_rate,
                        'cost_usd' => round($maintenanceType->default_hourly_rate / $this->exchangeRate, 2),
                        'effective_date' => now(),
                    ]);
                    $created++;
                }
            }

            session()->flash('success', "{$created} custos criados para o tipo '{$maintenanceType->name}'!");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar custos: ' . $e->getMessage());
        }
    }

    // Create costs for all maintenance types (for a specific client)
    public function createCostsForAllMaintenanceTypes($clientId)
    {
        try {
            $client = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($clientId);

            $maintenanceTypes = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->active()
                ->get();

            $created = 0;
            foreach ($maintenanceTypes as $type) {
                // Check if cost already exists
                $exists = ClientCost::where('company_id', auth()->user()->company_id)
                    ->where('client_id', $clientId)
                    ->where('maintenance_type_id', $type->id)
                    ->exists();

                if (!$exists) {
                    ClientCost::create([
                        'company_id' => auth()->user()->company_id,
                        'client_id' => $clientId,
                        'maintenance_type_id' => $type->id,
                        'cost_mzn' => $type->default_hourly_rate,
                        'cost_usd' => round($type->default_hourly_rate / $this->exchangeRate, 2),
                        'effective_date' => now(),
                    ]);
                    $created++;
                }
            }

            session()->flash('success', "{$created} custos criados para o cliente '{$client->name}'!");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar custos: ' . $e->getMessage());
        }
    }

    // Duplicate cost (change effective date)
    public function duplicateCost($id)
    {
        try {
            $cost = ClientCost::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);

            // Delete the old cost and create a new one with updated date
            $cost->update(['effective_date' => now()]);
            
            session()->flash('success', 'Custo atualizado para data atual!');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar custo: ' . $e->getMessage());
        }
    }

    // Copy costs from one client to another
    public function copyCostsBetweenClients($fromClientId, $toClientId)
    {
        try {
            $fromClient = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($fromClientId);
            
            $toClient = Client::where('company_id', auth()->user()->company_id)
                ->findOrFail($toClientId);

            $costs = ClientCost::where('company_id', auth()->user()->company_id)
                ->where('client_id', $fromClientId)
                ->get();

            $copied = 0;
            foreach ($costs as $cost) {
                // Check if cost already exists for target client
                $exists = ClientCost::where('company_id', auth()->user()->company_id)
                    ->where('client_id', $toClientId)
                    ->where('maintenance_type_id', $cost->maintenance_type_id)
                    ->exists();

                if (!$exists) {
                    ClientCost::create([
                        'company_id' => auth()->user()->company_id,
                        'client_id' => $toClientId,
                        'maintenance_type_id' => $cost->maintenance_type_id,
                        'cost_mzn' => $cost->cost_mzn,
                        'cost_usd' => $cost->cost_usd,
                        'effective_date' => now(),
                    ]);
                    $copied++;
                }
            }

            session()->flash('success', "{$copied} custos copiados de '{$fromClient->name}' para '{$toClient->name}'!");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao copiar custos: ' . $e->getMessage());
        }
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Currency conversion helper
    public function convertCurrency($amount, $from, $to)
    {
        if ($from === $to) return $amount;
        
        return $from === 'usd' 
            ? $amount * $this->exchangeRate 
            : $amount / $this->exchangeRate;
    }

    // Get cost in preferred currency
    public function getCostInCurrency($cost, $currency = 'mzn')
    {
        return $currency === 'usd' ? $cost->cost_usd : $cost->cost_mzn;
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $costs = $this->getClientCosts()->items();
        
        // TODO: Implement export service with detailed cost breakdown
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total_costs' => ClientCost::where('company_id', $companyId)->count(),
            'unique_clients' => ClientCost::where('company_id', $companyId)
                ->distinct('client_id')
                ->count(),
            'unique_maintenance_types' => ClientCost::where('company_id', $companyId)
                ->distinct('maintenance_type_id')
                ->count(),
            'avg_cost_mzn' => ClientCost::where('company_id', $companyId)
                ->avg('cost_mzn') ?? 0,
            'avg_cost_usd' => ClientCost::where('company_id', $companyId)
                ->avg('cost_usd') ?? 0,
            'min_cost_mzn' => ClientCost::where('company_id', $companyId)
                ->min('cost_mzn') ?? 0,
            'max_cost_mzn' => ClientCost::where('company_id', $companyId)
                ->max('cost_mzn') ?? 0,
            'clients_without_costs' => Client::where('company_id', $companyId)
                ->whereDoesntHave('clientCosts')
                ->count(),
        ];
    }

    // Cost analysis by client
    public function getCostAnalysisByClientProperty()
    {
        return ClientCost::selectRaw('
                client_id,
                COUNT(*) as maintenance_types_count,
                AVG(cost_mzn) as avg_cost_mzn,
                MIN(cost_mzn) as min_cost_mzn,
                MAX(cost_mzn) as max_cost_mzn
            ')
            ->with('client:id,name')
            ->where('company_id', auth()->user()->company_id)
            ->groupBy('client_id')
            ->orderBy('avg_cost_mzn', 'desc')
            ->get();
    }

    // Cost analysis by maintenance type
    public function getCostAnalysisByMaintenanceTypeProperty()
    {
        return ClientCost::selectRaw('
                maintenance_type_id,
                COUNT(*) as clients_count,
                AVG(cost_mzn) as avg_cost_mzn,
                MIN(cost_mzn) as min_cost_mzn,
                MAX(cost_mzn) as max_cost_mzn
            ')
            ->with('maintenanceType:id,name,color')
            ->where('company_id', auth()->user()->company_id)
            ->groupBy('maintenance_type_id')
            ->orderBy('avg_cost_mzn', 'desc')
            ->get();
    }

    // Get missing cost combinations (clients without specific maintenance type costs)
    public function getMissingCostCombinationsProperty()
    {
        $clients = Client::where('company_id', auth()->user()->company_id)
            ->active()
            ->get();
        
        $maintenanceTypes = MaintenanceType::where('company_id', auth()->user()->company_id)
            ->active()
            ->get();

        $missing = [];
        foreach ($clients as $client) {
            foreach ($maintenanceTypes as $type) {
                $exists = ClientCost::where('company_id', auth()->user()->company_id)
                    ->where('client_id', $client->id)
                    ->where('maintenance_type_id', $type->id)
                    ->exists();

                if (!$exists) {
                    $missing[] = [
                        'client' => $client,
                        'maintenance_type' => $type,
                    ];
                }
            }
        }

        return collect($missing)->take(20); // Limit to 20 for performance
    }

    // Price comparison with default rates
    public function getPriceComparisonProperty()
    {
        return ClientCost::selectRaw('
                client_costs.*,
                maintenance_types.default_hourly_rate,
                (client_costs.cost_mzn - maintenance_types.default_hourly_rate) as difference_mzn,
                ROUND(((client_costs.cost_mzn - maintenance_types.default_hourly_rate) / maintenance_types.default_hourly_rate) * 100, 2) as percentage_difference
            ')
            ->join('maintenance_types', 'client_costs.maintenance_type_id', '=', 'maintenance_types.id')
            ->with(['client:id,name', 'maintenanceType:id,name'])
            ->where('client_costs.company_id', auth()->user()->company_id)
            ->orderByDesc('percentage_difference')
            ->take(10)
            ->get();
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingClientFilter()
    {
        $this->resetPage();
    }

    public function updatingMaintenanceTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFromFilter()
    {
        $this->resetPage();
    }

    public function updatingDateToFilter()
    {
        $this->resetPage();
    }

    public function updatingCostRangeMin()
    {
        $this->resetPage();
    }

    public function updatingCostRangeMax()
    {
        $this->resetPage();
    }

    public function updatingCurrencyFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
