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
     public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
 public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

// Methods
    public static function createForEmployee(Employee $employee, string $email, string $password): self
    {
        return self::create([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'access_token' => Str::random(60),
            'email' => $email,
            'password' => bcrypt($password),
            'is_active' => true,
            'login_count' => 0
        ]);
    }

    public function recordLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'login_count' => $this->login_count + 1
        ]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function regenerateAccessToken(): string
    {
        $newToken = Str::random(60);
        $this->update(['access_token' => $newToken]);
        return $newToken;
    }

    // Accessors
    public function getEmployeeNameAttribute(): string
    {
        return $this->employee->name ?? 'N/A';
    }

    public function getCompanyNameAttribute(): string
    {
        return $this->company->name ?? 'N/A';
    }

    public function getInitialsAttribute(): string
    {
        return substr($this->employee_name, 0, 2);
    }
}
