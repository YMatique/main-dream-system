<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Client;
use App\Models\Company\Employee;
use App\Models\Company\MachineNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm5 extends Model
{
     use HasFactory;

    protected $table = 'repair_order_form5';

    protected $fillable = [
        'repair_order_id',
        'carimbo',
        'machine_number_id',
        'data_faturacao_1',
        'horas_faturadas_1',
        'data_faturacao_2',
        'horas_faturadas_2',
        'client_id',
        'descricao_actividades',
        'employee_id',
    ];

    protected $casts = [
        'carimbo' => 'datetime',
        'data_faturacao_1' => 'date',
        'data_faturacao_2' => 'date',
        'horas_faturadas_1' => 'decimal:2',
        'horas_faturadas_2' => 'decimal:2',
    ];

    // Relationships
    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function machineNumber()
    {
        return $this->belongsTo(MachineNumber::class);
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Validação: máximo 4 dias entre as datas
    public function getDaysDifferenceAttribute(): int
    {
        return $this->data_faturacao_2->diffInDays($this->data_faturacao_1);
    }

    public function isValidDateRange(): bool
    {
        return $this->getDaysDifferenceAttribute() <= 4;
    }

    // Validação no boot
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->isValidDateRange()) {
                throw new \Exception('A diferença entre as datas de faturação não pode ser superior a 4 dias.');
            }
        });
    }
}
