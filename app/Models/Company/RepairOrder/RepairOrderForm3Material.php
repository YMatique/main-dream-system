<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm3Material extends Model
{
     use HasFactory;

    protected $table = 'repair_order_form3_materials';

    protected $fillable = [
        'form3_id',
        'material_id',
        'quantidade',
    ];

    protected $casts = [
        'quantidade' => 'decimal:3',
    ];

    public function form3()
    {
        return $this->belongsTo(RepairOrderForm3::class, 'form3_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
