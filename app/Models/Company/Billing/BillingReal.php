<?php

namespace App\Models\Company\Billing;

use App\Models\Company\ClientCost;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingReal extends Model
{
    use HasFactory;

    protected $table = 'billing_real';

    protected $fillable = [
        'company_id',
        'repair_order_id',
        'billing_date',
        'billed_hours',
        'hourly_price_mzn',
        'hourly_price_usd',
        'total_mzn',
        'total_usd',
        'billing_currency',
        'billed_amount',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'billed_hours' => 'decimal:2',
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
     * Gerar faturação real automaticamente após Form3
     */
    public static function generateFromForm3(RepairOrder $order)
    {
        if (!$order->form3) {
            return null;
        }

        // Obter preços específicos do cliente ou usar preços do sistema
        $clientCost = ClientCost::where('company_id', $order->company_id)
            ->where('client_id', $order->form1->client_id)
            ->where('maintenance_type_id', $order->form1->maintenance_type_id)
            // ->where('is_active', true)
            ->first();

        if (!$clientCost) {
            // Fallback para preços do sistema
            // $systemPrice = SystemPrice::where('company_id', $order->company_id)
            //     ->where('maintenance_type_id', $order->form1->maintenance_type_id)
            //     ->where('is_active', true)
            //     ->first();

            // if (!$systemPrice) {
            //     throw new \Exception('Preços não configurados para este cliente/tipo de manutenção.');
            // }

            $priceMzn = 0;
            $priceUsd = 0;
        } else {
            $priceMzn = $clientCost->cost_mzn;
            $priceUsd = $clientCost->cost_usd;
        }

        // Calcular totais
        $totalMzn = $order->form3->horas_faturadas * $priceMzn;
        $totalUsd = $order->form3->horas_faturadas * $priceUsd;

        // Criar faturação real
        $billing = self::updateOrCreate([
            'company_id' => $order->company_id,
            'repair_order_id' => $order->id,],[
            'billing_date' => $order->form3->data_faturacao,
            'billed_hours' => $order->form3->horas_faturadas,
            'hourly_price_mzn' => $priceMzn,
            'hourly_price_usd' => $priceUsd,
            'total_mzn' => $totalMzn,
            'total_usd' => $totalUsd,
            'billing_currency' => 'MZN', // Padrão
            'billed_amount' => $totalMzn, // Valor em MZN por padrão
        ]);
        
        // Marcar como gerada
        // $order->update(['has_billing_real' => true]);

        return $billing;
    }

    /**
     * Única alteração permitida após geração: moeda
     */
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
        return 'Real';
    }
}
