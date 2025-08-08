<?php

namespace App\Livewire\Company;

use App\Models\Company\MaintenanceType;
use App\Models\Company\ClientCost;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class MaintenanceTypeManagement extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showRatesModal = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;
    public ?int $viewingRatesId = null;

    // Form properties
    public string $name = '';
    public ?string $description = null;
    public float $hourly_rate_mzn = 0.00;
    public float $hourly_rate_usd = 0.00;
    public bool $is_active = true;

    // Filter properties
    public string $search = '';
    public string $statusFilter = '';
    public string $rateRangeMin = '';
    public string $rateRangeMax = '';
    public int $perPage = 10;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('maintenance_types', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'description' => 'nullable|string|max:500',
            'hourly_rate_mzn' => 'required|numeric|min:0|max:99999.99',
            'hourly_rate_usd' => 'required|numeric|min:0|max:99999.99',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'name.unique' => 'Já existe um tipo de manutenção com este nome.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
        'hourly_rate_mzn.required' => 'A taxa horária (MZN) é obrigatória.',
        'hourly_rate_mzn.numeric' => 'A taxa (MZN) deve ser um número válido.',
        'hourly_rate_mzn.min' => 'A taxa (MZN) não pode ser negativa.',
        'hourly_rate_usd.required' => 'A taxa horária (USD) é obrigatória.',
        'hourly_rate_usd.numeric' => 'A taxa (USD) deve ser um número válido.',
        'hourly_rate_usd.min' => 'A taxa (USD) não pode ser negativa.',
    ];

    public function render()
    {
        $maintenanceTypes = $this->getMaintenanceTypes();

        return view('livewire.company.maintenance-type-management', compact('maintenanceTypes'))
            ->title('Tipos de Manutenção')
            ->layout('layouts.company');
    }
    public function getMaintenanceTypes()
    {
        return MaintenanceType::withCount(['clientCosts'])
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->when($this->rateRangeMin, function ($query) {
                $query->where('hourly_rate_mzn', '>=', $this->rateRangeMin);
            })
            ->when($this->rateRangeMax, function ($query) {
                $query->where('hourly_rate_mzn', '<=', $this->rateRangeMax);
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
        $this->name = '';
        $this->description = null;
        $this->hourly_rate_mzn = 0.00;
        $this->hourly_rate_usd = 0.00;
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->name,
                'description' => $this->description,
                'hourly_rate_mzn' => $this->hourly_rate_mzn,
                'hourly_rate_usd' => $this->hourly_rate_usd,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                MaintenanceType::where('company_id', auth()->user()->company_id)
                    ->findOrFail($this->editingId)
                    ->update($data);
                session()->flash('success', 'Tipo de manutenção atualizado com sucesso!');
            } else {
                MaintenanceType::create($data);
                session()->flash('success', 'Tipo de manutenção criado com sucesso!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Erro ao salvar tipo de manutenção: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar tipo de manutenção: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $this->editingId = $id;
            $this->name = $maintenanceType->name;
            $this->description = $maintenanceType->description;
            $this->hourly_rate_mzn = $maintenanceType->hourly_rate_mzn;
            $this->hourly_rate_usd = $maintenanceType->hourly_rate_usd;
            $this->is_active = $maintenanceType->is_active;
            
            $this->showModal = true;
        } catch (\Exception $e) {
            Log::error('Erro ao editar tipo de manutenção: ' . $e->getMessage());
            session()->flash('error', 'Erro ao carregar tipo de manutenção: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            if ($maintenanceType->clientCosts()->exists()) {
                session()->flash('error', 'Não é possível eliminar tipo de manutenção com custos de clientes associados. Desative-o em vez disso.');
                $this->showDeleteModal = false;
                return;
            }
            
            $maintenanceType->delete();
            session()->flash('success', 'Tipo de manutenção eliminado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao eliminar tipo de manutenção: ' . $e->getMessage());
            session()->flash('error', 'Erro ao eliminar tipo de manutenção: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$maintenanceType->is_active;
            $maintenanceType->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Tipo de manutenção {$statusText} com sucesso!");
        } catch (\Exception $e) {
            Log::error('Erro ao alterar status: ' . $e->getMessage());
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    public function viewClientRates($maintenanceTypeId)
    {
        $this->viewingRatesId = $maintenanceTypeId;
        $this->showRatesModal = true;
    }

    public function closeRatesModal()
    {
        $this->showRatesModal = false;
        $this->viewingRatesId = null;
    }

    public function getClientRatesProperty()
    {
        if (!$this->viewingRatesId) {
            return collect();
        }
        
        return ClientCost::with(['client'])
            ->where('company_id', auth()->user()->company_id)
            ->where('maintenance_type_id', $this->viewingRatesId)
            ->orderBy('effective_date', 'desc')
            ->get();
    }

    public function getMaintenanceTypeInfoProperty()
    {
        if (!$this->viewingRatesId) {
            return null;
        }
        
        return MaintenanceType::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->viewingRatesId);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createDefaultMaintenanceTypes()
    {
        try {
            $defaultTypes = [
                ['name' => 'Manutenção Preventiva', 'description' => 'Manutenção programada para prevenir falhas', 'rate_mzn' => 450, 'rate_usd' => 7.00],
                ['name' => 'Manutenção Corretiva', 'description' => 'Reparação de equipamentos avariados', 'rate_mzn' => 520, 'rate_usd' => 8.10],
                ['name' => 'Manutenção Elétrica', 'description' => 'Serviços elétricos especializados', 'rate_mzn' => 480, 'rate_usd' => 7.50],
                ['name' => 'Manutenção Mecânica', 'description' => 'Reparações mecânicas e estruturais', 'rate_mzn' => 500, 'rate_usd' => 7.80],
                ['name' => 'Soldadura', 'description' => 'Serviços de soldadura e metalurgia', 'rate_mzn' => 550, 'rate_usd' => 8.60],
                ['name' => 'Pintura', 'description' => 'Serviços de pintura e acabamentos', 'rate_mzn' => 380, 'rate_usd' => 5.90],
                ['name' => 'Instalação', 'description' => 'Instalação de novos equipamentos', 'rate_mzn' => 420, 'rate_usd' => 6.50],
                ['name' => 'Inspeção', 'description' => 'Inspeção e diagnóstico', 'rate_mzn' => 350, 'rate_usd' => 5.50],
            ];

            $created = 0;
            foreach ($defaultTypes as $type) {
                $exists = MaintenanceType::where('company_id', auth()->user()->company_id)
                    ->where('name', $type['name'])
                    ->exists();
                
                if (!$exists) {
                    MaintenanceType::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => $type['name'],
                        'description' => $type['description'],
                        'hourly_rate_mzn' => $type['rate_mzn'],
                        'hourly_rate_usd' => $type['rate_usd'],
                        'is_active' => true
                    ]);
                    $created++;
                }
            }
            
            if ($created > 0) {
                session()->flash('success', "{$created} tipos de manutenção padrão criados com sucesso!");
            } else {
                session()->flash('info', 'Todos os tipos de manutenção padrão já existem.');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao criar tipos padrão: ' . $e->getMessage());
            session()->flash('error', 'Erro ao criar tipos padrão: ' . $e->getMessage());
        }
    }

    public function duplicateMaintenanceType($id)
    {
        try {
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newType = $maintenanceType->replicate();
            $newType->name = $maintenanceType->name . ' (Cópia)';
            $newType->save();
            
            session()->flash('success', 'Tipo de manutenção duplicado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao duplicar tipo: ' . $e->getMessage());
            session()->flash('error', 'Erro ao duplicar tipo: ' . $e->getMessage());
        }
    }

    public function bulkUpdateRates($percentage, $applyTo = 'all')
    {
        try {
            $query = MaintenanceType::where('company_id', auth()->user()->company_id);
            
            if ($applyTo === 'active') {
                $query->where('is_active', true);
            }
            
            $types = $query->get();
            
            foreach ($types as $type) {
                $newRateMZN = $type->hourly_rate_mzn * (1 + $percentage / 100);
                $newRateUSD = $type->hourly_rate_usd * (1 + $percentage / 100);
                $type->update([
                    'hourly_rate_mzn' => round($newRateMZN, 2),
                    'hourly_rate_usd' => round($newRateUSD, 2)
                ]);
            }

            $action = $percentage > 0 ? 'aumentadas' : 'reduzidas';
            session()->flash('success', "Taxas {$action} em {$percentage}% para {$types->count()} tipos de manutenção!");
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar taxas: ' . $e->getMessage());
            session()->flash('error', 'Erro ao atualizar taxas: ' . $e->getMessage());
        }
    }

    public function bulkActivate($ids)
    {
        try {
            MaintenanceType::where('company_id', auth()->user()->company_id)
                ->whereIn('id', $ids)
                ->update(['is_active' => true]);
            
            session()->flash('success', 'Tipos de manutenção ativados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao ativar tipos: ' . $e->getMessage());
            session()->flash('error', 'Erro ao ativar tipos: ' . $e->getMessage());
        }
    }

    public function bulkDeactivate($ids)
    {
        try {
            MaintenanceType::where('company_id', auth()->user()->company_id)
                ->whereIn('id', $ids)
                ->update(['is_active' => false]);
            
            session()->flash('success', 'Tipos de manutenção desativados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao desativar tipos: ' . $e->getMessage());
            session()->flash('error', 'Erro ao desativar tipos: ' . $e->getMessage());
        }
    }

    public function export($format = 'excel')
    {
        try {
            $types = $this->getMaintenanceTypes()->items();
            // TODO: Implement export service
            session()->flash('info', 'Exportação em desenvolvimento...');
        } catch (\Exception $e) {
            Log::error('Erro ao exportar tipos: ' . $e->getMessage());
            session()->flash('error', 'Erro ao exportar: ' . $e->getMessage());
        }
    }

    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => MaintenanceType::where('company_id', $companyId)->count(),
            'active' => MaintenanceType::where('company_id', $companyId)->active()->count(),
            'inactive' => MaintenanceType::where('company_id', $companyId)->where('is_active', false)->count(),
            'with_client_costs' => MaintenanceType::where('company_id', $companyId)
                ->whereHas('clientCosts')
                ->count(),
            'avg_hourly_rate_mzn' => MaintenanceType::where('company_id', $companyId)
                ->avg('hourly_rate_mzn') ?? 0,
            'avg_hourly_rate_usd' => MaintenanceType::where('company_id', $companyId)
                ->avg('hourly_rate_usd') ?? 0,
            'min_hourly_rate_mzn' => MaintenanceType::where('company_id', $companyId)
                ->min('hourly_rate_mzn') ?? 0,
            'max_hourly_rate_mzn' => MaintenanceType::where('company_id', $companyId)
                ->max('hourly_rate_mzn') ?? 0,
            'total_repair_orders' => 0, // TODO: Count when repair orders implemented
        ];
    }

    public function getRateDistributionProperty()
    {
        $ranges = [
            '0-300' => ['min' => 0, 'max' => 300],
            '301-500' => ['min' => 301, 'max' => 500],
            '501-700' => ['min' => 501, 'max' => 700],
            '701+' => ['min' => 701, 'max' => PHP_INT_MAX],
        ];

        $distribution = [];
        foreach ($ranges as $label => $range) {
            $count = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->whereBetween('hourly_rate_mzn', [$range['min'], $range['max']])
                ->count();
            $distribution[$label] = $count;
        }

        return $distribution;
    }

    public function getMostUsedTypesProperty()
    {
        // TODO: Implement when repair orders are ready
        return collect();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRateRangeMin()
    {
        $this->resetPage();
    }

    public function updatingRateRangeMax()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
