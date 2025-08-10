<?php

namespace App\Models\Company\RepairOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company\Location;
use App\Models\Company\Status;

class RepairOrderForm2 extends Model
{
    use HasFactory;

    protected $table = 'repair_order_form2';

    protected $fillable = [
        'repair_order_id',
        'carimbo',
        'location_id',
        'status_id',
        'tempo_total_horas',
        'actividade_realizada',
    ];

    protected $casts = [
        'carimbo' => 'datetime',
        'tempo_total_horas' => 'decimal:2',
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

    public function employees()
    {
        return $this->hasMany(RepairOrderForm2Employee::class, 'form2_id');
    }

    public function materials()
    {
        return $this->hasMany(RepairOrderForm2Material::class, 'form2_id');
    }

    public function additionalMaterials()
    {
        return $this->hasMany(RepairOrderForm2AdditionalMaterial::class, 'form2_id');
    }

    // Calcula o tempo total baseado nas horas dos técnicos
    public function calculateTotalHours(): float
    {
        return $this->employees()->sum('horas_trabalhadas');
    }

    // Atualiza automaticamente o tempo total
    public function updateTotalHours(): void
    {
        $this->tempo_total_horas = $this->calculateTotalHours();
        $this->save();
    }
}
