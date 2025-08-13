<?php

namespace App\Models\Company\Billing;

use App\Models\Company\ClientCost;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingEstimated extends Model
{
    use HasFactory;

    protected $table = 'billing_estimated';

    protected $fillable = [
        'company_id',
        'repair_order_id',
        'estimated_hours',
        'hourly_price_mzn',
        'hourly_price_usd',
        'total_mzn',
        'total_usd',
        'billing_currency',
        'billed_amount',
        'notes',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'hourly_price_mzn' => 'decimal:2',
        'hourly_price_usd' => 'decimal:2',
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
    // MÉTODOS DE NEGÓCIO
    // =============================================

    /**
     * Gerar faturação estimada automaticamente após Form2
     */
    public static function generateFromForm2(RepairOrder $order)
    {
        if (!$order->form2 || $order->has_billing_estimated) {
            return null;
        }

        // Obter preços do sistema como base
        $systemPrice = ClientCost::where('company_id', $order->company_id)
            ->where('maintenance_type_id', $order->form1->maintenance_type_id)
            ->where('is_active', true)
            ->first();

        if (!$systemPrice) {
            throw new \Exception('Preços do sistema não configurados.');
        }

        // Criar faturação estimada
        $billing = self::create([
            'company_id' => $order->company_id,
            'repair_order_id' => $order->id,
            'estimated_hours' => $order->form2->tempo_total_horas,
            'hourly_price_mzn' => $systemPrice->cost_mzn,
            'hourly_price_usd' => $systemPrice->cost_usd,
        ]);

        $billing->calculateTotals();
        
        // Marcar como gerada
        $order->update(['has_billing_estimated' => true]);

        return $billing;
    }

    public function updatePrices($hourlyPriceMzn, $hourlyPriceUsd, $estimatedHours = null)
    {
        $this->hourly_price_mzn = $hourlyPriceMzn;
        $this->hourly_price_usd = $hourlyPriceUsd;
        
        if ($estimatedHours !== null) {
            $this->estimated_hours = $estimatedHours;
        }
        
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->total_mzn = $this->estimated_hours * $this->hourly_price_mzn;
        $this->total_usd = $this->estimated_hours * $this->hourly_price_usd;
        
        $this->billed_amount = $this->billing_currency === 'USD' 
            ? $this->total_usd 
            : $this->total_mzn;
        
        $this->save();
    }

    public function changeCurrency($newCurrency)
    {
        $this->billing_currency = $newCurrency;
        $this->billed_amount = $newCurrency === 'USD' 
            ? $this->total_usd 
            : $this->total_mzn;
        
        $this->save();
    }

    public function getBillingTypeAttribute()
    {
        return 'Estimada';
    }
}
