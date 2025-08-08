<?php

namespace App\Livewire\Company;

use App\Models\Company\Department;
use App\Models\Company\Employee;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DepartmentManagement extends Component
{
      use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $showEmployeesModal = false;
    public $editingId = null;
    public $deleteId = null;
    public $viewingEmployeesId = null;

    // Form properties
    public $name = '';
    public $description = '';
    public $is_active = true;

    // Filter properties
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('departments', 'name')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'name.unique' => 'Já existe um departamento com este nome.',
        'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
    ];

    public function render()
    {
         $departments = $this->getDepartments();
        return view('livewire.company.department-management', compact('departments'))
            ->title('Gestão de Departamentos')
            ->layout('layouts.company');
    }
     public function getDepartments()
    {
        return Department::withCount(['employees'])
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
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $department = Department::findOrFail($this->editingId);
                $department->update($data);
                session()->flash('success', 'Departamento atualizado com sucesso!');
            } else {
                Department::create($data);
                session()->flash('success', 'Departamento criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar departamento: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $department = Department::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->is_active = $department->is_active;
        
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
            $department = Department::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // Check if department has employees
            if ($department->employees()->exists()) {
                session()->flash('error', 'Não é possível eliminar departamento com funcionários associados. Desative-o em vez disso.');
                $this->showDeleteModal = false;
                return;
            }
            
            $department->delete();
            session()->flash('success', 'Departamento eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar departamento: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $department = Department::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$department->is_active;
            
            // If deactivating, check for active employees
            if (!$newStatus && $department->employees()->where('is_active', true)->exists()) {
                session()->flash('error', 'Não é possível desativar departamento com funcionários ativos.');
                return;
            }
            
            $department->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Departamento {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    public function viewEmployees($departmentId)
    {
        $this->viewingEmployeesId = $departmentId;
        $this->showEmployeesModal = true;
    }

    public function closeEmployeesModal()
    {
        $this->showEmployeesModal = false;
        $this->viewingEmployeesId = null;
    }

    public function getDepartmentEmployeesProperty()
    {
        if (!$this->viewingEmployeesId) return collect();
        
        return Employee::where('company_id', auth()->user()->company_id)
            ->where('department_id', $this->viewingEmployeesId)
            ->orderBy('name')
            ->get();
    }

    public function getDepartmentInfoProperty()
    {
        if (!$this->viewingEmployeesId) return null;
        
        return Department::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->viewingEmployeesId);
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

    // Create default departments
    public function createDefaultDepartments()
    {
        try {
            $defaultDepartments = [
                ['name' => 'Manutenção Elétrica', 'description' => 'Responsável por reparações elétricas'],
                ['name' => 'Manutenção Mecânica', 'description' => 'Responsável por reparações mecânicas'],
                ['name' => 'Soldadura', 'description' => 'Serviços de soldadura e metalurgia'],
                ['name' => 'Pintura', 'description' => 'Serviços de pintura e acabamentos'],
                ['name' => 'Carpintaria', 'description' => 'Trabalhos em madeira e carpintaria'],
                ['name' => 'Plumbing', 'description' => 'Instalações hidráulicas e sanitárias'],
                ['name' => 'Administração', 'description' => 'Gestão administrativa e financeira'],
            ];

            $created = 0;
            foreach ($defaultDepartments as $dept) {
                $exists = Department::where('company_id', auth()->user()->company_id)
                    ->where('name', $dept['name'])
                    ->exists();
                
                if (!$exists) {
                    Department::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => $dept['name'],
                        'description' => $dept['description'],
                        'is_active' => true
                    ]);
                    $created++;
                }
            }
            
            if ($created > 0) {
                session()->flash('success', "{$created} departamentos padrão criados com sucesso!");
            } else {
                session()->flash('info', 'Todos os departamentos padrão já existem.');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar departamentos padrão: ' . $e->getMessage());
        }
    }

    // Duplicate department
    public function duplicateDepartment($id)
    {
        try {
            $department = Department::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newDepartment = $department->replicate();
            $newDepartment->name = $department->name . ' (Cópia)';
            $newDepartment->save();
            
            session()->flash('success', 'Departamento duplicado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar departamento: ' . $e->getMessage());
        }
    }

    // Transfer employees between departments
    public function transferEmployees($fromDepartmentId, $toDepartmentId)
    {
        try {
            $fromDepartment = Department::where('company_id', auth()->user()->company_id)
                ->findOrFail($fromDepartmentId);
            
            $toDepartment = Department::where('company_id', auth()->user()->company_id)
                ->findOrFail($toDepartmentId);

            $employeeCount = $fromDepartment->employees()->count();
            
            if ($employeeCount === 0) {
                session()->flash('info', 'Não há funcionários para transferir.');
                return;
            }

            $fromDepartment->employees()->update(['department_id' => $toDepartmentId]);
            
            session()->flash('success', "{$employeeCount} funcionários transferidos de '{$fromDepartment->name}' para '{$toDepartment->name}'.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao transferir funcionários: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        Department::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Departamentos ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        // Check for active employees in each department
        $departmentsWithActiveEmployees = Department::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->whereHas('employees', function($query) {
                $query->where('is_active', true);
            })
            ->pluck('name');

        if ($departmentsWithActiveEmployees->isNotEmpty()) {
            session()->flash('error', 'Não é possível desativar departamentos com funcionários ativos: ' . 
                $departmentsWithActiveEmployees->implode(', '));
            return;
        }

        Department::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Departamentos desativados com sucesso!');
    }

    // Export functionality
    public function export($format = 'excel')
    {
        $departments = $this->getDepartments()->items();
        
        // TODO: Implement export service
        session()->flash('info', 'Exportação em desenvolvimento...');
    }

    // Statistics
    public function getStatsProperty()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total' => Department::where('company_id', $companyId)->count(),
            'active' => Department::where('company_id', $companyId)->active()->count(),
            'inactive' => Department::where('company_id', $companyId)->where('is_active', false)->count(),
            'with_employees' => Department::where('company_id', $companyId)
                ->whereHas('employees')
                ->count(),
            'empty' => Department::where('company_id', $companyId)
                ->whereDoesntHave('employees')
                ->count(),
            'total_employees' => Employee::where('company_id', $companyId)->count(),
            'employees_distribution' => Department::where('company_id', $companyId)
                ->withCount('employees')
                ->get()
                ->mapWithKeys(function ($dept) {
                    return [$dept->name => $dept->employees_count];
                })
        ];
    }

    // Department hierarchy (if needed for organizational chart)
    public function getDepartmentHierarchyProperty()
    {
        // This could be extended to support parent-child relationships
        return Department::where('company_id', auth()->user()->company_id)
            ->with(['employees' => function($query) {
                $query->active()->orderBy('name');
            }])
            ->active()
            ->orderBy('name')
            ->get();
    }

    // Performance metrics by department (placeholder for future implementation)
    public function getDepartmentPerformanceProperty()
    {
        // TODO: Calculate performance metrics when evaluation system is implemented
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

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
