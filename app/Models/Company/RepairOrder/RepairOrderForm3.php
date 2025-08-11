<?php

namespace App\Models\Company\RepairOrder;


use App\Models\Company\Location;
use App\Models\Company\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm3 extends Model
{
    use HasFactory;

    protected $table = 'repair_order_form3';

    protected $fillable = [
        'repair_order_id',
        'carimbo',
        'location_id',
        'status_id',
        'data_faturacao',
        'horas_faturadas',
    ];

    protected $casts = [
        'carimbo' => 'datetime',
        'data_faturacao' => 'date',
        'horas_faturadas' => 'decimal:2',
    ];

    // Relationships
    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function materials()
    {
        return $this->hasMany(RepairOrderForm3Material::class, 'form3_id');
    }
}
