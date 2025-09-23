<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Client;
use App\Models\Company\MachineNumber;
use App\Models\Company\MaintenanceType;
use App\Models\Company\RepairOrder\RepairOrder;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersForm1List extends Component
{
    use WithPagination;

    // Propriedades de Filtros
    public $search = '';

    public $filterByClient = '';

    public $filterByMaintenanceType = '';

    public $filterByMonthYear = '';

    public $filterByMachine = '';

    public $filterStartDate = '';

    public $filterEndDate = '';

    // Propriedades de Configuração
    public $perPage = 15;

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $viewMode = 'table'; // table ou cards

    // Dados para Dropdowns
    public $clients = [];

    public $maintenanceTypes = [];

    public $machineNumbers = [];

    // Métricas
    public $metrics = [];

    public $showMetrics = true;

    public function mount()
    {
        // Verificar permissões
        // if (! auth()->user()->can('repair_orders.form1.view') && ! auth()->user()->isCompanyAdmin()) {
        //     // abort(403, 'Sem permissão para visualizar ordens do Formulário 1.');
        //     return redirect()->route('company.my-permissions')->with('error', 'Sem permissão para visualizar ordens do Formulário 1.');
        // }

        $this->loadFilterData();
        $this->calculateMetrics();

        // Configurar período padrão
        if (! $this->filterStartDate && ! $this->filterEndDate) {
            $this->filterStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $this->filterEndDate = Carbon::now()->format('Y-m-d');
        }
    }

    // Listeners de Filtros
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterByClient()
    {
        $this->resetPage();
    }

    public function updatedFilterByMaintenanceType()
    {
        $this->resetPage();
    }

    public function updatedFilterByMonthYear()
    {
        $this->resetPage();
    }

    public function updatedFilterByMachine()
    {
        $this->resetPage();
    }

    public function updatedFilterStartDate()
    {
        $this->resetPage();
        $this->calculateMetrics();
    }

    public function updatedFilterEndDate()
    {
        $this->resetPage();
        $this->calculateMetrics();
    }

    // Carregar dados para filtros
    private function loadFilterData()
    {
        $companyId = auth()->user()->company_id;

        $this->clients = Client::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->maintenanceTypes = MaintenanceType::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->machineNumbers = MachineNumber::where('company_id', $companyId)
            ->orderBy('number')
            ->get(['id', 'number']);
    }

    // Calcular métricas
    public function calculateMetrics()
    {
        $query = $this->getBaseQuery();

        $this->metrics = [
            'total_orders' => $query->clone()->count(),
            'period_start' => $this->filterStartDate ? Carbon::parse($this->filterStartDate)->format('d/m/Y') : null,
            'period_end' => $this->filterEndDate ? Carbon::parse($this->filterEndDate)->format('d/m/Y') : null,
        ];
    }

    // Query base
    private function getBaseQuery()
    {
        $companyId = auth()->user()->company_id;
        $user = auth()->user();

        $query = RepairOrder::where('company_id', $companyId)
            ->where('current_form', 'form1')
            ->with(['form1.client', 'form1.maintenanceType', 'form1.machineNumber']);

        // Aplicar permissões
        if (! $user->can('repair_orders.view_all')) {
            if ($user->can('repair_orders.view_own')) {
                $query->whereHas('form2.employees', function ($q) use ($user) {
                    $q->where('employee_id', $user->employee_id);
                });
            } elseif ($user->can('repair_orders.view_department')) {
                $query->whereHas('form2.employees.department', function ($q) use ($user) {
                    $q->where('id', $user->employee->department_id);
                });
            }
        }

        // Filtro de período
        if ($this->filterStartDate && $this->filterEndDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->filterStartDate)->startOfDay(),
                Carbon::parse($this->filterEndDate)->endOfDay(),
            ]);
        }

        return $query;
    }

    // Ordenação
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

    // Ações
    public function clearFilters()
    {
        $this->search = '';
        $this->filterByClient = '';
        $this->filterByMaintenanceType = '';
        $this->filterByMonthYear = '';
        $this->filterByMachine = '';
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

    public function continueOrder($orderId)
    {
        $order = RepairOrder::where('company_id', auth()->user()->company_id)
            ->where('id', $orderId)
            ->first();

        if (! $order) {
            session()->flash('error', 'Ordem não encontrada.');

            return;
        }

        return redirect()->route('company.repair-orders.form2', $order->id);
    }

    public function viewOrder($orderId)
    {
        $order = RepairOrder::where('company_id', auth()->user()->company_id)
            ->where('id', $orderId)
            ->with(['form1.client', 'form1.maintenanceType', 'form1.machineNumber'])
            ->first();

        if (! $order) {
            session()->flash('error', 'Ordem não encontrada.');

            return;
        }

        $this->dispatch('show-order-details', [
            'orderId' => $orderId,
            'orderData' => $order->getFullSummary(),
        ]);
    }

    public function exportOrders($format = 'excel')
    {

        if (! auth()->user()->can('repair_orders.export') || ! auth()->user()->isCompanyAdmin()) {
            session()->flash('error', 'Sem permissão para exportar dados.');

            return;
        }

        try {
            $query = $this->getBaseQuery();

            if ($this->search) {
                $query->search($this->search);
            }

            if ($this->filterByClient) {
                $query->whereHas('form1', function ($q) {
                    $q->where('client_id', $this->filterByClient);
                });
            }

            if ($this->filterByMaintenanceType) {
                $query->whereHas('form1', function ($q) {
                    $q->where('maintenance_type_id', $this->filterByMaintenanceType);
                });
            }

            if ($this->filterByMonthYear) {
                [$month, $year] = explode('/', $this->filterByMonthYear);
                $query->whereHas('form1', function ($q) use ($month, $year) {
                    $q->where('mes', $month)->where('ano', $year);
                });
            }

            if ($this->filterByMachine) {
                $query->whereHas('form1', function ($q) {
                    $q->where('machine_number_id', $this->filterByMachine);
                });
            }

            $orders = $query->orderBy($this->sortField, $this->sortDirection)->get();

            $exportData = $orders->map(function ($order) {
                return [
                    'Ordem' => $order->order_number,
                    'Cliente' => $order->form1?->client?->name ?? '',
                    'Tipo Manutenção' => $order->form1?->maintenanceType?->name ?? '',
                    'Máquina' => $order->form1?->machineNumber?->number ?? '',
                    'Data' => $order->form1?->carimbo->format('d/m/Y H:i') ?? '',
                    'Descrição Avaria' => $order->form1?->descricao_avaria ?? '',
                ];
            })->toArray();

            $filename = 'ordens-form1-'.date('Y-m-d-H-i-s').'.'.$format;
            $filePath = storage_path('app/temp/'.$filename);

            if (! file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            switch ($format) {
                case 'excel':
                case 'csv':
                    $handle = fopen($filePath, 'w');
                    if (! empty($exportData)) {
                        fputcsv($handle, array_keys($exportData[0]));
                        foreach ($exportData as $row) {
                            fputcsv($handle, $row);
                        }
                    }
                    fclose($handle);
                    break;
                case 'pdf':
                    $html = '<html><body>';
                    $html .= '<h1>Ordens de Reparação - Formulário 1</h1>';
                    $html .= '<p>Gerado em: '.date('d/m/Y H:i:s').'</p>';
                    if (! empty($exportData)) {
                        $html .= '<table border="1" cellpadding="5">';
                        $html .= '<tr>';
                        foreach (array_keys($exportData[0]) as $header) {
                            $html .= '<th>'.htmlspecialchars($header).'</th>';
                        }
                        $html .= '</tr>';
                        foreach ($exportData as $row) {
                            $html .= '<tr>';
                            foreach ($row as $cell) {
                                $html .= '<td>'.htmlspecialchars($cell).'</td>';
                            }
                            $html .= '</tr>';
                        }
                        $html .= '</table>';
                    }
                    $html .= '</body></html>';
                    file_put_contents($filePath, $html);
                    break;
                default:
                    session()->flash('error', 'Formato de exportação não suportado.');
                    throw new \Exception('Formato de exportação não suportado.');
            }

            return response()->download($filePath, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao exportar dados: '.$e->getMessage());
            \Log::error('Erro na exportação Form1: '.$e->getMessage());
        }
    }

    // Query principal
    public function getOrdersProperty()
    {
        $query = $this->getBaseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%'.$this->search.'%')
                    ->orWhereHas('form1.client', function ($subQ) {
                        $subQ->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('form1.machineNumber', function ($subQ) {
                        $subQ->where('number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('form1', function ($subQ) {
                        $subQ->where('descricao_avaria', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if ($this->filterByClient) {
            $query->whereHas('form1', function ($q) {
                $q->where('client_id', $this->filterByClient);
            });
        }

        if ($this->filterByMaintenanceType) {
            $query->whereHas('form1', function ($q) {
                $q->where('maintenance_type_id', $this->filterByMaintenanceType);
            });
        }

        if ($this->filterByMonthYear) {
            [$month, $year] = explode('/', $this->filterByMonthYear);
            $query->whereHas('form1', function ($q) use ($month, $year) {
                $q->where('mes', $month)->where('ano', $year);
            });
        }

        if ($this->filterByMachine) {
            $query->whereHas('form1', function ($q) {
                $q->where('machine_number_id', $this->filterByMachine);
            });
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // Contagem de filtros ativos
    public function getActiveFiltersCountProperty()
    {
        $count = 0;
        if ($this->search) {
            $count++;
        }
        if ($this->filterByClient) {
            $count++;
        }
        if ($this->filterByMaintenanceType) {
            $count++;
        }
        if ($this->filterByMonthYear) {
            $count++;
        }
        if ($this->filterByMachine) {
            $count++;
        }

        return $count;
    }

    public function getHasPermissionToExportProperty()
    {
        return auth()->user()->can('repair_orders.export') || auth()->user()->isCompanyAdmin();
    }

    public function getHasPermissionToCreateProperty()
    {
        return auth()->user()->can('repair_orders.create') || auth()->user()->isCompanyAdmin();
    }

    public function render()
    {
        return view('livewire.company.listings.repair-orders-form1-list', [
            'orders' => $this->orders,
            'metrics' => $this->metrics,
        ])->layout('layouts.company', [
            'title' => 'Ordens de Reparação - Formulário 1',
        ]);
    }
}
