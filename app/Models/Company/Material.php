<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
     protected $fillable = [
        'company_id', 'name', 'description', 'unit',
        'cost_per_unit_mzn', 'cost_per_unit_usd', 
        'stock_quantity', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'cost_per_unit_mzn' => 'decimal:2',
            'cost_per_unit_usd' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean'
        ];
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
