<?php
namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\UserPermission;
use App\Models\DepartmentEvaluator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    /**
     * Conceder permissão a um usuário
     */
    public function grantPermission(User $user, string $permissionName, ?User $grantedBy = null): bool
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return false;
        }

        UserPermission::updateOrCreate([
            'user_id' => $user->id,
            'permission_id' => $permission->id,
            'department_id' => null,
        ], [
            'granted_at' => now(),
            'granted_by' => $grantedBy?->id,
        ]);

        // Limpar cache de permissões
        $this->clearUserPermissionCache($user);
        
        return true;
    }

    /**
     * Revogar permissão de um usuário
     */
    public function revokePermission(User $user, string $permissionName): bool
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return false;
        }

        $deleted = UserPermission::where('user_id', $user->id)
            ->where('permission_id', $permission->id)
            ->delete();

        if ($deleted) {
            $this->clearUserPermissionCache($user);
        }

        return $deleted > 0;
    }

    /**
     * Atribuir grupo de permissões a usuário
     */
    public function assignPermissionGroup(User $user, int $groupId, ?User $assignedBy = null): bool
    {
        $group = PermissionGroup::find($groupId);
        
        if (!$group || !$group->is_active) {
            return false;
        }

        $user->permissionGroups()->syncWithoutDetaching([
            $groupId => [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy?->id,
            ]
        ]);

        $this->clearUserPermissionCache($user);
        return true;
    }

    /**
     * Configurar usuário como avaliador de departamento
     */
    public function assignDepartmentEvaluator(User $user, int $departmentId, ?User $assignedBy = null): bool
    {
        DepartmentEvaluator::updateOrCreate([
            'user_id' => $user->id,
            'department_id' => $departmentId,
            'company_id' => $user->company_id,
        ], [
            'is_active' => true,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy?->id,
        ]);

        $this->clearUserPermissionCache($user);
        return true;
    }

    /**
     * Limpar cache de permissões do usuário
     */
    public function clearUserPermissionCache(User $user): void
    {
        Cache::forget("user_permissions_{$user->id}");
        $user->clearPermissionCache();
    }

    /**
     * Obter estatísticas básicas de permissões
     */
    public function getPermissionStats(): array
    {
        return [
            'total_permissions' => Permission::count(),
            'total_groups' => PermissionGroup::count(),
            'active_groups' => PermissionGroup::where('is_active', true)->count(),
            'users_with_permissions' => UserPermission::distinct('user_id')->count(),
        ];
    }
}