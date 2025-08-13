<?php

namespace App\Observers;

use App\Models\Company\RepairOrder\RepairOrderForm2;
use App\Models\Company\RepairOrder\RepairOrderForm3;
use App\Models\Company\Billing\BillingHH;
use App\Models\Company\Billing\BillingEstimated;
use App\Models\Company\Billing\BillingReal;
use Illuminate\Support\Facades\Log;

class RepairOrderObserver
{
    //
    /**
     * Após criar/atualizar Form2 - Gerar Faturação HH e Estimada
     */
    public function created(RepairOrderForm2 $form2)
    {
        $this->generateBillingsAfterForm2($form2);
    }
    
    public function updated(RepairOrderForm2 $form2)
    {
        $this->generateBillingsAfterForm2($form2);
    }

    /**
     * Após criar/atualizar Form3 - Gerar Faturação Real
     */
    public function created(RepairOrderForm3 $form3)
    {
        $this->generateBillingAfterForm3($form3);
    }
    
    public function updated(RepairOrderForm3 $form3)
    {
        $this->generateBillingAfterForm3($form3);
    }

    // =============================================
    // MÉTODOS PRIVADOS
    // =============================================

    private function generateBillingsAfterForm2(RepairOrderForm2 $form2)
    {
        try {
            $repairOrder = $form2->repairOrder;
            
            // 1. Gerar Faturação HH (automaticamente)
            if (!$repairOrder->has_billing_hh) {
                $billingHH = BillingHH::generateFromForm2($repairOrder);
                if ($billingHH) {
                    Log::info("Faturação HH gerada automaticamente", [
                        'repair_order_id' => $repairOrder->id,
                        'billing_hh_id' => $billingHH->id
                    ]);
                }
            }

            // 2. Gerar Faturação Estimada (automaticamente)
            if (!$repairOrder->has_billing_estimated) {
                $billingEstimated = BillingEstimated::generateFromForm2($repairOrder);
                if ($billingEstimated) {
                    Log::info("Faturação Estimada gerada automaticamente", [
                        'repair_order_id' => $repairOrder->id,
                        'billing_estimated_id' => $billingEstimated->id
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao gerar faturações após Form2", [
                'form2_id' => $form2->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function generateBillingAfterForm3(RepairOrderForm3 $form3)
    {
        try {
            $repairOrder = $form3->repairOrder;
            
            // Gerar Faturação Real (automaticamente)
            if (!$repairOrder->has_billing_real) {
                $billingReal = BillingReal::generateFromForm3($repairOrder);
                if ($billingReal) {
                    Log::info("Faturação Real gerada automaticamente", [
                        'repair_order_id' => $repairOrder->id,
                        'billing_real_id' => $billingReal->id
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Erro ao gerar faturação Real após Form3", [
                'form3_id' => $form3->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
