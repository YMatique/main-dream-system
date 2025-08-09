<?php

namespace App\Livewire\Company;

use App\Models\Company\Department;
use App\Models\DepartmentEvaluator;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserPermissionManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showUserModal = false;
    public $showPermissionsModal = false;
    public $showDeleteModal = false;
    public $showDepartmentModal = false;

    // User management
    public $editingUserId = null;
    public $deleteUserId = null;
    public $managingUserId = null;

    // User form properties
    public $user_name = '';
    public $user_email = '';
    public $user_password = '';
    public $user_password_confirmation = '';
    public $user_type = 'company_user';

    // Permission management
    public $selectedGroups = [];
    public $selectedPermissions = [];
    public $selectedDepartments = [];

    // Filters
    public $search = '';
    public $userTypeFilter = '';
    public $perPage = 10;
    public $activeTab = 'users'; // 'users', 'groups', 'permissions'

    // Data collections
    public $availableGroups = [];
    public $availablePermissions = [];
    public $availableDepartments = [];

    protected $permissionService;

    public function boot(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    protected function rules()
    {
        $rules = [
            'user_name' => 'required|string|min:2|max:255',
            'user_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId)
            ],
            'user_type' => 'required|in:company_admin,company_user',
        ];

        // Password só é obrigatório na criação
        if (!$this->editingUserId) {
            $rules['user_password'] = 'required|string|min:8|confirmed';
        } elseif ($this->user_password) {
            $rules['user_password'] = 'string|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'user_name.required' => 'O nome é obrigatório.',
        'user_email.required' => 'O email é obrigatório.',
        'user_email.unique' => 'Este email já está sendo usado.',
        'user_password.required' => 'A senha é obrigatória.',
        'user_password.confirmed' => 'A confirmação da senha não confere.',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        $users = $this->getUsers();
        $groups = $this->getPermissionGroups();
        $permissions = $this->getPermissions();
        
        return view('livewire.company.user-permission-management', [
            'users' => $users,
            'groups' => $groups,
            'permissions' => $permissions,
            'stats' => $this->getStats(),
        ])
        ->title('Gestão de Usuários e Permissões')
        ->layout('layouts.company');
    }

    public function loadData()
    {
        $this->availableGroups = PermissionGroup::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->availablePermissions = Permission::orderBy('category')
            ->orderBy('sort_order')
            ->get();

        $this->availableDepartments = Department::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getUsers()
    {
        return User::where('company_id', auth()->user()->company_id)
            ->where('id', '!=', auth()->user()->id) // Não mostrar o próprio usuário
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->userTypeFilter, function ($query) {
                $query->where('user_type', $this->userTypeFilter);
            })
            ->withCount(['permissionGroups', 'userPermissions'])
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    public function getPermissionGroups()
    {
        return PermissionGroup::where('is_active', true)
            ->withCount(['users', 'permissions'])
            ->orderBy('sort_order')
            ->get();
    }

    public function getPermissions()
    {
        return Permission::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
    }

    public function getStats()
    {
        $companyId = auth()->user()->company_id;
        
        return [
            'total_users' => User::where('company_id', $companyId)->count(),
            'company_admins' => User::where('company_id', $companyId)->where('user_type', 'company_admin')->count(),
            'company_users' => User::where('company_id', $companyId)->where('user_type', 'company_user')->count(),
            'users_with_groups' => User::where('company_id', $companyId)->whereHas('permissionGroups')->count(),
            'total_groups' => PermissionGroup::where('is_active', true)->count(),
            'total_permissions' => Permission::count(),
        ];
    }

    // User management methods
    public function createUser()
    {
        $this->resetUserForm();
        $this->showUserModal = true;
    }

    public function editUser($userId)
    {
        $user = User::where('company_id', auth()->user()->company_id)->findOrFail($userId);
        
        $this->editingUserId = $userId;
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->user_type = $user->user_type;
        $this->user_password = '';
        $this->user_password_confirmation = '';
        
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->user_name,
                'email' => $this->user_email,
                'user_type' => $this->user_type,
                'company_id' => auth()->user()->company_id,
            ];

            if ($this->user_password) {
                $data['password'] = Hash::make($this->user_password);
            }

            if ($this->editingUserId) {
                $user = User::findOrFail($this->editingUserId);
                $user->update($data);
                session()->flash('success', 'Usuário atualizado com sucesso!');
            } else {
                $data['password'] = Hash::make($this->user_password);
                $data['email_verified_at'] = now();
                User::create($data);
                session()->flash('success', 'Usuário criado com sucesso!');
            }

            $this->closeUserModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar usuário: ' . $e->getMessage());
        }
    }

    public function confirmDeleteUser($userId)
    {
        $this->deleteUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        try {
            $user = User::where('company_id', auth()->user()->company_id)
                ->where('id', '!=', auth()->user()->id)
                ->findOrFail($this->deleteUserId);
            
            $user->delete();
            session()->flash('success', 'Usuário eliminado com sucesso!');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao eliminar usuário: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deleteUserId = null;
    }

    // Permission management methods
    public function managePermissions($userId)
    {
        $user = User::where('company_id', auth()->user()->company_id)->findOrFail($userId);
        
        $this->managingUserId = $userId;
        
        // Carregar permissões atuais
        $this->selectedGroups = $user->permissionGroups()->pluck('permission_groups.id')->toArray();
        $this->selectedPermissions = $user->userPermissions()
            ->whereNull('department_id')
            ->pluck('permission_id')
            ->toArray();
        
        $this->showPermissionsModal = true;
    }

    public function savePermissions()
    {
        try {
            $user = User::findOrFail($this->managingUserId);

            // Atualizar grupos de permissões
            $user->permissionGroups()->sync($this->selectedGroups);

            // Atualizar permissões individuais
            $user->userPermissions()->whereNull('department_id')->delete();
            
            foreach ($this->selectedPermissions as $permissionId) {
                $this->permissionService->grantPermission(
                    $user,
                    Permission::find($permissionId)->name,
                    null,
                    auth()->user()
                );
            }

            session()->flash('success', 'Permissões atualizadas com sucesso!');
            $this->closePermissionsModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar permissões: ' . $e->getMessage());
        }
    }

    public function manageDepartments($userId)
    {
        $user = User::where('company_id', auth()->user()->company_id)->findOrFail($userId);
        
        $this->managingUserId = $userId;
        
        // Carregar departamentos atuais
        $this->selectedDepartments = DepartmentEvaluator::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('department_id')
            ->toArray();
        
        $this->showDepartmentModal = true;
    }

    public function saveDepartments()
    {
        try {
            $user = User::findOrFail($this->managingUserId);

            // Remover todas as atribuições atuais
            DepartmentEvaluator::where('user_id', $user->id)->delete();

            // Adicionar novas atribuições
            foreach ($this->selectedDepartments as $departmentId) {
                $this->permissionService->assignDepartmentEvaluator(
                    $user,
                    $departmentId,
                    auth()->user()
                );
            }

            session()->flash('success', 'Departamentos atualizados com sucesso!');
            $this->closeDepartmentModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar departamentos: ' . $e->getMessage());
        }
    }

    // Helper methods
    public function resetUserForm()
    {
        $this->editingUserId = null;
        $this->user_name = '';
        $this->user_email = '';
        $this->user_password = '';
        $this->user_password_confirmation = '';
        $this->user_type = 'company_user';
        $this->resetValidation();
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->resetUserForm();
    }

    public function closePermissionsModal()
    {
        $this->showPermissionsModal = false;
        $this->selectedGroups = [];
        $this->selectedPermissions = [];
        $this->managingUserId = null;
    }

    public function closeDepartmentModal()
    {
        $this->showDepartmentModal = false;
        $this->selectedDepartments = [];
        $this->managingUserId = null;
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Quick actions
    public function quickAssignGroup($userId, $groupId)
    {
        try {
            $user = User::where('company_id', auth()->user()->company_id)->findOrFail($userId);
            $this->permissionService->assignPermissionGroup($user, $groupId, auth()->user());
            
            session()->flash('success', 'Grupo atribuído com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atribuir grupo: ' . $e->getMessage());
        }
    }

    public function quickRemoveGroup($userId, $groupId)
    {
        try {
            $user = User::where('company_id', auth()->user()->company_id)->findOrFail($userId);
            $this->permissionService->removePermissionGroup($user, $groupId);
            
            session()->flash('success', 'Grupo removido com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao remover grupo: ' . $e->getMessage());
        }
    }

    public function toggleUserType($userId)
    {
        try {
            $user = User::where('company_id', auth()->user()->company_id)
                ->where('id', '!=', auth()->user()->id)
                ->findOrFail($userId);
            
            $newType = $user->user_type === 'company_admin' ? 'company_user' : 'company_admin';
            $user->update(['user_type' => $newType]);
            
            session()->flash('success', 'Tipo de usuário alterado com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar tipo de usuário: ' . $e->getMessage());
        }
    }

    // Lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUserTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    // public function render()
    // {
    //     return view('livewire.company.user-permission-management');
    // }
}
