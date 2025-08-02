<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
     use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'nuit',
        'status',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latest('created_at');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(\App\Models\Company\Employee::class);
    }

    public function clients()
    {
        return $this->hasMany(\App\Models\Company\Client::class);
    }

    public function repairOrders(): HasMany
    {
        return $this->hasMany(\App\Models\Company\RepairOrder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription !== null;
    }

    public function canCreateUsers(): bool
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $activeSubscription = $this->activeSubscription;
        $maxUsers = $activeSubscription->plan->max_users;

        if ($maxUsers === null) {
            return true; // Unlimited
        }

        return $this->users()->count() < $maxUsers;
    }

    public function canCreateOrders(): bool
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $activeSubscription = $this->activeSubscription;
        $maxOrders = $activeSubscription->plan->max_orders;

        if ($maxOrders === null) {
            return true; // Unlimited
        }

        return $this->repairOrders()->count() < $maxOrders;
    }

    public function markEmailAsVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    // Route for notifications
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
}
