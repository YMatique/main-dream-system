<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
     protected $fillable = [
        'company_id', 'name', 'form_type', 
        'color', 'is_final', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'is_final' => 'boolean',
            'sort_order' => 'integer'
        ];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function scopeForForm($query, string $formType)
    {
        return $query->where('form_type', $formType);
    }
}
