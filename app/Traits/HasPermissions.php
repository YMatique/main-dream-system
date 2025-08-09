<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\PermissionCache;
use App\Models\PermissionGroup;
use App\Models\UserPermission;
use App\Models\DepartmentEvaluator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasPermissions
{
    // Relationships
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot(['department_id', 'metadata', 'granted_at', 'granted_by'])
            ->withTimestamps();
    }

    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function permissionGroups(): BelongsToMany
    {
        return $this->belongsToMany(PermissionGroup::class, 'user_permission_groups')
            ->withPivot(['assigned_at', 'assigned_by'])
            ->withTimestamps();
    }

    public function permissionCache(): HasOne
    {
        return $this->hasOne(PermissionCache::class);
    }

    public function departmentEvaluators(): HasMany
    {
        return $this->hasMany(DepartmentEvaluator::class);
    }

    // Permission checking methods
    public function hasPermission(string $permission, ?int $departmentId = null): bool
    {
        // Super Admin tem todas as permissões
        if ($this->user_type === 'super_admin') {
            return true;
        }

        // Company Admin tem todas as permissões da empresa
        if ($this->user_type === 'company_admin') {
            return true;
        }

        // Verificar cache primeiro
        $cache = $this->getPermissionCache();
        
        if ($departmentId) {
            return $cache->hasDepartmentPermission($permission, $departmentId);
        }

        return $cache->hasPermission($permission);
    }

    public function hasAnyPermission(array $permissions, ?int $departmentId = null): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission, $departmentId)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions, ?int $departmentId = null): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission, $departmentId)) {
                return false;
            }
        }
        return true;
    }

    public function canEvaluateDepartment(int $departmentId): bool
    {
        return DepartmentEvaluator::userCanEvaluateDepartment($this->id, $departmentId);
    }

    // Permission granting methods
    public function grantPermission(string $permissionName, ?int $departmentId = null, ?int $grantedBy = null): bool
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return false;
        }

        UserPermission::updateOrCreate([
            'user_id' => $this->id,
            'permission_id' => $permission->id,
            'department_id' => $departmentId,
        ], [
            'granted_at' => now(),
            'granted_by' => $grantedBy,
        ]);

        $this->clearPermissionCache();
        return true;
    }

    public function revokePermission(string $permissionName, ?int $departmentId = null): bool
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return false;
        }

        $deleted = UserPermission::where('user_id', $this->id)
            ->where('permission_id', $permission->id)
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->delete();

        if ($deleted) {
            $this->clearPermissionCache();
        }

        return $deleted > 0;
    }

    public function assignPermissionGroup(int $groupId, ?int $assignedBy = null): bool
    {
        $group = PermissionGroup::find($groupId);
        
        if (!$group || !$group->is_active) {
            return false;
        }

        $this->permissionGroups()->syncWithoutDetaching([
            $groupId => [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
            ]
        ]);

        $this->clearPermissionCache();
        return true;
    }

    public function removePermissionGroup(int $groupId): bool
    {
        $removed = $this->permissionGroups()->detach($groupId);
        
        if ($removed) {
            $this->clearPermissionCache();
        }

        return $removed > 0;
    }

    // Cache management
    public function getPermissionCache(): PermissionCache
    {
        $cache = $this->permissionCache;
        
        if (!$cache || $cache->isExpired()) {
            $cache = $this->refreshPermissionCache();
        }

        return $cache;
    }

    public function refreshPermissionCache(): PermissionCache
    {
        $permissions = $this->getAllPermissions();
        $departmentPermissions = $this->getDepartmentPermissions();

        return PermissionCache::updateOrCreate([
            'user_id' => $this->id,
        ], [
            'permissions' => $permissions,
            'department_permissions' => $departmentPermissions,
            'cached_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);
    }

    public function clearPermissionCache(): void
    {
        PermissionCache::where('user_id', $this->id)->delete();
    }

    // Helper methods
    public function getAllPermissions(): array
    {
        // Permissões diretas
        $directPermissions = $this->userPermissions()
            ->whereNull('department_id')
            ->with('permission')
            ->get()
            ->pluck('permission.name')
            ->toArray();

        // Permissões dos grupos
        $groupPermissions = $this->permissionGroups()
            ->where('is_active', true)
            ->with('permissions')
            ->get()
            ->flatMap(fn($group) => $group->permissions->pluck('name'))
            ->toArray();

        return array_unique(array_merge($directPermissions, $groupPermissions));
    }

    public function getDepartmentPermissions(): array
    {
        $permissions = [];

        // Permissões diretas por departamento
        $directDeptPermissions = $this->userPermissions()
            ->whereNotNull('department_id')
            ->with(['permission', 'department'])
            ->get();

        foreach ($directDeptPermissions as $userPerm) {
            $deptId = $userPerm->department_id;
            $permissions[$deptId][] = $userPerm->permission->name;
        }

        // Departamentos para avaliação
        $evaluatorDepts = $this->departmentEvaluators()
            ->where('is_active', true)
            ->pluck('department_id')
            ->toArray();

        foreach ($evaluatorDepts as $deptId) {
            $permissions[$deptId][] = "evaluation.department.{$deptId}";
        }

        return $permissions;
    }

    public function getEvaluationDepartments(): array
    {
        return DepartmentEvaluator::getUserDepartments($this->id);
    }
}