<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['company_id', 'name', 'form_type', 'description'];

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function scopeForForm($query, string $formType)
    {
        return $query->where('form_type', $formType);
    }
}
