<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class ClientCost extends Model
{
       protected $fillable = [
        'company_id', 'client_id', 'maintenance_type_id',
        'cost_mzn', 'cost_usd', 'effective_date'
    ];

    protected function casts(): array
    {
        return [
            'cost_mzn' => 'decimal:2',
            'cost_usd' => 'decimal:2',
            'effective_date' => 'date'
        ];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }
}
