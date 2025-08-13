<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Client;
use App\Models\Company\Employee;
use App\Models\Company\Location;
use App\Models\Company\MaintenanceType;
use App\Models\Company\RepairOrder\RepairOrderForm5;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersForm5List extends Component
{
      use WithPagination;

    public $search = '';
    public $filterByOrderNumber = '';
    public $filterByMachineNumber = '';
    public $filterByClient = '';
    public $filterByTechnician = '';
    public $filterByMaintenanceType = '';
    public $filterByStatus = '';
    public $filterByLocation = '';
    public $filterByDescription = '';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'table';

    public $clients = [];
    public $technicians = [];
    public $maintenanceTypes = [];
    public $statuses = [];
    public $locations = [];

    public $metrics = [];
    public $showMetrics = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterByOrderNumber' => ['except' => ''],
        'filterByMachineNumber' => ['except' => ''],
        'filterByClient' => ['except' => ''],
        'filterByTechnician' => ['except' => ''],
        'filterByMaintenanceType' => ['except' => ''],
        'filterByStatus' => ['except' => ''],
        'filterByLocation' => ['except' => ''],
        'filterByDescription' => ['except' => ''],
        'filterStartDate' => ['except' => ''],
        'filterEndDate' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
        'viewMode' => ['except' => 'table'],
    ];

    public function mount()
    {
        if (!auth()->user()->can('repair_orders.form5.view') && !auth()->user()->isCompanyAdmin()) {
            abort(403, 'Sem permissão para visualizar ordens do Formulário 5.');
        }

        $this->loadFilterData();
        $this->calculateMetrics();

        if (!$this->filterStartDate && !$this->filterEndDate) {
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
    }

    private function loadFilterData()
    {
        $companyId = auth()->user()->company_id;

        $this->clients = Client::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->technicians = Employee::where('company_id', $companyId)
            ->whereHas('department', function ($q) {
                $q->where('is_active', true);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->maintenanceTypes = MaintenanceType::where('company_id', $companyId)
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
            'total_billed_hours' => $query->clone()->sum(\DB::raw('horas_faturadas_1 + horas_faturadas_2')),
            'average_billed_hours' => $query->clone()->count() > 0 ? round($query->clone()->avg(\DB::raw('horas_faturadas_1 + horas_faturadas_2')), 2) : 0,
            'period_start' => $this->filterStartDate ? Carbon::parse($this->filterStartDate)->format('d/m/Y') : null,
            'period_end' => $this->filterEndDate ? Carbon::parse($this->filterEndDate)->format('d/m/Y') : null,
        ];
    }

    private function getBaseQuery()
    {
        $companyId = auth()->user()->company_id;
        $user = auth()->user();

        $query = RepairOrderForm5::with(['repairOrder', 'client', 'employee', ])
            ->whereHas('repairOrder', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });

        if (!$user->can('repair_orders.view_all')) {
            if ($user->can('repair_orders.view_own')) {
                $query->where('employee_id', $user->employee_id);
            } elseif ($user->can('repair_orders.view_department')) {
                $query->whereHas('employee', function ($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
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
            'filterByOrderNumber',
            'filterByMachineNumber',
            'filterByClient',
            'filterByTechnician',
            'filterByMaintenanceType',
            'filterByStatus',
            'filterByLocation',
            'filterByDescription',
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

    public function viewOrder($form5Id)
    {
        $form5 = RepairOrderForm5::with([
            'repairOrder',
            'client',
            'employee',
            'repairOrder.maintenanceType',
            'repairOrder.status',
            'repairOrder.location'
        ])->findOrFail($form5Id);
        $this->dispatch('show-order-details', [
            'form5Id' => $form5Id,
            'form5Data' => $form5->toArray(),
        ]);
    }

    public function exportOrders($format = 'excel')
    {
        if (!auth()->user()->can('repair_orders.export')) {
            session()->flash('error', 'Sem permissão para exportar dados.');
            return;
        }

        try {
            $query = $this->getBaseQuery();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas('repairOrder', function ($subQ) {
                        $subQ->where('order_number', 'like', '%' . $this->search . '%')
                             ->orWhere('machine_number', 'like', '%' . $this->search . '%')
                             ->orWhere('description', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('activity_description', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->filterByOrderNumber) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('order_number', 'like', '%' . $this->filterByOrderNumber . '%');
                });
            }

            if ($this->filterByMachineNumber) {
                $query->where('machine_number', 'like', '%' . $this->filterByMachineNumber . '%');
            }

            if ($this->filterByClient) {
                $query->where('client_id', $this->filterByClient);
            }

            if ($this->filterByTechnician) {
                $query->where('employee_id', $this->filterByTechnician);
            }

            if ($this->filterByMaintenanceType) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('maintenance_type_id', $this->filterByMaintenanceType);
                });
            }

            if ($this->filterByStatus) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('status_id', $this->filterByStatus);
                });
            }

            if ($this->filterByLocation) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('location_id', $this->filterByLocation);
                });
            }

            if ($this->filterByDescription) {
                $query->where(function ($q) {
                    $q->where('activity_description', 'like', '%' . $this->filterByDescription . '%')
                      ->orWhereHas('repairOrder', function ($subQ) {
                          $subQ->where('description', 'like', '%' . $this->filterByDescription . '%');
                      });
                });
            }

            $form5s = $query->get();

            $exportData = $form5s->map(function ($form5) {
                $totalHours = ($form5->billed_hours_1 ?? 0) + ($form5->billed_hours_2 ?? 0);
                $estimatedCost = $form5->repairOrder?->maintenanceType?->cost_per_hour * $totalHours ?? 0;
                return [
                    'Ordem' => $form5->repairOrder?->order_number ?? '',
                    'Data' => $form5->carimbo?->format('d/m/Y H:i') ?? '',
                    'Tempo de Execução (h)' => number_format($totalHours, 2),
                    'Valor Estimado (MZN)' => number_format($estimatedCost, 2),
                    'Nº Máquina' => $form5->machine_number ?? '',
                    'Técnico' => $form5->employee?->name ?? '',
                    'Tipo de Manutenção' => $form5->repairOrder?->maintenanceType?->name ?? '',
                    'Descrição de Avaria' => $form5->repairOrder?->description ?? '',
                ];
            })->toArray();

            $filename = 'ordens-form5-' . date('Y-m-d-H-i-s') . '.' . $format;
            $filePath = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            switch ($format) {
                case 'excel':
                case 'csv':
                    $handle = fopen($filePath, 'w');
                    if (!empty($exportData)) {
                        fputcsv($handle, array_keys($exportData[0]));
                        foreach ($exportData as $row) {
                            fputcsv($handle, $row);
                        }
                    }
                    fclose($handle);
                    break;
                case 'pdf':
                    $html = '<html><body>';
                    $html .= '<h1>Ordens de Reparação - Formulário 5</h1>';
                    $html .= '<p>Gerado em: ' . date('d/m/Y H:i:s') . '</p>';
                    if (!empty($exportData)) {
                        $html .= '<table border="1" cellpadding="5">';
                        $html .= '<tr>';
                        foreach (array_keys($exportData[0]) as $header) {
                            $html .= '<th>' . htmlspecialchars($header) . '</th>';
                        }
                        $html .= '</tr>';
                        foreach ($exportData as $row) {
                            $html .= '<tr>';
                            foreach ($row as $cell) {
                                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
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
            session()->flash('error', 'Erro ao exportar dados: ' . $e->getMessage());
            \Log::error('Erro na exportação Form5: ' . $e->getMessage());
        }
    }

    public function getForm5sProperty()
    {
        $query = $this->getBaseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('repairOrder', function ($subQ) {
                    $subQ->where('order_number', 'like', '%' . $this->search . '%')
                         ->orWhere('machine_number', 'like', '%' . $this->search . '%')
                         ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orWhere('activity_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterByOrderNumber) {
            $query->whereHas('repairOrder', function ($q) {
                $q->where('order_number', 'like', '%' . $this->filterByOrderNumber . '%');
            });
        }

        if ($this->filterByMachineNumber) {
            $query->where('machine_number', 'like', '%' . $this->filterByMachineNumber . '%');
        }

        if ($this->filterByClient) {
            $query->where('client_id', $this->filterByClient);
        }

        if ($this->filterByTechnician) {
            $query->where('employee_id', $this->filterByTechnician);
        }

        if ($this->filterByMaintenanceType) {
            $query->whereHas('repairOrder', function ($q) {
                $q->where('maintenance_type_id', $this->filterByMaintenanceType);
            });
        }

        if ($this->filterByStatus) {
            $query->whereHas('repairOrder', function ($q) {
                $q->where('status_id', $this->filterByStatus);
            });
        }

        if ($this->filterByLocation) {
            $query->whereHas('repairOrder', function ($q) {
                $q->where('location_id', $this->filterByLocation);
            });
        }

        if ($this->filterByDescription) {
            $query->where(function ($q) {
                $q->where('activity_description', 'like', '%' . $this->filterByDescription . '%')
                  ->orWhereHas('repairOrder', function ($subQ) {
                      $subQ->where('description', 'like', '%' . $this->filterByDescription . '%');
                  });
            });
        }

        if ($this->sortField === 'repairOrder.order_number') {
            $query->join('repair_orders', 'repair_order_form5.repair_order_id', '=', 'repair_orders.id')
                  ->orderBy('repair_orders.order_number', $this->sortDirection);
        } elseif ($this->sortField === 'client.name') {
            $query->join('clients', 'repair_order_form5.client_id', '=', 'clients.id')
                  ->orderBy('clients.name', $this->sortDirection);
        } elseif ($this->sortField === 'employee.name') {
            $query->join('employees', 'repair_order_form5.employee_id', '=', 'employees.id')
                  ->orderBy('employees.name', $this->sortDirection);
        } elseif ($this->sortField === 'repairOrder.maintenanceType.name') {
            $query->join('repair_orders', 'repair_order_form5.repair_order_id', '=', 'repair_orders.id')
                  ->join('maintenance_types', 'repair_orders.maintenance_type_id', '=', 'maintenance_types.id')
                  ->orderBy('maintenance_types.name', $this->sortDirection);
        } else {
            $query->orderBy('repair_order_form5.' . $this->sortField, $this->sortDirection);
        }

        return $query->select('repair_order_form5.*')->paginate($this->perPage);
    }

    public function getActiveFiltersCountProperty()
    {
        return collect([
            'search' => $this->search,
            'filterByOrderNumber' => $this->filterByOrderNumber,
            'filterByMachineNumber' => $this->filterByMachineNumber,
            'filterByClient' => $this->filterByClient,
            'filterByTechnician' => $this->filterByTechnician,
            'filterByMaintenanceType' => $this->filterByMaintenanceType,
            'filterByStatus' => $this->filterByStatus,
            'filterByLocation' => $this->filterByLocation,
            'filterByDescription' => $this->filterByDescription,
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
        return view('livewire.company.listings.repair-orders-form5-list', [
            'form5s' => $this->form5s,
            'metrics' => $this->metrics,
            'activeFiltersCount' => $this->activeFiltersCount,
            'hasPermissionToCreate' => $this->hasPermissionToCreate,
            'hasPermissionToExport' => $this->hasPermissionToExport,
        ])->layout('layouts.company', [
            'title' => 'Formulário 5 - Faturação Final',
        ]);
    }
}
