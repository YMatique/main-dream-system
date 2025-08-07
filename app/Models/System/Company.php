<?php

namespace App\Models\System;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
     use HasFactory, Notifiable, LogsActivity;

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

    // Campos que não devem aparecer nos logs
    protected $hiddenForLogs = [
        'api_key',
        'secret_token'
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

    

    /**
     * Check if company is inactive.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

   
    /**
     * Activate the company.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Suspend the company.
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Deactivate the company.
     */
    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    /*
    |--------------------------------------------------------------------------
    | Subscription Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if company has an active subscription.
     */
    // public function hasActiveSubscription(): bool
    // {
    //     $subscription = $this->currentSubscription;
        
    //     if (!$subscription) {
    //         return false;
    //     }

    //     return $subscription->status === 'active' && 
    //            $subscription->expires_at > now();
    // }

    /**
     * Check if subscription is expired.
     */
    public function hasExpiredSubscription(): bool
    {
        return !$this->hasActiveSubscription();
    }

    /**
     * Get days until subscription expires.
     */
    public function getDaysUntilExpiration(): int
    {
        $subscription = $this->currentSubscription;
        
        if (!$subscription || $subscription->expires_at <= now()) {
            return 0;
        }

        return $subscription->expires_at->diffInDays(now());
    }

    /**
     * Check if subscription is expiring soon (within 7 days).
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        $daysUntilExpiration = $this->getDaysUntilExpiration();
        return $daysUntilExpiration > 0 && $daysUntilExpiration <= 7;
    }

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get company admins.
     */
    public function getAdmins()
    {
        return $this->users()->where('user_type', 'company_admin')->get();
    }

    /**
     * Get regular company users.
     */
    public function getRegularUsers()
    {
        return $this->users()->where('user_type', 'company_user')->get();
    }

    /**
     * Get total active users count.
     */
    public function getActiveUsersCount(): int
    {
        return $this->users()->active()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Settings Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get a setting value.
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Set a setting value.
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->update(['settings' => $settings]);
    }

    /**
     * Remove a setting.
     */
    public function removeSetting(string $key): void
    {
        $settings = $this->settings ?? [];
        data_forget($settings, $key);
        $this->update(['settings' => $settings]);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

   

   

    /**
     * Scope to get companies with active subscriptions.
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->whereHas('currentSubscription', function ($q) {
            $q->where('status', 'active')
              ->where('expires_at', '>', now());
        });
    }

    /**
     * Scope to get companies with expired subscriptions.
     */
    public function scopeWithExpiredSubscription($query)
    {
        return $query->whereDoesntHave('currentSubscription')
                    ->orWhereHas('currentSubscription', function ($q) {
                        $q->where('status', '!=', 'active')
                          ->orWhere('expires_at', '<=', now());
                    });
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get company's initials for avatar.
     */
    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return $initials ?: 'C';
    }

    /**
     * Get formatted address.
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([$this->address, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Check if company can be deleted.
     */
    public function canBeDeleted(): bool
    {
        // Company can only be deleted if it has no users and no subscriptions
        return $this->users()->count() === 0 && 
               $this->subscriptions()->count() === 0;
    }
}
