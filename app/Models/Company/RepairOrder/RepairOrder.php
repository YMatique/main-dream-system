<?php

namespace App\Models\Company\RepairOrder;

use App\Models\System\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
     use HasFactory;

    protected $fillable = [
        'company_id',
        'order_number',
        'current_form',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function form1()
    {
        return $this->hasOne(RepairOrderForm1::class);
    }

    public function form2()
    {
        return $this->hasOne(RepairOrderForm2::class);
    }

    public function form3()
    {
        return $this->hasOne(RepairOrderForm3::class);
    }

    public function form4()
    {
        return $this->hasOne(RepairOrderForm4::class);
    }

    public function form5()
    {
        return $this->hasOne(RepairOrderForm5::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeCurrentForm($query, $form)
    {
        return $query->where('current_form', $form);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    // =============================================
    // MÉTODOS DE NEGÓCIO
    // =============================================

    /**
     * Gera número de ordem único para a empresa
     */
    public static function generateOrderNumber($companyId): string
    {
        $year = date('Y');
        $prefix = "OR-{$year}-";
        
        // Busca o último número da empresa no ano atual
        $lastOrder = static::where('company_id', $companyId)
            ->where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            // Extrai o número sequencial
            $lastNumber = (int) str_replace($prefix, '', $lastOrder->order_number);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Avança para o próximo formulário
     */
    public function advanceToNextForm(): bool
    {
        $formSequence = ['form1', 'form2', 'form3', 'form4', 'form5'];
        $currentIndex = array_search($this->current_form, $formSequence);
        
        if ($currentIndex !== false && $currentIndex < count($formSequence) - 1) {
            $this->current_form = $formSequence[$currentIndex + 1];
            
            // Se chegou ao último formulário, marca como completo
            if ($this->current_form === 'form5') {
                $this->is_completed = true;
            }
            
            return $this->save();
        }
        
        return false;
    }

    /**
     * Verifica se pode avançar para o próximo formulário
     */
    public function canAdvanceToForm($targetForm): bool
    {
        $formSequence = ['form1', 'form2', 'form3', 'form4', 'form5'];
        $currentIndex = array_search($this->current_form, $formSequence);
        $targetIndex = array_search($targetForm, $formSequence);
        
        // Pode acessar o formulário atual ou o próximo
        return $targetIndex <= $currentIndex + 1;
    }

    /**
     * Verifica se o formulário está preenchido
     */
    public function isFormCompleted($formNumber): bool
    {
        $relation = "form{$formNumber}";
        return $this->$relation()->exists();
    }

    /**
     * Obtém informações do cliente (do Form1)
     */
    public function getClientAttribute()
    {
        return $this->form1?->client;
    }

    /**
     * Obtém tipo de manutenção (do Form1)
     */
    public function getMaintenanceTypeAttribute()
    {
        return $this->form1?->maintenanceType;
    }

    /**
     * Obtém descrição da avaria (do Form1)
     */
    public function getDescricaoAvariaAttribute()
    {
        return $this->form1?->descricao_avaria;
    }

    /**
     * Obtém tempo total de horas (do Form2)
     */
    public function getTempoTotalHorasAttribute()
    {
        return $this->form2?->tempo_total_horas ?? 0;
    }

    /**
     * Obtém número da máquina
     */
    public function getMachineNumberAttribute()
    {
        // Prioriza Form4, depois Form1
        return $this->form4?->machineNumber ?? $this->form1?->machineNumber;
    }

    /**
     * Verifica se a ordem está no status final
     */
    public function isInFinalStatus(): bool
    {
        return $this->current_form === 'form5' && $this->is_completed;
    }

    // =============================================
    // BOOT
    // =============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = static::generateOrderNumber($model->company_id);
            }
        });
    }
}
