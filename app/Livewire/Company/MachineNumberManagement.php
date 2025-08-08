<?php

namespace App\Livewire\Company;

use App\Models\Company\MachineNumber;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MachineNumberManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showImportModal = false;
    public $editingId = null;
    public $deleteId = null;

    // Form properties
    public $number = '';
    public $description = '';
    public $is_active = true;

    // Import properties
    public $import_numbers = '';
    public $import_separator = 'line'; // 'line', 'comma', 'semicolon'

    // Filter properties
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortBy = 'number';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('machine_numbers', 'number')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'number.required' => 'O número da máquina é obrigatório.',
        'number.unique' => 'Este número de máquina já existe.',
        'number.max' => 'O número deve ter no máximo 50 caracteres.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
    ];

    public function render()
    {
        $machineNumbers = $this->getMachineNumbers();
        return view('livewire.company.machine-number-management', compact('machineNumbers'))
            ->title('Números de Máquina')
            ->layout('layouts.company');
    }

     public function getMachineNumbers()
    {
        return MachineNumber::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('number', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
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
        $this->number = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'number' => $this->number,
                'description' => $this->description ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $machineNumber = MachineNumber::findOrFail($this->editingId);
                $machineNumber->update($data);
                session()->flash('success', 'Número de máquina atualizado com sucesso!');
            } else {
                MachineNumber::create($data);
                session()->flash('success', 'Número de máquina criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar número de máquina: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $machineNumber = MachineNumber::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->number = $machineNumber->number;
        $this->description = $machineNumber->description;
        $this->is_active = $machineNumber->is_active;
        
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
            $machineNumber = MachineNumber::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // TODO: Check if machine number is being used in repair orders
            
            $machineNumber->delete();
            session()->flash('success', 'Número de máquina eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar número de máquina: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $machineNumber = MachineNumber::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$machineNumber->is_active;
            $machineNumber->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Número de máquina {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    // Import functionality
    public function openImportModal()
    {
        $this->import_numbers = '';
        $this->import_separator = 'line';
        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->import_numbers = '';
    }

    public function importMachineNumbers()
    {
        $this->validate([
            'import_numbers' => 'required|string|min:1',
            'import_separator' => 'required|in:line,comma,semicolon'
        ]);

        try {
            // Determine separator
            $separator = match($this->import_separator) {
                'comma' => ',',
                'semicolon' => ';',
                default => "\n"
            };

            // Split and clean numbers
            $numbers = array_map('trim', explode($separator, $this->import_numbers));
            $numbers = array_filter($numbers); // Remove empty values
            $numbers = array_unique($numbers); // Remove duplicates

            $imported = 0;
            $errors = [];

            foreach ($numbers as $number) {
                if (strlen($number) > 50) {
                    $errors[] = "'{$number}' excede 50 caracteres";
                    continue;
                }

                // Check if already exists
                $exists = MachineNumber::where('company_id', auth()->user()->company_id)
                    ->where('number', $number)
                    ->exists();

                if (!$exists) {
                    MachineNumber::create([
                        'company_id' => auth()->user()->company_id,
                        'number' => $number,
                        'description' => null,
                        'is_active' => true,
                    ]);
                    $imported++;
                }
            }

            $message = "{$imported} números importados com sucesso!";
            if (!empty($errors)) {
                $message .= " Erros: " . implode(', ', array_slice($errors, 0, 3));
            }

            session()->flash('success', $message);
            $this->closeImportModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }

    // Generate sequential numbers
    public function generateSequentialNumbers($prefix, $start, $end, $digits = 4)
    {
        try {
            if ($start > $end || $end - $start > 1000) {
                session()->flash('error', 'Intervalo inválido ou muito grande (máx. 1000)');
                return;
            }

            $created = 0;
            for ($i = $start; $i <= $end; $i++) {
                $number = $prefix . str_pad($i, $digits, '0', STR_PAD_LEFT);
                
                $exists = MachineNumber::where('company_id', auth()->user()->company_id)
                    ->where('number', $number)
                    ->exists();

                if (!$exists) {
                    MachineNumber::create([
                        'company_id' => auth()->user()->company_id,
                        'number' => $number,
                        'description' => "Gerado automaticamente",
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }

            session()->flash('success', "{$created} números sequenciais criados!");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao gerar números: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        MachineNumber::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Números de máquina ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        MachineNumber::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Números de máquina desativados com sucesso!');
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

    // Export functionality
    public function export($format = 'excel')
    {
        // TODO: Implement export service
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => MachineNumber::where('company_id', $companyId)->count(),
            'active' => MachineNumber::where('company_id', $companyId)
                ->where('is_active', true)->count(),
            'inactive' => MachineNumber::where('company_id', $companyId)
                ->where('is_active', false)->count(),
            'total_usage' => 0, // TODO: Count usage in repair orders
        ];
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

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
