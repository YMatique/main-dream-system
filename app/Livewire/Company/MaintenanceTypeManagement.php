<?php

namespace App\Livewire\Company;

use App\Models\Company\MaintenanceType;
use Livewire\Component;

class MaintenanceTypeManagement extends Component
{
     use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showRatesModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $viewingRatesId = null;

    // Form properties
    public $name = '';
    public $description = '';
    public $default_hourly_rate = 0;
    public $color = '#3B82F6';
    public $is_active = true;

    // Filter properties
    public $search = '';
    public $statusFilter = '';
    public $rateRangeMin = '';
    public $rateRangeMax = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Predefined colors for maintenance types
    public $availableColors = [
        '#3B82F6' => 'Azul',
        '#10B981' => 'Verde',
        '#F59E0B' => 'Amarelo',
        '#EF4444' => 'Vermelho',
        '#8B5CF6' => 'Roxo',
        '#F97316' => 'Laranja',
        '#06B6D4' => 'Ciano',
        '#84CC16' => 'Lima',
        '#EC4899' => 'Rosa',
        '#6B7280' => 'Cinza'
    ];

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
            'default_hourly_rate' => 'required|numeric|min:0|max:99999.99',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'name.unique' => 'Já existe um tipo de manutenção com este nome.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
        'default_hourly_rate.required' => 'A taxa horária padrão é obrigatória.',
        'default_hourly_rate.numeric' => 'A taxa deve ser um número válido.',
        'default_hourly_rate.min' => 'A taxa não pode ser negativa.',
        'color.required' => 'Selecione uma cor.',
        'color.regex' => 'Formato de cor inválido.',
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
                $query->where('default_hourly_rate', '>=', $this->rateRangeMin);
            })
            ->when($this->rateRangeMax, function ($query) {
                $query->where('default_hourly_rate', '<=', $this->rateRangeMax);
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
        $this->description = '';
        $this->default_hourly_rate = 0;
        $this->color = '#3B82F6';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->name,
                'description' => $this->description ?: null,
                'default_hourly_rate' => $this->default_hourly_rate,
                'color' => $this->color,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $maintenanceType = MaintenanceType::findOrFail($this->editingId);
                $maintenanceType->update($data);
                session()->flash('success', 'Tipo de manutenção atualizado com sucesso!');
            } else {
                MaintenanceType::create($data);
                session()->flash('success', 'Tipo de manutenção criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar tipo de manutenção: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $maintenanceType->name;
        $this->description = $maintenanceType->description;
        $this->default_hourly_rate = $maintenanceType->default_hourly_rate;
        $this->color = $maintenanceType->color;
        $this->is_active = $maintenanceType->is_active;
        
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
            $maintenanceType = MaintenanceType::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // Check if maintenance type has associated client costs or repair orders
            if ($maintenanceType->clientCosts()->exists()) {
                session()->flash('error', 'Não é possível eliminar tipo de manutenção com custos de clientes associados. Desative-o em vez disso.');
                $this->showDeleteModal = false;
                return;
            }
            
            // TODO: Check repair orders when implemented
            
            $maintenanceType->delete();
            session()->flash('success', 'Tipo de manutenção eliminado com sucesso!');
            
        } catch (\Exception $e) {
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
        if (!$this->viewingRatesId) return collect();
        
        return ClientCost::with(['client'])
            ->where('company_id', auth()->user()->company_id)
            ->where('maintenance_type_id', $this->viewingRatesId)
            ->orderBy('effective_date', 'desc')
            ->get();
    }

    public function getMaintenanceTypeInfoProperty()
    {
        if (!$this->viewingRatesId) return null;
        
        return MaintenanceType::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->viewingRatesId);
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

    // Create default maintenance types
    public function createDefaultMaintenanceTypes()
    {
        try {
            $defaultTypes = [
                ['name' => 'Manutenção Preventiva', 'description' => 'Manutenção programada para prevenir falhas', 'rate' => 450, 'color' => '#10B981'],
                ['name' => 'Manutenção Corretiva', 'description' => 'Reparação de equipamentos avariados', 'rate' => 520, 'color' => '#EF4444'],
                ['name' => 'Manutenção Elétrica', 'description' => 'Serviços elétricos especializados', 'rate' => 480, 'color' => '#F59E0B'],
                ['name' => 'Manutenção Mecânica', 'description' => 'Reparações mecânicas e estruturais', 'rate' => 500, 'color' => '#3B82F6'],
                ['name' => 'Soldadura', 'description' => 'Serviços de soldadura e metalurgia', 'rate' => 550, 'color' => '#F97316'],
                ['name' => 'Pintura', 'description' => 'Serviços de pintura e acabamentos', 'rate' => 380, 'color' => '#8B5CF6'],
                ['name' => 'Instalação', 'description' => 'Instalação de novos equipamentos', 'rate' => 420, 'color' => '#06B6D4'],
                ['name' => 'Inspeção', 'description' => 'Inspeção e diagnóstico', 'rate' => 350, 'color' => '#84CC16'],
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
                        'default_hourly_rate' => $type['rate'],
                        'color' => $type['color'],
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
            session()->flash('error', 'Erro ao criar tipos padrão: ' . $e->getMessage());
        }
    }

    // Duplicate maintenance type
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
            session()->flash('error', 'Erro ao duplicar tipo: ' . $e->getMessage());
        }
    }

    // Bulk update rates
    public function bulkUpdateRates($percentage, $applyTo = 'all')
    {
        try {
            $query = MaintenanceType::where('company_id', auth()->user()->company_id);
            
            if ($applyTo === 'active') {
                $query->where('is_active', true);
            }
            
            $types = $query->get();
            
            foreach ($types as $type) {
                $newRate = $type->default_hourly_rate * (1 + $percentage / 100);
                $type->update(['default_hourly_rate' => round($newRate, 2)]);
            }

            $action = $percentage > 0 ? 'aumentadas' : 'reduzidas';
            session()->flash('success', "Taxas {$action} em {$percentage}% para {$types->count()} tipos de manutenção!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar taxas: ' . $e->getMessage());
        }
    }

    // Get color name
    public function getColorName($hexColor)
    {
        return $this->availableColors[$hexColor] ?? 'Personalizada';
    }

    // Generate random color
    public function generateRandomColor()
    {
        $colors = array_keys($this->availableColors);
        $this->color = $colors[array_rand($colors)];
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        MaintenanceType::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Tipos de manutenção ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        MaintenanceType::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Tipos de manutenção desativados com sucesso!');
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $types = $this->getMaintenanceTypes()->items();
        
        // TODO: Implement export service
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
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
            'avg_hourly_rate' => MaintenanceType::where('company_id', $companyId)
                ->avg('default_hourly_rate') ?? 0,
            'min_hourly_rate' => MaintenanceType::where('company_id', $companyId)
                ->min('default_hourly_rate') ?? 0,
            'max_hourly_rate' => MaintenanceType::where('company_id', $companyId)
                ->max('default_hourly_rate') ?? 0,
            'total_repair_orders' => 0, // TODO: Count when repair orders implemented
        ];
    }

    // Rate distribution analysis
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
                ->whereBetween('default_hourly_rate', [$range['min'], $range['max']])
                ->count();
            $distribution[$label] = $count;
        }

        return $distribution;
    }

    // Most used maintenance types (placeholder for future)
    public function getMostUsedTypesProperty()
    {
        // TODO: Implement when repair orders are ready
        return collect();
    }

    // Livewire lifecycle hooks
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
