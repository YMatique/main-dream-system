<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermissionGroup extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_group_id',
        'assigned_at',
        'assigned_by',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGroup($query, int $groupId)
    {
        return $query->where('permission_group_id', $groupId);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('permissionGroup', fn($q) => $q->where('is_active', true));
    }
}
