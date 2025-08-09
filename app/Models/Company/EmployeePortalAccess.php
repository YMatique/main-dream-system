<?php

namespace App\Models\Company;

use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeePortalAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'access_token',
        'email',
        'email_verified_at',
        'password',
        'is_active',
        'last_login_at',
        'login_count'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'login_count' => 'integer'
    ];

    protected $hidden = [
        'password',
        'access_token'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Accessors
    public function getPortalUrlAttribute()
    {
        return route('employee.portal.login', ['token' => $this->access_token]);
    }

    // Methods
    public static function createForEmployee($employee, $email, $password = null)
    {
        $password = $password ?? Str::random(8);
        
        return static::create([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'access_token' => Str::uuid(),
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true
        ]);
    }

    public function recordLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'login_count' => $this->login_count + 1
        ]);
    }

    public function verifyEmail()
    {
        $this->update(['email_verified_at' => now()]);
    }

    public function resetPassword($newPassword)
    {
        $this->update(['password' => Hash::make($newPassword)]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }
}
