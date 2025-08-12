<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Client;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersList extends Component
{
     use WithPagination;

    // =============================================
    // PROPRIEDADES DE FILTROS
    // =============================================
    
    public $search = '';
    public $filterByForm = 'all'; // all, form1, form2, form3, form4, form5, completed
    public $filterByClient = '';
    public $filterByStatus = '';
    public $filterByPeriod = '30'; // últimos X dias
    public $filterStartDate = '';
    public $filterEndDate = '';
    
    // =============================================
    // PROPRIEDADES DE CONFIGURAÇÃO
    // =============================================
    
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'table'; // table ou cards
    
    // =============================================
    // DADOS PARA DROPDOWNS
    // =============================================
    
    public $clients = [];
    public $statuses = [];
    public $currentFormOptions = [];
    
    // =============================================
    // MÉTRICAS E CONTADORES
    // =============================================
    
    public $metrics = [];
    public $showMetrics = true;

    // =============================================
    // MOUNT E INICIALIZAÇÃO
    // =============================================

    public function mount()
    {
        // Verificar permissões básicas
        if (!auth()->user()->can('repair_orders.view_all') && !auth()->user()->can('repair_orders.view_own') && !auth()->user()->isCompanyAdmin()) {
            abort(403, 'Sem permissão para visualizar ordens de reparação.');
        }

        $this->loadFilterData();
        $this->calculateMetrics();
        
        // Configurar período padrão se não definido
        if (!$this->filterStartDate && !$this->filterEndDate) {
            $this->filterStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $this->filterEndDate = Carbon::now()->format('Y-m-d');
        }
    }

    // =============================================
    // LISTENERS DE FILTROS
    // =============================================

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterByForm()
    {
        $this->resetPage();
        $this->calculateMetrics();
    }

    public function updatedFilterByClient()
    {
        $this->resetPage();
    }

    public function updatedFilterByStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterByPeriod()
    {
        if ($this->filterByPeriod !== 'custom') {
            $days = (int) $this->filterByPeriod;
            $this->filterStartDate = Carbon::now()->subDays($days)->format('Y-m-d');
            $this->filterEndDate = Carbon::now()->format('Y-m-d');
        }
        $this->resetPage();
        $this->calculateMetrics();
    }

    public function updatedFilterStartDate()
    {
        $this->filterByPeriod = 'custom';
        $this->resetPage();
        $this->calculateMetrics();
    }

    public function updatedFilterEndDate()
    {
        $this->filterByPeriod = 'custom';
        $this->resetPage();
        $this->calculateMetrics();
    }

    // =============================================
    // MÉTODOS DE DADOS
    // =============================================

    private function loadFilterData()
    {
        $companyId = auth()->user()->company_id;

        // Carregar clientes
        $this->clients = Client::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Carregar status (todos os tipos de formulário)
        $this->statuses = Status::where('company_id', $companyId)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'form_type', 'color']);

        // Opções de formulário atual
        $this->currentFormOptions = [
            'all' => 'Todas as Ordens',
            'form1' => 'Formulário 1 (Inicial)',
            'form2' => 'Formulário 2 (Técnicos)',
            'form3' => 'Formulário 3 (Faturação)',
            'form4' => 'Formulário 4 (Máquina)',
            'form5' => 'Formulário 5 (Final)',
            'completed' => 'Concluídas'
        ];
    }

    public function calculateMetrics()
    {
        $companyId = auth()->user()->company_id;
        
        // Query base com permissões
        $baseQuery = $this->getBaseQuery();

        // Métricas gerais
        $this->metrics = [
            'total_orders' => $baseQuery->clone()->count(),
            'completed_orders' => $baseQuery->clone()->where('is_completed', true)->count(),
            'in_progress_orders' => $baseQuery->clone()->where('is_completed', false)->count(),
            
            // Contadores por formulário
            'form1_count' => $baseQuery->clone()->where('current_form', 'form1')->count(),
            'form2_count' => $baseQuery->clone()->where('current_form', 'form2')->count(),
            'form3_count' => $baseQuery->clone()->where('current_form', 'form3')->count(),
            'form4_count' => $baseQuery->clone()->where('current_form', 'form4')->count(),
            'form5_count' => $baseQuery->clone()->where('current_form', 'form5')->count(),
            
            // Métricas do período atual
            'period_start' => $this->filterStartDate ? Carbon::parse($this->filterStartDate)->format('d/m/Y') : null,
            'period_end' => $this->filterEndDate ? Carbon::parse($this->filterEndDate)->format('d/m/Y') : null,
        ];

        // Calcular tempo médio se houver ordens completas
        if ($this->metrics['completed_orders'] > 0) {
            $avgDays = $baseQuery->clone()
                ->where('is_completed', true)
                ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
                ->value('avg_days');
            
            $this->metrics['avg_completion_days'] = round($avgDays, 1);
        } else {
            $this->metrics['avg_completion_days'] = 0;
        }
    }

    private function getBaseQuery()
    {
        $companyId = auth()->user()->company_id;
        $user = auth()->user();

        // Query base
        $query = RepairOrder::where('company_id', $companyId);

        // Aplicar permissões de visualização
        if (!$user->can('repair_orders.view_all')) {
            if ($user->can('repair_orders.view_own')) {
                // Ver apenas ordens onde o usuário participou como técnico
                $query->whereHas('form2.employees', function ($q) use ($user) {
                    $q->where('employee_id', $user->employee_id);
                });
            } elseif ($user->can('repair_orders.view_department')) {
                // Ver ordens do departamento
                $query->whereHas('form2.employees.department', function ($q) use ($user) {
                    $q->where('id', $user->employee->department_id);
                });
            }
        }

        // Aplicar filtros de período
        if ($this->filterStartDate && $this->filterEndDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->filterStartDate)->startOfDay(),
                Carbon::parse($this->filterEndDate)->endOfDay()
            ]);
        }

        return $query;
    }

    // =============================================
    // MÉTODOS DE ORDENAÇÃO
    // =============================================

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    // =============================================
    // MÉTODOS DE AÇÃO
    // =============================================

    public function clearFilters()
    {
        $this->search = '';
        $this->filterByForm = 'all';
        $this->filterByClient = '';
        $this->filterByStatus = '';
        $this->filterByPeriod = '30';
        $this->filterStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->filterEndDate = Carbon::now()->format('Y-m-d');
        
        $this->resetPage();
        $this->calculateMetrics();
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'table' ? 'cards' : 'table';
    }

    public function refreshData()
    {
        $this->loadFilterData();
        $this->calculateMetrics();
        $this->dispatch('refresh-complete');
    }

    // =============================================
    // NAVEGAÇÃO RÁPIDA
    // =============================================

    public function continueOrder($orderId)
    {
        $order = RepairOrder::where('company_id', auth()->user()->company_id)
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            session()->flash('error', 'Ordem não encontrada.');
            return;
        }

        // Determinar próximo formulário
        $nextForm = $order->current_form;
        
        return redirect()->route("company.repair-orders.{$nextForm}", $order->id);
    }

    public function viewOrder($orderId)
    {
        // TODO: Implementar modal de visualização ou página de detalhes
        $this->dispatch('show-order-details', orderId: $orderId);
    }

    // =============================================
    // QUERY PRINCIPAL
    // =============================================

    public function getOrdersProperty()
    {
        $query = $this->getBaseQuery();

        // Aplicar filtros específicos
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('form1.client', function ($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('form1.machineNumber', function ($subQ) {
                      $subQ->where('number', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterByForm !== 'all') {
            if ($this->filterByForm === 'completed') {
                $query->where('is_completed', true);
            } else {
                $query->where('current_form', $this->filterByForm);
            }
        }

        if ($this->filterByClient) {
            $query->whereHas('form1', function ($q) {
                $q->where('client_id', $this->filterByClient);
            });
        }

        if ($this->filterByStatus) {
            $query->whereHas('form1', function ($q) {
                $q->where('status_id', $this->filterByStatus);
            });
        }

        // Incluir relacionamentos necessários
        $query->with([
            'form1.client',
            'form1.maintenanceType',
            'form1.machineNumber',
            'form1.status',
            'form2.employees',
            'form3',
            'form4',
            'form5.employee'
        ]);

        // Aplicar ordenação
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    // =============================================
    // COMPUTED PROPERTIES
    // =============================================

    public function getActiveFiltersCountProperty()
    {
        $count = 0;
        
        if ($this->search) $count++;
        if ($this->filterByForm !== 'all') $count++;
        if ($this->filterByClient) $count++;
        if ($this->filterByStatus) $count++;
        if ($this->filterByPeriod !== '30') $count++;
        
        return $count;
    }

    public function getHasPermissionToExportProperty()
    {
        return auth()->user()->can('repair_orders.export');
    }

    public function getHasPermissionToCreateProperty()
    {
        return auth()->user()->can('repair_orders.create');
    }

    // =============================================
    // RENDER
    // =============================================

    public function render()
    {
        return view('livewire.company.listings.repair-orders-list', [
            'orders' => $this->orders,
            'metrics' => $this->metrics,
        ])->layout('layouts.company', [
            'title' => 'Ordens de Reparação'
        ]);
    }
}
