<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
       protected $fillable = [
        'company_id', 'name', 'email', 
        'phone', 'department', 'is_active'
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
