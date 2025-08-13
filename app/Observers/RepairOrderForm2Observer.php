<?php

namespace App\Observers;

use App\Models\Company\RepairOrder\RepairOrderForm2;
use App\Models\Company\Billing\BillingHH;
use App\Models\Company\Billing\BillingEstimated;
use Illuminate\Support\Facades\Log;

class RepairOrderForm2Observer
{
    /**
     * Handle the RepairOrderForm2 "created" event.
     */
    public function created(RepairOrderForm2 $form2): void
    {
        $this->generateBillingsAfterForm2($form2);
    }

    /**
     * Handle the RepairOrderForm2 "updated" event.
     */
    public function updated(RepairOrderForm2 $form2): void
    {
        $this->generateBillingsAfterForm2($form2);
        // Só regenerar se ainda não tem faturações
        if (!$form2->repairOrder || !$form2->repairOrder->form3) {
            $this->generateBillingsAfterForm2($form2);
        }
    }

    /**
     * Gerar faturações HH e Estimada após Form2
     */
    private function generateBillingsAfterForm2(RepairOrderForm2 $form2): void
    {
        try {
            $repairOrder = $form2->repairOrder;
            
            Log::info('Iniciando geração automática de faturações', [
                'repair_order_id' => $repairOrder->id,
                'form2_id' => $form2->id
            ]);

            // 1. Gerar Faturação HH
            if (!$repairOrder->has_billing_hh) {
                $billingHH = BillingHH::generateFromForm2($repairOrder);
                if ($billingHH) {
                    Log::info('Faturação HH gerada com sucesso', [
                        'billing_hh_id' => $billingHH->id,
                        'amount' => $billingHH->billed_amount
                    ]);
                }
            }

            // 2. Gerar Faturação Estimada
            if (!$repairOrder->has_billing_estimated) {
                $billingEstimated = BillingEstimated::generateFromForm2($repairOrder);
                if ($billingEstimated) {
                    Log::info('Faturação Estimada gerada com sucesso', [
                        'billing_estimated_id' => $billingEstimated->id,
                        'amount' => $billingEstimated->billed_amount
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Erro ao gerar faturações após Form2', [
                'form2_id' => $form2->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
