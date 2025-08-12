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
    /**
     * Scope para ordens incompletas
     */
    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

     /**
     * Scope para incluir todos os formulários
     */
    public function scopeWithAllForms($query)
    {
        return $query->with([
            'form1.client',
            'form1.maintenanceType',
            'form1.machineNumber',
            'form1.requester',
            'form1.status',
            'form1.location',
            'form2.employees.department',
            'form2.materials',
            'form2.additionalMaterials',
            'form3.materials',
            'form4.machineNumber',
            'form4.location',
            'form4.status',
            'form5.employee.department',
            'form5.client',
            'form5.machineNumber'
        ]);
    }

     /**
     * Scope para ordens com permissões do usuário
     */
    public function scopeWithUserPermissions($query, $user)
    {
        if ($user->can('repair_orders.view_all')) {
            // Admin pode ver todas as ordens da empresa
            return $query->where('company_id', $user->company_id);
        } elseif ($user->can('repair_orders.view_department')) {
            // Ver ordens do departamento
            return $query->where('company_id', $user->company_id)
                ->whereHas('form2.employees', function ($q) use ($user) {
                    $q->whereHas('department', function ($deptQ) use ($user) {
                        $deptQ->where('id', $user->employee?->department_id);
                    });
                });
        } elseif ($user->can('repair_orders.view_own')) {
            // Ver apenas ordens onde participou como técnico
            return $query->where('company_id', $user->company_id)
                ->whereHas('form2.employees', function ($q) use ($user) {
                    $q->where('employee_id', $user->employee_id);
                });
        }

        // Fallback: nenhuma ordem se não tem permissões
        return $query->where('id', null);
    }

    /**
     * Scope para busca geral
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('order_number', 'like', '%' . $search . '%')
              ->orWhereHas('form1.client', function ($clientQ) use ($search) {
                  $clientQ->where('name', 'like', '%' . $search . '%');
              })
              ->orWhereHas('form1.machineNumber', function ($machineQ) use ($search) {
                  $machineQ->where('number', 'like', '%' . $search . '%');
              })
              ->orWhereHas('form1', function ($form1Q) use ($search) {
                  $form1Q->where('descricao_avaria', 'like', '%' . $search . '%');
              });
        });
    }

    /**
     * Scope para filtro por período
     */
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            ]);
        }

        return $query;
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

     /**
     * Accessor para obter o próximo formulário
     */
    public function getNextFormAttribute(): ?string
    {
        $forms = ['form1', 'form2', 'form3', 'form4', 'form5'];
        $currentIndex = array_search($this->current_form, $forms);
        
        return $currentIndex !== false && $currentIndex < count($forms) - 1 
            ? $forms[$currentIndex + 1] 
            : null;
    }

    /**
     * Accessor para calcular o progresso dos formulários (%)
     */
    public function getProgressPercentageAttribute(): int
    {
        $formWeights = [
            'form1' => 20,
            'form2' => 40,
            'form3' => 60,
            'form4' => 80,
            'form5' => 100,
        ];

        return $formWeights[$this->current_form] ?? 0;
    }

    /**
     * Accessor para verificar quais formulários estão completos
     */
    public function getCompletedFormsAttribute(): array
    {
        return [
            'form1' => $this->form1 !== null,
            'form2' => $this->form2 !== null,
            'form3' => $this->form3 !== null,
            'form4' => $this->form4 !== null,
            'form5' => $this->form5 !== null,
        ];
    }

    /**
     * Accessor para obter dados resumidos da ordem
     */
    public function getOrderSummaryAttribute(): array
    {
        return [
            'order_number' => $this->order_number,
            'client_name' => $this->form1?->client?->name ?? 'N/A',
            'machine_number' => $this->form1?->machineNumber?->number ?? 'N/A',
            'maintenance_type' => $this->form1?->maintenanceType?->name ?? 'N/A',
            'current_form' => $this->current_form,
            'progress_percentage' => $this->progress_percentage,
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Accessor para obter o status visual baseado no formulário atual
     */
    public function getVisualStatusAttribute(): array
    {
        $statusMap = [
            'form1' => ['color' => 'blue', 'label' => 'Iniciado', 'icon' => '📝'],
            'form2' => ['color' => 'green', 'label' => 'Técnicos', 'icon' => '🔧'],
            'form3' => ['color' => 'orange', 'label' => 'Faturação', 'icon' => '💰'],
            'form4' => ['color' => 'purple', 'label' => 'Máquina', 'icon' => '⚙️'],
            'form5' => ['color' => 'red', 'label' => 'Finalização', 'icon' => '✅'],
        ];

        if ($this->is_completed) {
            return ['color' => 'green', 'label' => 'Concluída', 'icon' => '🏁'];
        }

        return $statusMap[$this->current_form] ?? ['color' => 'gray', 'label' => 'Indefinido', 'icon' => '❓'];
    }




    /**
     * Obter resumo completo da ordem
     */
    public function getFullSummary(): array
    {
        $summary = [
            'order_number' => $this->order_number,
            'current_form' => $this->current_form,
            'progress_percentage' => $this->progress_percentage,
            'is_completed' => $this->is_completed,
            'completed_forms' => $this->completed_forms,
            'visual_status' => $this->visual_status,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];

        // Adicionar dados dos formulários se existirem
        if ($this->form1) {
            $summary['form1'] = [
                'client' => $this->form1->client?->name,
                'maintenance_type' => $this->form1->maintenanceType?->name,
                'machine_number' => $this->form1->machineNumber?->number,
                'description' => $this->form1->descricao_avaria,
                'month_year' => $this->form1->mes . '/' . $this->form1->ano,
            ];
        }

        if ($this->form2) {
            $summary['form2'] = [
                'total_hours' => $this->form2->tempo_total_horas,
                'employees_count' => $this->form2->employees->count(),
                'materials_count' => $this->form2->materials->count(),
                'additional_materials_count' => $this->form2->additionalMaterials->count(),
            ];
        }

        if ($this->form3) {
            $summary['form3'] = [
                'billing_date' => $this->form3->data_faturacao->format('d/m/Y'),
                'billed_hours' => $this->form3->horas_faturadas,
                'materials_count' => $this->form3->materials->count(),
            ];
        }

        if ($this->form4) {
            $summary['form4'] = [
                'machine_number' => $this->form4->machineNumber?->number,
                'location' => $this->form4->location?->name,
                'status' => $this->form4->status?->name,
            ];
        }

        if ($this->form5) {
            $summary['form5'] = [
                'billing_period' => $this->form5->billing_period,
                'total_hours' => $this->form5->total_hours,
                'employee' => $this->form5->employee?->name,
                'days_difference' => $this->form5->days_difference,
            ];
        }

        return $summary;
    }

    /**
     * Verificar se todos os formulários estão completos
     */
    public function areAllFormsComplete(): bool
    {
        $completedForms = $this->completed_forms;
        return collect($completedForms)->every(fn($completed) => $completed);
    }

    /**
     * Obter dados para exportação
     */
    public function getExportData(): array
    {
        return [
            'Ordem' => $this->order_number,
            'Cliente' => $this->form1?->client?->name ?? '',
            'Máquina' => $this->form1?->machineNumber?->number ?? '',
            'Tipo Manutenção' => $this->form1?->maintenanceType?->name ?? '',
            'Formulário Atual' => strtoupper($this->current_form),
            'Progresso' => $this->progress_percentage . '%',
            'Status' => $this->is_completed ? 'Concluída' : 'Em Andamento',
            'Data Criação' => $this->created_at->format('d/m/Y'),
            'Última Atualização' => $this->updated_at->format('d/m/Y H:i'),
            'Horas Trabalhadas' => $this->form2?->tempo_total_horas ?? 0,
            'Horas Faturadas' => $this->form3?->horas_faturadas ?? 0,
            'Técnicos' => $this->form2?->employees->pluck('name')->implode(', ') ?? '',
            'Descrição Avaria' => $this->form1?->descricao_avaria ?? '',
        ];
    }

    /**
     * Calcular tempo total para conclusão
     */
    public function getCompletionTimeAttribute(): ?int
    {
        if (!$this->is_completed) {
            return null;
        }

        return $this->created_at->diffInDays($this->updated_at);
    }

    /**
     * Validar integridade da ordem
     */
    public function validateIntegrity(): array
    {
        $errors = [];

        // Verificar se o formulário atual existe
        if (!$this->{$this->current_form}) {
            $errors[] = "Formulário {$this->current_form} não existe mas está marcado como atual.";
        }

        // Verificar sequência dos formulários
        $forms = ['form1', 'form2', 'form3', 'form4', 'form5'];
        $currentIndex = array_search($this->current_form, $forms);
        
        for ($i = 0; $i < $currentIndex; $i++) {
            if (!$this->{$forms[$i]}) {
                $errors[] = "Formulário {$forms[$i]} deveria existir antes do formulário atual.";
            }
        }

        // Verificar se está marcado como completo mas não tem todos os formulários
        if ($this->is_completed && !$this->areAllFormsComplete()) {
            $errors[] = "Ordem marcada como completa mas não possui todos os formulários.";
        }

        return $errors;
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
        
         // Log de mudanças importantes
        static::updating(function ($model) {
            if ($model->isDirty('current_form')) {
                \Log::info("Ordem {$model->order_number} avançou para {$model->current_form}");
            }

            if ($model->isDirty('is_completed') && $model->is_completed) {
                \Log::info("Ordem {$model->order_number} foi concluída");
            }
        });
    }
}
