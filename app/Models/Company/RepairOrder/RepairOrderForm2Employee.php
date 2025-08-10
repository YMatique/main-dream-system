<?php

namespace App\Models\Company\RepairOrder;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm2Employee extends Model
{
    use HasFactory;

    protected $table = 'repair_order_form2_employees';

    protected $fillable = [
        'form2_id',
        'employee_id',
        'horas_trabalhadas',
    ];

    protected $casts = [
        'horas_trabalhadas' => 'decimal:2',
    ];

    public function form2()
    {
        return $this->belongsTo(RepairOrderForm2::class, 'form2_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Boot para atualizar tempo total quando salvar
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->form2->updateTotalHours();
        });

        static::deleted(function ($model) {
            $model->form2->updateTotalHours();
        });
    }
}
