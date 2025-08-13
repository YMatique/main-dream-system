<?php

namespace App\Models\Company\Billing;

use App\Models\Company\ClientCost;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingHH extends Model
{
    use HasFactory;

    protected $table = 'billing_hh';

    protected $fillable = [
        'company_id',
        'repair_order_id',
        'total_hours',
        'total_mzn',
        'total_usd',
        'billing_currency',
        'billed_amount',
    ];

    protected $casts = [
        'total_hours' => 'decimal:2',
        'total_mzn' => 'decimal:2',
        'total_usd' => 'decimal:2',
        'billed_amount' => 'decimal:2',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByCurrency($query, $currency)
    {
        return $query->where('billing_currency', $currency);
    }

    // =============================================
    // MÉTODOS DE NEGÓCIO
    // =============================================

    /**
     * Gerar faturação HH automaticamente após Form2
     */
    public static function generateFromForm2(RepairOrder $order)
    {
        if (!$order->form2 ) {
            return null;
        }

        // Obter preços do sistema
        $systemPrice = ClientCost::where('company_id', $order->company_id)
            ->where('maintenance_type_id', $order->form1->maintenance_type_id)
            // ->where('is_active', true)
            ->first();

        // if (!$systemPrice) {
        //     throw new \Exception('Preços do sistema não configurados para este tipo de manutenção.');
        // }

        // Calcular totais
        $totalMzn = $order->form2->tempo_total_horas * ($systemPrice->cost_mzn??0);
        $totalUsd = $order->form2->tempo_total_horas * ($systemPrice->cost_usd??0);

        // Criar faturação HH
        $billing = self::updateOrCreate(
           [
            'repair_order_id' => $order->id,
             'company_id' => $order->company_id,
           ],[            
            'total_hours' => $order->form2->tempo_total_horas,
            'total_mzn' => $totalMzn,
            'total_usd' => $totalUsd,
            'billing_currency' => 'MZN', // Padrão
            'billed_amount' => $totalMzn, // Valor em MZN por padrão
        ]);
        
        // Marcar como gerada
        // $order->update(['has_billing_hh' => true]);

        return $billing;
    }

    public function changeCurrency($newCurrency)
    {
        $this->billing_currency = $newCurrency;
        $this->billed_amount = $newCurrency === 'USD' 
            ? $this->total_usd 
            : $this->total_mzn;
        
        $this->save();
    }

    // =============================================
    // ACCESSORS
    // =============================================

    public function getFormattedBilledAmountAttribute()
    {
        $currency = $this->billing_currency === 'USD' ? '$' : 'MZN';
        return $currency . ' ' . number_format($this->billed_amount, 2);
    }

    public function getBillingTypeAttribute()
    {
        return 'HH';
    }
}
