<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
        use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'group',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot(['department_id', 'metadata', 'granted_at', 'granted_by'])
            ->withTimestamps();
    }

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function permissionGroups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'permission_group_permissions')
            ->withTimestamps();
    }

    public function permissionGroupPermissions()
    {
        return $this->hasMany(PermissionGroupPermission::class);
    }

    // Scopes
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
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
        return $query->orderBy('category')->orderBy('sort_order')->orderBy('name');
    }

    // Accessor
    public function getFullNameAttribute(): string
    {
        return "{$this->category}.{$this->name}";
    }

    // Helper methods
    public static function getCategories(): array
    {
        return static::distinct('category')->orderBy('category')->pluck('category')->toArray();
    }

    public static function getGroups(): array
    {
        return static::distinct('group')->whereNotNull('group')->orderBy('group')->pluck('group')->toArray();
    }

    public function isGrantedToUser(int $userId, ?int $departmentId = null): bool
    {
        return $this->userPermissions()
            ->where('user_id', $userId)
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->exists();
    }

}
