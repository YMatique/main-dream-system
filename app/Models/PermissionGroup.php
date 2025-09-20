    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class PermissionGroup extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name',
            'description',
            'color',
            'is_system',
            'is_active',
            'sort_order',
        ];

        protected $casts = [
            'is_system' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];

        // Relationships
        public function permissions()
        {
            return $this->belongsToMany(Permission::class, 'permission_group_permissions')
                ->withTimestamps();
        }

        public function permissionGroupPermissions()
        {
            return $this->hasMany(PermissionGroupPermission::class);
        }

        public function users()
        {
            return $this->belongsToMany(User::class, 'user_permission_groups')
                ->withPivot(['assigned_at', 'assigned_by'])
                ->withTimestamps();
        }

        public function userPermissionGroups()
        {
            return $this->hasMany(UserPermissionGroup::class);
        }

        // Scopes
        public function scopeActive($query)
        {
            return $query->where('is_active', true);
        }

        public function scopeSystem($query)
        {
            return $query->where('is_system', true);
        }

        public function scopeCustom($query)
        {
            return $query->where('is_system', false);
        }

        public function scopeOrdered($query)
        {
            return $query->orderBy('sort_order')->orderBy('name');
        }

        // Helper methods
        public function getPermissionNames(): array
        {
            return $this->permissions()->pluck('name')->toArray();
        }

        public function hasPermission(string $permissionName): bool
        {
            return $this->permissions()->where('name', $permissionName)->exists();
        }

        public function addPermission(Permission $permission): void
        {
            if (!$this->hasPermission($permission->name)) {
                $this->permissions()->attach($permission->id);
            }
        }

        public function removePermission(Permission $permission): void
        {
            $this->permissions()->detach($permission->id);
        }

        public function syncPermissions(array $permissionIds): void
        {
            $this->permissions()->sync($permissionIds);
        }

        public function getUsersCount(): int
        {
            return $this->users()->count();
        }
    }
