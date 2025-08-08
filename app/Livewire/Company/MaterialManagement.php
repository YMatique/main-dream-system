<?php

namespace App\Livewire\Company;

use App\Models\Company\Material;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MaterialManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showStockModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $adjustingStockId = null;

    // Form properties
    public $name = '';
    public $description = '';
    public $unit = '';
    public $cost_per_unit_mzn = 0;
    public $cost_per_unit_usd = 0;
    public $stock_quantity = 0;
    public $min_stock_alert = 0;
    public $supplier = '';
    public $is_active = true;

    // Stock adjustment
    public $stock_adjustment = 0;
    public $adjustment_type = 'add'; // 'add' or 'remove'
    public $adjustment_reason = '';

    // Filter properties
    public $search = '';
    public $unitFilter = '';
    public $stockFilter = ''; // 'low', 'out', 'available'
    public $statusFilter = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Common units
    public $commonUnits = [
        'peça' => 'Peça',
        'metro' => 'Metro',
        'litro' => 'Litro',
        'kg' => 'Quilograma',
        'caixa' => 'Caixa',
        'pacote' => 'Pacote',
        'rolo' => 'Rolo',
        'tubo' => 'Tubo',
        'saco' => 'Saco',
        'unidade' => 'Unidade'
    ];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('materials', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'description' => 'nullable|string|max:500',
            'unit' => 'required|string|max:50',
            'cost_per_unit_mzn' => 'required|numeric|min:0|max:999999.99',
            'cost_per_unit_usd' => 'required|numeric|min:0|max:999999.99',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'name.unique' => 'Já existe um material com este nome.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
        'unit.required' => 'A unidade é obrigatória.',
        'cost_per_unit_mzn.required' => 'O custo em MZN é obrigatório.',
        'cost_per_unit_mzn.numeric' => 'O custo em MZN deve ser um número.',
        'cost_per_unit_usd.required' => 'O custo em USD é obrigatório.',
        'cost_per_unit_usd.numeric' => 'O custo em USD deve ser um número.',
        'stock_quantity.required' => 'A quantidade em stock é obrigatória.',
        'stock_quantity.integer' => 'A quantidade deve ser um número inteiro.',
        'min_stock_alert.required' => 'O alerta de stock mínimo é obrigatório.',
        'min_stock_alert.integer' => 'O alerta deve ser um número inteiro.',
        'supplier.max' => 'O fornecedor deve ter no máximo 255 caracteres.',
    ];
    public function render()
    {
         $materials = $this->getMaterials();
        $units = $this->getAvailableUnits();
        return view('livewire.company.material-management',compact('materials', 'units'))
            ->title('Gestão de Materiais')
            ->layout('layouts.company');
    }
     public function getMaterials()
    {
        return Material::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('unit', 'like', '%' . $this->search . '%')
                      ->orWhere('supplier', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->unitFilter, function ($query) {
                $query->where('unit', $this->unitFilter);
            })
            ->when($this->stockFilter, function ($query) {
                switch ($this->stockFilter) {
                    case 'low':
                        $query->whereRaw('stock_quantity <= min_stock_alert AND stock_quantity > 0');
                        break;
                    case 'out':
                        $query->where('stock_quantity', 0);
                        break;
                    case 'available':
                        $query->where('stock_quantity', '>', 0);
                        break;
                }
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getAvailableUnits()
    {
        return Material::where('company_id', auth()->user()->company_id)
            ->select('unit')
            ->distinct()
            ->pluck('unit');
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
        $this->unit = '';
        $this->cost_per_unit_mzn = 0;
        $this->cost_per_unit_usd = 0;
        $this->stock_quantity = 0;
        $this->min_stock_alert = 0;
        $this->supplier = '';
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
                'unit' => $this->unit,
                'cost_per_unit_mzn' => $this->cost_per_unit_mzn,
                'cost_per_unit_usd' => $this->cost_per_unit_usd,
                'stock_quantity' => $this->stock_quantity,
                'min_stock_alert' => $this->min_stock_alert,
                'supplier' => $this->supplier ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $material = Material::findOrFail($this->editingId);
                $material->update($data);
                session()->flash('success', 'Material atualizado com sucesso!');
            } else {
                Material::create($data);
                session()->flash('success', 'Material criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar material: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $material = Material::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $material->name;
        $this->description = $material->description;
        $this->unit = $material->unit;
        $this->cost_per_unit_mzn = $material->cost_per_unit_mzn;
        $this->cost_per_unit_usd = $material->cost_per_unit_usd;
        $this->stock_quantity = $material->stock_quantity;
        $this->min_stock_alert = $material->min_stock_alert;
        $this->supplier = $material->supplier;
        $this->is_active = $material->is_active;
        
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
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // TODO: Check if material is used in repair orders
            
            $material->delete();
            session()->flash('success', 'Material eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar material: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$material->is_active;
            $material->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Material {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    // Stock management
    public function openStockModal($id)
    {
        $this->adjustingStockId = $id;
        $this->stock_adjustment = 0;
        $this->adjustment_type = 'add';
        $this->adjustment_reason = '';
        $this->showStockModal = true;
    }

    public function closeStockModal()
    {
        $this->showStockModal = false;
        $this->adjustingStockId = null;
        $this->stock_adjustment = 0;
        $this->adjustment_reason = '';
    }

    public function adjustStock()
    {
        $this->validate([
            'stock_adjustment' => 'required|integer|min:1',
            'adjustment_reason' => 'required|string|max:255'
        ], [
            'stock_adjustment.required' => 'A quantidade de ajuste é obrigatória.',
            'stock_adjustment.min' => 'A quantidade deve ser maior que zero.',
            'adjustment_reason.required' => 'O motivo do ajuste é obrigatório.'
        ]);

        try {
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->adjustingStockId);

            $oldQuantity = $material->stock_quantity;
            
            if ($this->adjustment_type === 'add') {
                $newQuantity = $oldQuantity + $this->stock_adjustment;
            } else {
                $newQuantity = max(0, $oldQuantity - $this->stock_adjustment);
            }

            $material->update(['stock_quantity' => $newQuantity]);

            // TODO: Log stock adjustment history
            
            $actionText = $this->adjustment_type === 'add' ? 'adicionada' : 'removida';
            session()->flash('success', "Quantidade {$actionText} com sucesso! Stock atual: {$newQuantity}");
            
            $this->closeStockModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao ajustar stock: ' . $e->getMessage());
        }
    }

    // Quick stock actions
    public function quickAddStock($id, $quantity)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
                
            $material->increment('stock_quantity', $quantity);
            session()->flash('success', "{$quantity} unidades adicionadas ao stock!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao adicionar stock: ' . $e->getMessage());
        }
    }

    public function markAsOutOfStock($id)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
                
            $material->update(['stock_quantity' => 0]);
            session()->flash('info', 'Material marcado como fora de stock.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro: ' . $e->getMessage());
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

    // Bulk actions
    public function bulkUpdatePrices($percentage, $currency = 'both')
    {
        try {
            $materials = Material::where('company_id', auth()->user()->company_id)
                ->where('is_active', true)
                ->get();

            foreach ($materials as $material) {
                if ($currency === 'mzn' || $currency === 'both') {
                    $newPriceMzn = $material->cost_per_unit_mzn * (1 + $percentage / 100);
                    $material->cost_per_unit_mzn = round($newPriceMzn, 2);
                }
                
                if ($currency === 'usd' || $currency === 'both') {
                    $newPriceUsd = $material->cost_per_unit_usd * (1 + $percentage / 100);
                    $material->cost_per_unit_usd = round($newPriceUsd, 2);
                }
                
                $material->save();
            }

            session()->flash('success', 'Preços atualizados em massa com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar preços: ' . $e->getMessage());
        }
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $materials = $this->getMaterials()->items();
        
        // TODO: Implement export service
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => Material::where('company_id', $companyId)->count(),
            'active' => Material::where('company_id', $companyId)->active()->count(),
            'low_stock' => Material::where('company_id', $companyId)
                ->whereRaw('stock_quantity <= min_stock_alert AND stock_quantity > 0')
                ->count(),
            'out_of_stock' => Material::where('company_id', $companyId)
                ->where('stock_quantity', 0)
                ->count(),
            'total_value_mzn' => Material::where('company_id', $companyId)
                ->selectRaw('SUM(stock_quantity * cost_per_unit_mzn) as total')
                ->value('total') ?? 0,
            'total_value_usd' => Material::where('company_id', $companyId)
                ->selectRaw('SUM(stock_quantity * cost_per_unit_usd) as total')
                ->value('total') ?? 0,
        ];
    }

    // Low stock alerts
    public function getLowStockMaterialsProperty()
    {
        return Material::where('company_id', auth()->user()->company_id)
            ->whereRaw('stock_quantity <= min_stock_alert AND stock_quantity > 0')
            ->orderBy('stock_quantity')
            ->get();
    }

    // Most used materials
    public function getMostUsedMaterialsProperty()
    {
        // TODO: Implement when repair orders are ready
        return collect();
    }

    // Duplicate material
    public function duplicateMaterial($id)
    {
        try {
            $material = Material::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newMaterial = $material->replicate();
            $newMaterial->name = $material->name . ' (Cópia)';
            $newMaterial->stock_quantity = 0; // Reset stock for duplicate
            $newMaterial->save();
            
            session()->flash('success', 'Material duplicado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar material: ' . $e->getMessage());
        }
    }

    // Print barcode/labels
    public function printLabels($ids)
    {
        // TODO: Implement barcode/label printing
        session()->flash('info', 'Impressão de etiquetas em desenvolvimento...');
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
