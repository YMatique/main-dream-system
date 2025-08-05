<?php

namespace App\Livewire\System;

use App\Models\System\Company;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $company_id = '';
    public $user_type = 'company_user';
    public $phone = '';
    public $permissions = [];
    public $is_active = true;
    public $send_welcome_email = true;

    // Filter properties
    public $search = '';
    public $companyFilter = '';
    public $userTypeFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Data collections
    public $companies = [];

    // Available permissions for company users
    public $availablePermissions = [
        'repair_orders.create' => 'Criar Ordens de Reparação',
        'repair_orders.edit' => 'Editar Ordens de Reparação',
        'repair_orders.delete' => 'Eliminar Ordens de Reparação',
        'repair_orders.view' => 'Ver Ordens de Reparação',
        'repair_orders.export' => 'Exportar Ordens de Reparação',
        'employees.manage' => 'Gerir Funcionários',
        'clients.manage' => 'Gerir Clientes',
        'materials.manage' => 'Gerir Materiais',
        'departments.manage' => 'Gerir Departamentos',
        'billing.view' => 'Ver Faturação',
        'billing.manage' => 'Gerir Faturação',
        'performance.view' => 'Ver Avaliações de Desempenho',
        'performance.manage' => 'Gerir Avaliações de Desempenho',
        'reports.view' => 'Ver Relatórios',
        'reports.export' => 'Exportar Relatórios',
        'settings.manage' => 'Gerir Configurações',
    ];

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:6|confirmed',
        'company_id' => 'required_unless:user_type,super_admin|exists:companies,id',
        'user_type' => 'required|in:super_admin,company_admin,company_user',
        'phone' => 'nullable|string|max:20',
        'permissions' => 'array',
        'permissions.*' => 'string',
        'is_active' => 'boolean',
        'send_welcome_email' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Por favor, insira um email válido.',
        'email.unique' => 'Este email já está em uso.',
        'password.required' => 'A senha é obrigatória.',
        'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        'password.confirmed' => 'A confirmação da senha não confere.',
        'company_id.required_unless' => 'Selecione uma empresa (exceto para Super Admin).',
        'company_id.exists' => 'A empresa selecionada não existe.',
        'user_type.required' => 'Selecione um tipo de usuário.',
        'user_type.in' => 'O tipo de usuário selecionado é inválido.',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',
    ];

    public function mount()
    {
        $this->loadData();
    }
    public function render()
    {
        $users = $this->getUsers();
        
        return view('livewire.system.user-management',[ 'users' => $users,])->title('Gestão de Usuários');
    }

    public function loadData()
    {
        // Load ALL companies (Super Admin pode ver todas)
        $this->companies = Company::orderBy('name')->get();
    }

    public function getUsers()
    {
        $query = User::with('company')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%')
                          ->orWhere('phone', 'like', '%' . $this->search . '%')
                          ->orWhereHas('company', function ($companyQuery) {
                              $companyQuery->where('name', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->companyFilter, function ($q) {
                $q->where('company_id', $this->companyFilter);
            })
            ->when($this->userTypeFilter, function ($q) {
                $q->where('user_type', $this->userTypeFilter);
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
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
        $this->password = '';
        $this->password_confirmation = '';
        $this->company_id = '';
        $this->user_type = 'company_user';
        $this->phone = '';
        $this->permissions = [];
        $this->is_active = true;
        $this->send_welcome_email = true;
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->company_id = $user->company_id;
        $this->user_type = $user->user_type;
        $this->phone = $user->phone;
        $this->permissions = $user->permissions ?? [];
        $this->is_active = $user->status === 'active';
        $this->send_welcome_email = false; // Default false for editing
        
        $this->showModal = true;
    }

    public function save()
    {
        // Dynamic validation rules based on editing or creating
        $rules = $this->rules;
        
        if ($this->editingId) {
            // For editing, password is optional
            $rules['password'] = 'nullable|string|min:6|confirmed';
            // Email unique except current user
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->editingId),
            ];
        } else {
            // For creating, validate unique email
            $rules['email'] = 'required|email|max:255|unique:users,email';
        }

        $this->validate($rules);

        // Check if company has reached user limit (only for non-super-admin users)
        if (!$this->editingId && $this->user_type !== 'super_admin' && !$this->canAddUser()) {
            $this->addError('company_id', 'A empresa selecionada atingiu o limite de usuários do seu plano.');
            return;
        }

        try {
            if ($this->editingId) {
                $this->updateUser();
            } else {
                $this->createUser();
            }

            $this->closeModal();
            
            $action = $this->editingId ? 'atualizado' : 'criado';
            session()->flash('success', "Usuário {$action} com sucesso!");
            
        } catch (\Exception $e) {
            $this->addError('general', 'Erro ao salvar usuário: ' . $e->getMessage());
        }
    }

    private function canAddUser()
    {
        if (!$this->company_id) {
            return false;
        }

        $company = Company::find($this->company_id);
        if (!$company) {
            return false;
        }

        // Super Admin pode adicionar usuários mesmo sem subscrição ativa
        // Mas ainda respeitamos os limites do plano se houver subscrição
        $activeSubscription = $company->activeSubscription;
        
        if (!$activeSubscription) {
            // Sem subscrição ativa, permite adicionar usuários (Super Admin preparando empresa)
            return true;
        }

        $plan = $activeSubscription->plan;
        if (!$plan->max_users) { // Unlimited users
            return true;
        }

        $currentUserCount = $company->users()->count();
        return $currentUserCount < $plan->max_users;
    }

    private function createUser()
    {
        // Set default permissions based on user type
        $permissions = $this->permissions;
        if ($this->user_type === 'company_admin') {
            $permissions = array_keys($this->availablePermissions); // All permissions
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password, // Will be hashed by the model cast
            'company_id' => $this->user_type === 'super_admin' ? null : $this->company_id,
            'user_type' => $this->user_type,
            'phone' => $this->phone,
            'permissions' => $permissions,
            'status' => $this->is_active ? 'active' : 'inactive',
            'email_verified_at' => now(), // Auto-verify for admin created users
            'created_by_super_admin' => true,
        ]);

        if ($this->send_welcome_email) {
            $this->sendWelcomeEmail($user);
        }

        // Log activity
        activity()
            ->performedOn($user)
            ->withProperties([
                'company_id' => $this->company_id,
                'user_type' => $this->user_type,
                'created_by_super_admin' => true,
            ])
            ->log('User created by Super Admin');
    }

    private function updateUser()
    {
        $user = User::findOrFail($this->editingId);
        
        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->user_type === 'super_admin' ? null : $this->company_id,
            'user_type' => $this->user_type,
            'phone' => $this->phone,
            'permissions' => $this->permissions,
            'status' => $this->is_active ? 'active' : 'inactive',
        ];

        // Only update password if provided
        if ($this->password) {
            $updateData['password'] = $this->password; // Will be hashed by cast
            $updateData['password_reset_required'] = true; // Force password change
        }

        $user->update($updateData);

        if ($this->send_welcome_email && $this->password) {
            $this->sendWelcomeEmail($user, true); // true = resending
        }

        // Log activity
        activity()
            ->performedOn($user)
            ->withProperties(['changes' => $user->getChanges()])
            ->log('User updated by Super Admin');
    }

    private function sendWelcomeEmail($user, $resending = false)
    {
        try {
            $notificationService = app(NotificationService::class);
            $notificationService->sendWelcomeEmail($user, $this->password, $resending);
        } catch (\Exception $e) {
            // Log error but don't fail the user creation
            logger()->error('Failed to send welcome email: ' . $e->getMessage());
        }
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Você não pode alterar o seu próprio status.');
            return;
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $action = $newStatus === 'active' ? 'ativado' : 'desativado';
        session()->flash('success', "Usuário {$action} com sucesso!");

        // Log activity
        activity()
            ->performedOn($user)
            ->withProperties(['old_status' => $user->status, 'new_status' => $newStatus])
            ->log('User status changed by Super Admin');
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        
        // Generate a new random password
        $newPassword = $this->generateRandomPassword();
        
        $user->update([
            'password' => $newPassword, // Will be hashed by cast
            'password_reset_required' => true, // Force password change on next login
        ]);

        // Send new password via email
        try {
            $notificationService = app(NotificationService::class);
            $notificationService->sendPasswordResetEmail($user, $newPassword);
            
            session()->flash('success', 'Nova senha enviada por email para o usuário.');
        } catch (\Exception $e) {
            session()->flash('error', 'Senha redefinida, mas falhou ao enviar email.');
        }

        // Log activity
        activity()
            ->performedOn($user)
            ->log('Password reset by Super Admin');
    }

    private function generateRandomPassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($characters), 0, $length);
    }

    public function confirmDelete($userId)
    {
        $this->editingId = $userId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $user = User::findOrFail($this->editingId);
        
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Você não pode eliminar a sua própria conta.');
            $this->showDeleteModal = false;
            return;
        }

        // Prevent deleting the last super admin
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::superAdmin()->active()->count();
            
            if ($superAdminCount <= 1) {
                session()->flash('error', 'Não é possível eliminar o último Super Administrador.');
                $this->showDeleteModal = false;
                return;
            }
        }

        // Prevent deleting the last admin of a company
        if ($user->isCompanyAdmin() && $user->company_id) {
            $adminCount = User::where('company_id', $user->company_id)
                             ->where('user_type', 'company_admin')
                             ->where('status', 'active')
                             ->count();
            
            if ($adminCount <= 1) {
                session()->flash('error', 'Não é possível eliminar o último administrador da empresa.');
                $this->showDeleteModal = false;
                return;
            }
        }

        // Log activity before deletion
        activity()
            ->performedOn($user)
            ->withProperties([
                'user_name' => $user->name,
                'user_email' => $user->email,
                'company_name' => $user->company?->name,
            ])
            ->log('User deleted by Super Admin');

        $user->delete();
        
        session()->flash('success', 'Usuário eliminado com sucesso!');
        $this->showDeleteModal = false;
        $this->editingId = null;
    }

    // Auto-update permissions when user type changes
    public function updatedUserType()
    {
        if ($this->user_type === 'company_admin') {
            $this->permissions = array_keys($this->availablePermissions);
        } elseif ($this->user_type === 'super_admin') {
            $this->permissions = []; // Super admin doesn't need specific permissions
            $this->company_id = ''; // Clear company for super admin
        } else {
            // company_user gets basic permissions
            $this->permissions = [
                'repair_orders.create',
                'repair_orders.edit',
                'repair_orders.view',
                'reports.view',
            ];
        }
    }

    // Livewire lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
    }

    public function updatingUserTypeFilter()
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

    /**
     * Bulk create users from array (useful for initial setup)
     */
    public function bulkCreateUsers($companyId, $usersData)
    {
        $created = [];
        $errors = [];

        foreach ($usersData as $userData) {
            try {
                // Validate basic required fields
                if (empty($userData['name']) || empty($userData['email'])) {
                    $errors[] = "Dados incompletos para: " . ($userData['email'] ?? 'email não fornecido');
                    continue;
                }

                // Check if email already exists
                if (User::where('email', $userData['email'])->exists()) {
                    $errors[] = "Email já existe: " . $userData['email'];
                    continue;
                }

                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'] ?? $this->generateRandomPassword(),
                    'company_id' => $companyId,
                    'user_type' => $userData['user_type'] ?? 'company_user',
                    'phone' => $userData['phone'] ?? null,
                    'permissions' => $userData['permissions'] ?? [],
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'created_by_super_admin' => true,
                ]);

                $created[] = $user;

                // Send welcome email if requested
                if (!empty($userData['send_welcome_email'])) {
                    $this->sendWelcomeEmail($user);
                }

            } catch (\Exception $e) {
                $errors[] = "Erro ao criar usuário {$userData['email']}: " . $e->getMessage();
            }
        }

        return [
            'created' => $created,
            'errors' => $errors,
            'total' => count($usersData),
            'success_count' => count($created),
            'error_count' => count($errors),
        ];
    }

}
