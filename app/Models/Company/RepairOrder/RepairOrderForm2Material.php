<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm2Material extends Model
{
     use HasFactory;

    protected $table = 'repair_order_form2_materials';

    protected $fillable = [
        'form2_id',
        'material_id',
        'quantidade',
    ];

    protected $casts = [
        'quantidade' => 'decimal:3',
    ];

    public function form2()
    {
        return $this->belongsTo(RepairOrderForm2::class, 'form2_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Calcula custo total
    public function getCustoTotalAttribute(): float
    {
        return $this->quantidade * $this->material->cost_per_unit_mzn;
    }
}
