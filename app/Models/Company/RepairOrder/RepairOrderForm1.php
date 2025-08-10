<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Client;
use App\Models\Company\Location;
use App\Models\Company\MachineNumber;
use App\Models\Company\MaintenanceType;
use App\Models\Company\Requester;
use App\Models\Company\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm1 extends Model
{
     use HasFactory;

    protected $table = 'repair_order_form1';

    protected $fillable = [
        'repair_order_id',
        'carimbo',
        'maintenance_type_id',
        'client_id',
        'status_id',
        'location_id',
        'descricao_avaria',
        'mes',
        'ano',
        'requester_id',
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

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function requester()
    {
        return $this->belongsTo(Requester::class);
    }

    public function machineNumber()
    {
        return $this->belongsTo(MachineNumber::class);
    }
}
