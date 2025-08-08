<?php

namespace App\Livewire\Company;

use App\Models\Company\Requester;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class RequesterManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deleteId = null;

    // Form properties
    public $name = '';
    public $email = '';
    public $phone = '';
    public $department = '';
    public $is_active = true;

    // Filter properties
    public $search = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('requesters', 'email')
                    ->where('company_id', auth()->user()->company_id)
                    ->ignore($this->editingId)
            ],
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está sendo usado.',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',
        'department.max' => 'O departamento deve ter no máximo 100 caracteres.',
    ];

    public function render()
    {
        $requesters = $this->getRequesters();
        $departments = $this->getAvailableDepartments();
        
        return view('livewire.company.requester-management', compact('requesters', 'departments'))
            ->title('Solicitantes')
            ->layout('layouts.company');
    }
     public function getRequesters()
    {
        return Requester::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('department', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('department', $this->departmentFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getAvailableDepartments()
    {
        return Requester::where('company_id', auth()->user()->company_id)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->select('department')
            ->distinct()
            ->pluck('department');
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
        $this->email = '';
        $this->phone = '';
        $this->department = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'company_id' => auth()->user()->company_id,
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'department' => $this->department ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $requester = Requester::findOrFail($this->editingId);
                $requester->update($data);
                session()->flash('success', 'Solicitante atualizado com sucesso!');
            } else {
                Requester::create($data);
                session()->flash('success', 'Solicitante criado com sucesso!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar solicitante: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $requester = Requester::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $requester->name;
        $this->email = $requester->email;
        $this->phone = $requester->phone;
        $this->department = $requester->department;
        $this->is_active = $requester->is_active;
        
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
            $requester = Requester::where('company_id', auth()->user()->company_id)
                ->findOrFail($this->deleteId);
            
            // TODO: Check if requester is being used in repair orders
            
            $requester->delete();
            session()->flash('success', 'Solicitante eliminado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar solicitante: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        try {
            $requester = Requester::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newStatus = !$requester->is_active;
            $requester->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'ativado' : 'desativado';
            session()->flash('success', "Solicitante {$statusText} com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    // Create default requesters
    public function createDefaultRequesters()
    {
        try {
            $defaultRequesters = [
                ['name' => 'Gestor de Produção', 'department' => 'Produção', 'email' => null],
                ['name' => 'Supervisor de Manutenção', 'department' => 'Manutenção', 'email' => null],
                ['name' => 'Coordenador de Qualidade', 'department' => 'Qualidade', 'email' => null],
                ['name' => 'Chefe de Oficina', 'department' => 'Oficina', 'email' => null],
                ['name' => 'Técnico Sénior', 'department' => 'Técnico', 'email' => null],
                ['name' => 'Administrador', 'department' => 'Administração', 'email' => null],
            ];

            $created = 0;
            foreach ($defaultRequesters as $req) {
                $exists = Requester::where('company_id', auth()->user()->company_id)
                    ->where('name', $req['name'])
                    ->exists();
                
                if (!$exists) {
                    Requester::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => $req['name'],
                        'department' => $req['department'],
                        'email' => $req['email'],
                        'phone' => null,
                        'is_active' => true
                    ]);
                    $created++;
                }
            }
            
            session()->flash('success', "{$created} solicitantes padrão criados com sucesso!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar solicitantes padrão: ' . $e->getMessage());
        }
    }

    // Duplicate requester
    public function duplicateRequester($id)
    {
        try {
            $requester = Requester::where('company_id', auth()->user()->company_id)
                ->findOrFail($id);
            
            $newRequester = $requester->replicate();
            $newRequester->name = $requester->name . ' (Cópia)';
            $newRequester->email = null; // Clear unique fields
            $newRequester->save();
            
            session()->flash('success', 'Solicitante duplicado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao duplicar solicitante: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function bulkActivate($ids)
    {
        Requester::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => true]);
            
        session()->flash('success', 'Solicitantes ativados com sucesso!');
    }

    public function bulkDeactivate($ids)
    {
        Requester::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['is_active' => false]);
            
        session()->flash('success', 'Solicitantes desativados com sucesso!');
    }

    public function bulkUpdateDepartment($ids, $department)
    {
        Requester::where('company_id', auth()->user()->company_id)
            ->whereIn('id', $ids)
            ->update(['department' => $department]);
            
        session()->flash('success', 'Departamentos atualizados com sucesso!');
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
            'total' => Requester::where('company_id', $companyId)->count(),
            'active' => Requester::where('company_id', $companyId)
                ->where('is_active', true)->count(),
            'inactive' => Requester::where('company_id', $companyId)
                ->where('is_active', false)->count(),
            'with_email' => Requester::where('company_id', $companyId)
                ->whereNotNull('email')->count(),
            'with_phone' => Requester::where('company_id', $companyId)
                ->whereNotNull('phone')->count(),
            'by_department' => Requester::where('company_id', $companyId)
                ->selectRaw('department, COUNT(*) as count')
                ->whereNotNull('department')
                ->groupBy('department')
                ->pluck('count', 'department'),
            'total_requests' => 0, // TODO: Count usage in repair orders
        ];
    }

    // Get requesters by department
    public function getRequestersByDepartmentProperty()
    {
        return Requester::where('company_id', auth()->user()->company_id)
            ->selectRaw('department, COUNT(*) as count, GROUP_CONCAT(name) as names')
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->where('is_active', true)
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get();
    }

    // Get most active requesters (placeholder for future)
    public function getMostActiveRequestersProperty()
    {
        // TODO: Implement when repair orders are ready
        return collect();
    }

    // Send notification to requester
    public function sendNotificationToRequester($requesterId, $message)
    {
        try {
            $requester = Requester::where('company_id', auth()->user()->company_id)
                ->findOrFail($requesterId);

            if (!$requester->email) {
                session()->flash('error', 'Solicitante não tem email cadastrado.');
                return;
            }

            // TODO: Implement notification service
            session()->flash('info', 'Serviço de notificações em desenvolvimento...');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao enviar notificação: ' . $e->getMessage());
        }
    }

    // Import from CSV/Excel
    public function importRequestersFromFile($filePath)
    {
        // TODO: Implement file import functionality
        session()->flash('info', 'Importação de arquivos em desenvolvimento...');
    }

    // Sync with company departments
    public function syncWithDepartments()
    {
        try {
            $departments = \App\Models\Company\Department::where('company_id', auth()->user()->company_id)
                ->active()
                ->get();

            $created = 0;
            foreach ($departments as $dept) {
                $exists = Requester::where('company_id', auth()->user()->company_id)
                    ->where('name', 'Responsável - ' . $dept->name)
                    ->exists();

                if (!$exists) {
                    Requester::create([
                        'company_id' => auth()->user()->company_id,
                        'name' => 'Responsável - ' . $dept->name,
                        'department' => $dept->name,
                        'email' => null,
                        'phone' => null,
                        'is_active' => true
                    ]);
                    $created++;
                }
            }

            session()->flash('success', "{$created} solicitantes sincronizados com departamentos!");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao sincronizar: ' . $e->getMessage());
        }
    }

    // Contact information validation
    public function validateContactInfo($requesterId)
    {
        try {
            $requester = Requester::where('company_id', auth()->user()->company_id)
                ->findOrFail($requesterId);

            $issues = [];
            
            if (!$requester->email && !$requester->phone) {
                $issues[] = 'Sem informações de contacto';
            }
            
            if ($requester->email && !filter_var($requester->email, FILTER_VALIDATE_EMAIL)) {
                $issues[] = 'Email inválido';
            }

            if (empty($issues)) {
                session()->flash('success', 'Informações de contacto válidas!');
            } else {
                session()->flash('warning', 'Problemas encontrados: ' . implode(', ', $issues));
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Erro na validação: ' . $e->getMessage());
        }
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
