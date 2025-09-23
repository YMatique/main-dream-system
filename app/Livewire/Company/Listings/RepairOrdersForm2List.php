<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Employee;
use App\Models\Company\Location;
use App\Models\Company\RepairOrder\RepairOrderForm2;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersForm2List extends Component
{
    use WithPagination;

    public $search = '';

    public $filterByEmployee = '';

    public $filterByStatus = '';

    public $filterByLocation = '';

    public $filterByMonthYear = '';

    public $filterStartDate = '';

    public $filterEndDate = '';

    public $perPage = 15;

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $viewMode = 'table';

    public $employees = [];

    public $statuses = [];

    public $locations = [];

    public $metrics = [];

    public $showMetrics = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterByEmployee' => ['except' => ''],
        'filterByStatus' => ['except' => ''],
        'filterByLocation' => ['except' => ''],
        'filterByMonthYear' => ['except' => ''],
        'filterStartDate' => ['except' => ''],
        'filterEndDate' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
        'viewMode' => ['except' => 'table'],
    ];

    public function mount($order = null)
    {
        // if (!auth()->user()->can('repair_orders.form2.view') && !auth()->user()->isCompanyAdmin()) {
        //     // abort(403, 'Sem permissão para visualizar ordens do Formulário 2.');
        //                 return  redirect()->route('company.my-permissions')->with('error', "Sem permissão para visualizar ordens do Formulário 2.");
        // }

        $this->loadFilterData();
        $this->calculateMetrics();

        if (! $this->filterStartDate && ! $this->filterEndDate) {
            $this->filterStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $this->filterEndDate = Carbon::now()->format('Y-m-d');
        }
    }

    public function updated($propertyName)
    {
        $this->resetPage();
        if (in_array($propertyName, ['filterStartDate', 'filterEndDate'])) {
            $this->calculateMetrics();
        }
        if ($propertyName === 'filterByMonthYear' && $this->filterByMonthYear) {
            if (! preg_match('/^\d{2}\/\d{4}$/', $this->filterByMonthYear)) {
                $this->filterByMonthYear = '';
                session()->flash('error', 'Formato de Mês/Ano inválido. Use MM/YYYY.');
            }
        }
    }

    private function loadFilterData()
    {
        $companyId = auth()->user()->company_id;

        $this->employees = Employee::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->statuses = Status::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->locations = Location::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function calculateMetrics()
    {
        $query = $this->getBaseQuery();

        $this->metrics = [
            'total_orders' => $query->clone()->count(),
            'orders_with_employees' => $query->clone()->whereHas('employees')->count(),
            'orders_with_additional_materials' => $query->clone()->whereHas('additionalMaterials')->count(),
            'completed_orders' => $query->clone()->whereHas('repairOrder', function ($q) {
                $q->whereHas('form5');
            })->count(),
            'period_start' => $this->filterStartDate ? Carbon::parse($this->filterStartDate)->format('d/m/Y') : null,
            'period_end' => $this->filterEndDate ? Carbon::parse($this->filterEndDate)->format('d/m/Y') : null,
        ];
    }

    private function getBaseQuery()
    {
        $companyId = auth()->user()->company_id;
        $user = auth()->user();

        $query = RepairOrderForm2::with(['repairOrder', 'location', 'status', 'employees.employee', 'materials.material', 'additionalMaterials']);

        if (! $user->can('repair_orders.view_all')) {
            if ($user->can('repair_orders.view_own')) {
                $query->whereHas('employees', function ($q) use ($user) {
                    $q->where('employee_id', $user->employee_id);
                });
            } elseif ($user->can('repair_orders.view_department')) {
                $query->whereHas('employees', function ($q) use ($user) {
                    $q->whereHas('department', function ($deptQ) use ($user) {
                        $deptQ->where('id', $user->employee->department_id);
                    });
                });
            }
        }

        if ($this->filterStartDate && $this->filterEndDate) {
            $query->whereBetween('carimbo', [
                Carbon::parse($this->filterStartDate)->startOfDay(),
                Carbon::parse($this->filterEndDate)->endOfDay(),
            ]);
        }

        return $query;
    }

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

    public function clearFilters()
    {
        $this->reset([
            'search',
            'filterByEmployee',
            'filterByStatus',
            'filterByLocation',
            'filterByMonthYear',
            'filterStartDate',
            'filterEndDate',
        ]);
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

    public function continueOrder($form2Id)
    {
        $form2 = RepairOrderForm2::findOrFail($form2Id);

        return redirect()->route('company.orders.form3', $form2->repair_order_id);
    }

    public function editOrder($orderId)
    {
        return redirect()->route('company.repair-orders.form1', $orderId);
    }

    public function viewOrder($form2Id)
    {
        $form2 = RepairOrderForm2::with(['repairOrder', 'location', 'status', 'employees.employee', 'materials.material', 'additionalMaterials'])->findOrFail($form2Id);
        $this->dispatch('show-order-details', [
            'form2Id' => $form2Id,
            'form2Data' => $form2->toArray(),
        ]);
    }

    public function exportOrders($format = 'excel')
    {
        if (! auth()->user()->can('repair_orders.export')) {
            session()->flash('error', 'Sem permissão para exportar dados.');

            return;
        }

        try {
            $query = $this->getBaseQuery();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas('repairOrder', function ($subQ) {
                        $subQ->where('order_number', 'like', '%'.$this->search.'%');
                    })
                        ->orWhereHas('employees.employee', function ($subQ) {
                            $subQ->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('materials.material', function ($subQ) {
                            $subQ->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhere('actividade_realizada', 'like', '%'.$this->search.'%');
                });
            }

            if ($this->filterByEmployee) {
                $query->whereHas('employees', function ($q) {
                    $q->where('employee_id', $this->filterByEmployee);
                });
            }

            if ($this->filterByStatus) {
                $query->where('status_id', $this->filterByStatus);
            }

            if ($this->filterByLocation) {
                $query->where('location_id', $this->filterByLocation);
            }

            if ($this->filterByMonthYear) {
                if (! preg_match('/^\d{2}\/\d{4}$/', $this->filterByMonthYear)) {
                    throw new \Exception('Formato de Mês/Ano inválido. Use MM/YYYY.');
                }
                [$month, $year] = explode('/', $this->filterByMonthYear);
                $query->whereMonth('carimbo', $month)->whereYear('carimbo', $year);
            }

            $form2s = $query->get();

            $exportData = $form2s->map(function ($form2) {
                return [
                    'Ordem' => $form2->repairOrder?->order_number ?? '',
                    'Data' => $form2->carimbo?->format('d/m/Y H:i') ?? '',
                    'Localização' => $form2->location?->name ?? '',
                    'Status' => $form2->status?->name ?? '',
                    'Tempo Total' => number_format($form2->tempo_total_horas ?? 0, 2).' h',
                    'Funcionários' => $form2->employees->isNotEmpty() ? $form2->employees->pluck('employee.name')->implode(', ') : '',
                    'Atividade Realizada' => $form2->actividade_realizada ?? '',
                ];
            })->toArray();

            $filename = 'ordens-form2-'.date('Y-m-d-H-i-s').'.'.$format;
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
                    $html .= '<h1>Ordens de Reparação - Formulário 2</h1>';
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
                    throw new \Exception('Formato de exportação não suportado.');
            }

            return response()->download($filePath, $filename)->deleteFileAfterSend();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao exportar dados: '.$e->getMessage());
            \Log::error('Erro na exportação Form2: '.$e->getMessage());
        }
    }

    public function getForm2sProperty()
    {
        $query = $this->getBaseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('repairOrder', function ($subQ) {
                    $subQ->where('order_number', 'like', '%'.$this->search.'%');
                })
                    ->orWhereHas('employees.employee', function ($subQ) {
                        $subQ->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('materials.material', function ($subQ) {
                        $subQ->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('actividade_realizada', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterByEmployee) {
            $query->whereHas('employees', function ($q) {
                $q->where('employee_id', $this->filterByEmployee);
            });
        }

        if ($this->filterByStatus) {
            $query->where('status_id', $this->filterByStatus);
        }

        if ($this->filterByLocation) {
            $query->where('location_id', $this->filterByLocation);
        }

        if ($this->filterByMonthYear) {
            if (! preg_match('/^\d{2}\/\d{4}$/', $this->filterByMonthYear)) {
                $this->filterByMonthYear = '';
                session()->flash('error', 'Formato de Mês/Ano inválido. Use MM/YYYY.');
            } else {
                [$month, $year] = explode('/', $this->filterByMonthYear);
                $query->whereMonth('carimbo', $month)->whereYear('carimbo', $year);
            }
        }

        // Ajuste para ordenação por colunas de relações
        if ($this->sortField === 'repairOrder.order_number') {
            $query->join('repair_orders', 'repair_order_form2.repair_order_id', '=', 'repair_orders.id')
                ->orderBy('repair_orders.order_number', $this->sortDirection);
        } elseif ($this->sortField === 'location.name') {
            $query->join('locations', 'repair_order_form2.location_id', '=', 'locations.id')
                ->orderBy('locations.name', $this->sortDirection);
        } elseif ($this->sortField === 'status.name') {
            $query->join('statuses', 'repair_order_form2.status_id', '=', 'statuses.id')
                ->orderBy('statuses.name', $this->sortDirection);
        } else {
            $query->orderBy('repair_order_form2.'.$this->sortField, $this->sortDirection);
        }

        return $query->select('repair_order_form2.*')->paginate($this->perPage);
    }

    public function getActiveFiltersCountProperty()
    {
        return collect([
            'search' => $this->search,
            'filterByEmployee' => $this->filterByEmployee,
            'filterByStatus' => $this->filterByStatus,
            'filterByLocation' => $this->filterByLocation,
            'filterByMonthYear' => $this->filterByMonthYear,
            'filterStartDate' => $this->filterStartDate,
            'filterEndDate' => $this->filterEndDate,
        ])->filter()->count();
    }

    public function getHasPermissionToExportProperty()
    {
        return auth()->user()->can('repair_orders.export');
    }

    public function getHasPermissionToCreateProperty()
    {
        return auth()->user()->can('repair_orders.create');
    }

    public function render()
    {
        return view('livewire.company.listings.repair-orders-form2-list', [
            'form2s' => $this->form2s,
            'metrics' => $this->metrics,
            'activeFiltersCount' => $this->activeFiltersCount,
            'hasPermissionToCreate' => $this->hasPermissionToCreate,
            'hasPermissionToExport' => $this->hasPermissionToExport,
        ])->layout('layouts.company', [
            'title' => 'Formulário 2 - Técnicos e Materiais',
        ]);
    }
}
