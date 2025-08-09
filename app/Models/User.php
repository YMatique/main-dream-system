<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasPermissions;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
     use HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'user_type',
        'status',
        'phone',
        'permissions',
        'last_login_at',
        'password_reset_required',
        'created_by',
        'created_by_super_admin',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'permissions' => 'array',
            'password_reset_required' => 'boolean',
            'created_by_super_admin' => 'boolean',
            'is_super_admin' => 'boolean', // Manter compatibilidade
        ];
    }

    /**
     * The attributes that should be appended to arrays.
     *
     * @var array
     */
    protected $appends = [
        'user_type_text',
        'status_text',
        'is_online',
        'avatar_url',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

        
    // public function isSuperAdmin(): bool
    // {
    //     return $this->user_type === 'super_admin';
    // }
    
    // public function isCompanyAdmin(): bool
    // {
    //     return $this->user_type === 'company_admin';
    // }
    
    public function isCompanyUser(): bool
    {
        return $this->user_type === 'company_user';
    }
    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users created by this user.
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // ===== SCOPES =====

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para super admins
     */
    // public function scopeSuperAdmin($query)
    // {
    //     return $query->where('user_type', 'super_admin');
    // }

    /**
     * Scope para admins de empresa
     */
    public function scopeCompanyAdmin($query)
    {
        return $query->where('user_type', 'company_admin');
    }

    /**
     * Scope para usuários de uma empresa específica
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope para usuários online (logou nos últimos 15 minutos)
     */
    public function scopeOnline($query)
    {
        return $query->where('last_login_at', '>=', now()->subMinutes(15));
    }

    // ===== ACCESSORS & MUTATORS =====

    /**
     * Get user type text
     */
    public function getUserTypeTextAttribute(): string
    {
        return match ($this->user_type) {
            'super_admin' => 'Super Administrador',
            'company_admin' => 'Administrador da Empresa',
            'company_user' => 'Usuário',
            default => 'Usuário',
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'suspended' => 'Suspenso',
            default => 'Inativo',
        };
    }

    /**
     * Check if user is online
     */
    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_login_at) {
            return false;
        }

        return $this->last_login_at->greaterThan(now()->subMinutes(15));
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        // Por enquanto, retorna null. Depois pode implementar upload de avatar
        return null;
    }

    /**
     * Get user initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    // ===== HELPER METHODS =====

    /**
     * Check if user is super admin
     */
    // public function isSuperAdmin(): bool
    // {
    //     return $this->user_type === 'super_admin' || $this->is_super_admin;
    // }

    /**
     * Check if user is company admin
     */
    public function isCompanyAdmin(): bool
    {
        return $this->user_type === 'company_admin';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin tem todas as permissões
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Company admin tem todas as permissões da empresa
        if ($this->isCompanyAdmin()) {
            return true;
        }

        // Verificar permissões específicas
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Grant permission to user
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Revoke permission from user
     */
    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];

        if (($key = array_search($permission, $permissions)) !== false) {
            unset($permissions[$key]);
            $this->update(['permissions' => array_values($permissions)]);
        }
    }

    /**
     * Sync permissions
     */
    public function syncPermissions(array $permissions): void
    {
        $this->update(['permissions' => $permissions]);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'password_reset_required' => false, // Reset flag after successful login
        ]);
    }

    /**
     * Get company subscription status
     */
    public function getCompanySubscriptionStatus(): ?string
    {
        if (!$this->company) {
            return null;
        }

        $subscription = $this->company->activeSubscription;
        return $subscription ? $subscription->status : 'no_subscription';
    }

    /**
     * Check if user's company has active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->company && $this->company->activeSubscription;
    }

    /**
     * Get allowed permissions based on user type
     */
    public static function getAllowedPermissions(string $userType): array
    {
        $allPermissions = [
            'repair_orders.create',
            'repair_orders.edit',
            'repair_orders.delete',
            'repair_orders.view',
            'repair_orders.export',
            'employees.manage',
            'clients.manage',
            'materials.manage',
            'departments.manage',
            'billing.view',
            'billing.manage',
            'performance.view',
            'performance.manage',
            'reports.view',
            'reports.export',
            'settings.manage',
        ];

        return match ($userType) {
            'super_admin' => ['*'], // All permissions
            'company_admin' => $allPermissions, // All company permissions
            'company_user' => [
                'repair_orders.create',
                'repair_orders.edit',
                'repair_orders.view',
                'reports.view',
            ], // Limited permissions
            default => [],
        };
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set creator when creating user
        static::creating(function ($user) {
            if (auth()->check() && !$user->created_by) {
                $user->created_by = auth()->id();
                $user->created_by_super_admin = auth()->user()->isSuperAdmin();
            }
        });
    }

    /**
     * Check if user has permission (compatibilidade com CheckPermission middleware)
     */
    public function hasPermissionTo(string $permission): bool
    {
        return $this->hasPermission($permission);
    }

    /**
     * Method to update last login (compatibilidade com middleware)
     */
    // public function updateLastLogin(): void
    // {
    //     $this->update([
    //         'last_login_at' => now(),
    //     ]);
    // }

    /**
     * Método melhorado isSuperAdmin para compatibilidade
     */
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin' || $this->is_super_admin == true;
    }

    /**
     * Scope para super admins
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('user_type', 'super_admin')->orWhere('is_super_admin', true);
    }

    /**
     * Accessor para compatibilidade
     */
    public function getIsSuperAdminAttribute()
    {
        return $this->user_type === 'super_admin' || $this->attributes['is_super_admin'] ?? false;
    }




    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }
}
