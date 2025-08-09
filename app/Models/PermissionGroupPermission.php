<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroupPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_group_id',
        'permission_id',
    ];

    // Relationships
    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    // Scopes
    public function scopeForGroup($query, int $groupId)
    {
        return $query->where('permission_group_id', $groupId);
    }

    public function scopeForPermission($query, int $permissionId)
    {
        return $query->where('permission_id', $permissionId);
    }
}
