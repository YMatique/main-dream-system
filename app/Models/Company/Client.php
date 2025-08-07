<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
     protected $fillable = [
        'company_id', 'name', 'description', 'email', 
        'phone', 'address', 'is_active'
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
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
