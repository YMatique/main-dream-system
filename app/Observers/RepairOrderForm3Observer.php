<?php

namespace App\Observers;

use App\Models\Company\Billing\BillingReal;
use App\Models\Company\RepairOrder\RepairOrderForm3;
use Illuminate\Support\Facades\Log;

class RepairOrderForm3Observer
{
     /**
     * Handle the RepairOrderForm3 "created" event.
     */
    public function created(RepairOrderForm3 $form3): void
    {
        $this->generateBillingAfterForm3($form3);
    }

    /**
     * Handle the RepairOrderForm3 "updated" event.
     */
    public function updated(RepairOrderForm3 $form3): void
    {
        // Só regenerar se ainda não tem faturação Real
        if (!$form3->repairOrder->has_billing_real) {
            $this->generateBillingAfterForm3($form3);
        }
    }

    /**
     * Gerar faturação Real após Form3
     */
    private function generateBillingAfterForm3(RepairOrderForm3 $form3): void
    {
        try {
            $repairOrder = $form3->repairOrder;
            
            Log::info('Iniciando geração automática de faturação Real', [
                'repair_order_id' => $repairOrder->id,
                'form3_id' => $form3->id
            ]);

            // Gerar Faturação Real
            if (!$repairOrder->has_billing_real) {
                $billingReal = BillingReal::generateFromForm3($repairOrder);
                if ($billingReal) {
                    Log::info('Faturação Real gerada com sucesso', [
                        'billing_real_id' => $billingReal->id,
                        'amount' => $billingReal->billed_amount,
                        'billing_date' => $billingReal->billing_date
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Erro ao gerar faturação Real após Form3', [
                'form3_id' => $form3->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
