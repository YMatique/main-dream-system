<?php

namespace App\Livewire\Company;

use App\Models\Company\Material;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MaterialManagement extends Component
{
use WithPagination;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $name;
    public $description;
    public $unit;
    public $cost_per_unit_mzn;
    public $cost_per_unit_usd;
    public $stock_quantity;
    public $is_active = true;
    public $search = '';
    public $statusFilter = '';
    public $costRangeMin = null;
    public $costRangeMax = null;
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $selectedMaterials = [];
    public $selectAll = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'unit' => 'required|string|max:50',
        'cost_per_unit_mzn' => 'required|numeric|min:0',
        'cost_per_unit_usd' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->resetForm();
        Log::info('MaterialManagement mounted', ['user_id' => auth()->id(), 'company_id' => auth()->user()->company_id ?? 'none']);
    }

    public function getStatsProperty()
    {
        try {
            $query = Material::where('company_id', auth()->user()->company_id);
            return [
                'total' => $query->count(),
                'active' => $query->active()->count(),
                'inactive' => $query->where('is_active', false)->count(),
                'avg_cost_per_unit_mzn' => $query->avg('cost_per_unit_mzn') ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error in getStatsProperty: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'avg_cost_per_unit_mzn' => 0,
            ];
        }
    }

    public function getMaterialsProperty()
    {
        try {
            $query = Material::where('company_id', auth()->user()->company_id);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->statusFilter !== '') {
                $query->where('is_active', $this->statusFilter);
            }

            if ($this->costRangeMin !== null) {
                $query->where('cost_per_unit_mzn', '>=', $this->costRangeMin);
            }

            if ($this->costRangeMax !== null) {
                $query->where('cost_per_unit_mzn', '<=', $this->costRangeMax);
            }

            $query->orderBy($this->sortBy, $this->sortDirection);

            $paginated = $query->paginate(10);
            Log::info('Materials fetched', [
                'count' => $paginated->count(),
                'sample' => $paginated->items() ? get_class($paginated->items()[0] ?? null) : 'empty',
            ]);

            return $paginated;
        } catch (\Exception $e) {
            Log::error('Error in getMaterialsProperty: ' . $e->getMessage(), ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);
            session()->flash('error', 'Erro ao carregar materiais: ' . $e->getMessage());
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }
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

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = null;
        $this->description = null;
        $this->unit = null;
        $this->cost_per_unit_mzn = null;
        $this->cost_per_unit_usd = null;
        $this->stock_quantity = 0;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)->findOrFail($id);
            $this->editingId = $id;
            $this->name = $material->name;
            $this->description = $material->description;
            $this->unit = $material->unit;
            $this->cost_per_unit_mzn = $material->cost_per_unit_mzn;
            $this->cost_per_unit_usd = $material->cost_per_unit_usd;
            $this->stock_quantity = $material->stock_quantity;
            $this->is_active = $material->is_active;
            $this->showModal = true;
        } catch (\Exception $e) {
            Log::error('Error in edit: ' . $e->getMessage());
            session()->flash('error', 'Erro ao carregar material para edição: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        try {
            Material::updateOrCreate(
                [
                    'id' => $this->editingId,
                    'company_id' => auth()->user()->company_id,
                ],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'unit' => $this->unit,
                    'cost_per_unit_mzn' => $this->cost_per_unit_mzn,
                    'cost_per_unit_usd' => $this->cost_per_unit_usd,
                    'stock_quantity' => $this->stock_quantity,
                    'is_active' => $this->is_active,
                ]
            );

            session()->flash('success', $this->editingId ? 'Material atualizado com sucesso!' : 'Material criado com sucesso!');
            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Error in save: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)->findOrFail($id);
            $material->update(['is_active' => !$material->is_active]);
            session()->flash('success', 'Status do material atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error in toggleStatus: ' . $e->getMessage());
            session()->flash('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->editingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            Material::where('company_id', auth()->user()->company_id)
                ->where('id', $this->editingId)
                ->delete();

            session()->flash('success', 'Material eliminado com sucesso!');
            $this->showDeleteModal = false;
            $this->editingId = null;
        } catch (\Exception $e) {
            Log::error('Error in delete: ' . $e->getMessage());
            session()->flash('error', 'Erro ao eliminar: ' . $e->getMessage());
        }
    }

    public function bulkActivate(array $ids)
    {
        try {
            Material::where('company_id', auth()->user()->company_id)
                ->whereIn('id', array_keys(array_filter($this->selectedMaterials)))
                ->update(['is_active' => true]);

            session()->flash('success', 'Materiais ativados com sucesso!');
            $this->selectedMaterials = [];
            $this->selectAll = false;
        } catch (\Exception $e) {
            Log::error('Error in bulkActivate: ' . $e->getMessage());
            session()->flash('error', 'Erro ao ativar materiais: ' . $e->getMessage());
        }
    }

    public function bulkDeactivate(array $ids)
    {
        try {
            Material::where('company_id', auth()->user()->company_id)
                ->whereIn('id', array_keys(array_filter($this->selectedMaterials)))
                ->update(['is_active' => false]);

            session()->flash('success', 'Materiais desativados com sucesso!');
            $this->selectedMaterials = [];
            $this->selectAll = false;
        } catch (\Exception $e) {
            Log::error('Error in bulkDeactivate: ' . $e->getMessage());
            session()->flash('error', 'Erro ao desativar materiais: ' . $e->getMessage());
        }
    }

    public function bulkUpdateCosts($percentage)
    {
        try {
            Material::where('company_id', auth()->user()->company_id)
                ->whereIn('id', array_keys(array_filter($this->selectedMaterials)))
                ->update([
                    'cost_per_unit_mzn' => \DB::raw("cost_per_unit_mzn * (1 + $percentage / 100)"),
                    'cost_per_unit_usd' => \DB::raw("cost_per_unit_usd * (1 + $percentage / 100)"),
                ]);

            session()->flash('success', 'Custos atualizados com sucesso!');
            $this->selectedMaterials = [];
            $this->selectAll = false;
        } catch (\Exception $e) {
            Log::error('Error in bulkUpdateCosts: ' . $e->getMessage());
            session()->flash('error', 'Erro ao atualizar custos: ' . $e->getMessage());
        }
    }

    public function updatedSelectAll($value)
    {
        try {
            $this->selectedMaterials = $value
                ? $this->materials->pluck('id')->mapWithKeys(fn($id) => [$id => true])->toArray()
                : [];
        } catch (\Exception $e) {
            Log::error('Error in updatedSelectAll: ' . $e->getMessage());
            session()->flash('error', 'Erro ao selecionar materiais: ' . $e->getMessage());
        }
    }

    public function export($format = 'excel')
    {
        session()->flash('info', 'Funcionalidade de exportação em desenvolvimento.');
    }

    public function log($message)
    {
        Log::info($message);
    }

    public function render()
    {
        return view('livewire.company.material-management', [
            'materials' => $this->materials,
        ])->layout('layouts.company');
    }
    // Currency conversion helper
    public function convertCurrency($amount, $from, $to, $rate = 65) // Default MZN/USD rate
    {
        if ($from === $to) return $amount;

        return $from === 'usd'
            ? $amount * $rate
            : $amount / $rate;
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
