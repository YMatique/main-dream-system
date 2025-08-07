<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    protected $fillable = [
        'company_id', 'name', 'description', 
        'hourly_rate_mzn', 'hourly_rate_usd', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate_mzn' => 'decimal:2',
            'hourly_rate_usd' => 'decimal:2',
            'is_active' => 'boolean'
        ];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function clientCosts()
    {
        return $this->hasMany(ClientCost::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
