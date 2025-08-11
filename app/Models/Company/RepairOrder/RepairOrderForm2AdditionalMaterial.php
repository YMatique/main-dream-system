<?php

namespace App\Models\Company\RepairOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrderForm2AdditionalMaterial extends Model
{
     use HasFactory;

    protected $table = 'repair_order_form2_additional_materials';

    protected $fillable = [
        'form2_id',
        'nome_material',
        'custo_unitario',
        'quantidade',
        'custo_total',
    ];

    protected $casts = [
        'custo_unitario' => 'decimal:2',
        'quantidade' => 'decimal:3',
        'custo_total' => 'decimal:2',
    ];

    public function form2()
    {
        return $this->belongsTo(RepairOrderForm2::class, 'form2_id');
    }

    // Calcula custo total automaticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->custo_total = $model->custo_unitario * $model->quantidade;
        });
    }
}
