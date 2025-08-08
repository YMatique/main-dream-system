<?php

namespace App\Livewire\Company;

use App\Models\Company\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deleteId = null;

    // Form properties
    public $name = '';
    public $code = '';
    public $email = '';
    public $phone = '';
    public $department_id = '';
    public $is_active = true;

    // Filter properties
    public $search = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Data collections
    public $departments = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'code')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('employees', 'email')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'code.required' => 'O código é obrigatório.',
        'code.unique' => 'Este código já está em uso.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está sendo usado.',
        'department_id.required' => 'Selecione um departamento.',
        'department_id.exists' => 'O departamento selecionado não existe.',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',
    ];

    public function mount()
    {
        $this->loadDepartments();
    }

    public function render()
    {
        return view('livewire.company.employee-management', compact('employees'))
            ->title('Gestão de Funcionários')
            ->layout('layouts.company');
    }
     public function getEmployees()
    {
        return Employee::with('department')
            ->where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhereHas('department', function ($deptQuery) {
                          $deptQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('department_id', $this->departmentFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy('name')
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
        $this->code = '';
        $this->email = '';
        $this->phone = '';
        $this->department_id = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->name,
                'code' => $this->code,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'department_id' => $this->department_id,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $employee = Employee::findOrFail($this->editingId);
                $employee->update($data);
                session()->flash('success', 'Funcionário atualizado com sucesso!');
            } else {
                Employee::create($data);
                session()->flash('success', 'Funcionário criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar funcionário: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $employee = Employee::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $employee->name;
        $this->code = $employee->code;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->department_id = $employee->department_id;
        $this->is_active = $employee->is_active;
        
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
            $employee = Employee::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // TODO: Verificar se o funcionário tem ordens de reparação associadas
            // Se tiver, não permitir eliminar ou marcar como inativo
            
            $employee->delete();
            session()->flash('success', 'Funcionário eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar funcionário: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $employee = Employee::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$employee->is_active;
            $employee->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Funcionário {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    public function generateCode()
    {
        // Generate automatic employee code
        $lastEmployee = Employee::where('company_id', auth()->user()->company_id)
            ->orderBy('id', 'desc')
            ->first();
            
        if ($lastEmployee && preg_match('/(\d+)$/', $lastEmployee->code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $this->code = 'FUNC' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $this->code = 'FUNC0001';
        }
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        Employee::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Funcionários ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        Employee::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Funcionários desativados com sucesso!');
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $employees = $this->getEmployees()->items();
        
        // TODO: Implement export service
        // return app(ExportService::class)->exportEmployees($employees, $format);
        
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => Employee::where('company_id', $companyId)->count(),
            'active' => Employee::where('company_id', $companyId)->active()->count(),
            'inactive' => Employee::where('company_id', $companyId)->where('is_active', false)->count(),
            'by_department' => Employee::where('company_id', $companyId)
                ->selectRaw('department_id, COUNT(*) as total')
                ->with('department:id,name')
                ->groupBy('department_id')
                ->get()
        ];
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
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
