<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Location;
use App\Models\Company\MachineNumber;
use App\Models\Company\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm4 extends Model
{
    use HasFactory;

    protected $table = 'repair_order_form4';

    protected $fillable = [
        'repair_order_id',
        'carimbo',
        'location_id',
        'status_id',
        'machine_number_id',
    ];

    protected $casts = [
        'carimbo' => 'datetime',
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

    public function machineNumber()
    {
        return $this->belongsTo(MachineNumber::class);
    }
}
