<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCache extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permissions',
        'department_permissions',
        'cached_at',
        'expires_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'department_permissions' => 'array',
        'cached_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    // Helper methods
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function hasDepartmentPermission(string $permission, int $departmentId): bool
    {
        $departmentPerms = $this->department_permissions ?? [];
        return in_array($permission, $departmentPerms[$departmentId] ?? []);
    }

    public function refresh(): void
    {
        // Método para recalcular e atualizar o cache
        $user = $this->user;
        $permissions = $user->getAllPermissions();
        $departmentPermissions = $user->getDepartmentPermissions();

        $this->update([
            'permissions' => $permissions,
            'department_permissions' => $departmentPermissions,
            'cached_at' => now(),
            'expires_at' => now()->addHours(24), // Cache por 24 horas
        ]);
    }

    public static function clearExpired(): int
    {
        return static::expired()->delete();
    }

    public static function clearForUser(int $userId): bool
    {
        return static::where('user_id', $userId)->delete();
    }
}
