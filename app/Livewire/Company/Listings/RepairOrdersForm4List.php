<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Location;
use App\Models\Company\RepairOrder\RepairOrderForm4;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersForm4List extends Component
{
    use WithPagination;

    public $search = '';
    public $filterByOrderNumber = '';
    public $filterByMachineNumber = '';
    public $filterByStatus = '';
    public $filterByLocation = '';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'table';

    public $statuses = [];
    public $locations = [];

    public $metrics = [];
    public $showMetrics = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterByOrderNumber' => ['except' => ''],
        'filterByMachineNumber' => ['except' => ''],
        'filterByStatus' => ['except' => ''],
        'filterByLocation' => ['except' => ''],
        'filterStartDate' => ['except' => ''],
        'filterEndDate' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
        'viewMode' => ['except' => 'table'],
    ];

    public function mount()
    {
        if (!auth()->user()->can('repair_orders.form4.view') && !auth()->user()->isCompanyAdmin()) {
            abort(403, 'Sem permissão para visualizar ordens do Formulário 4.');
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

        $query = RepairOrderForm4::with(['repairOrder', 'location', 'status']);

        if (!$user->can('repair_orders.view_all')) {
            if ($user->can('repair_orders.view_own')) {
                $query->whereHas('repairOrder.form2.employees', function ($q) use ($user) {
                    $q->where('employee_id', $user->employee_id);
                });
            } elseif ($user->can('repair_orders.view_department')) {
                $query->whereHas('repairOrder.form2.employees', function ($q) use ($user) {
                    $q->whereHas('employee', function ($empQ) use ($user) {
                        $empQ->where('department_id', $user->employee->department_id);
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
            'filterByOrderNumber',
            'filterByMachineNumber',
            'filterByStatus',
            'filterByLocation',
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

    public function continueOrder($form4Id)
    {
        $form4 = RepairOrderForm4::findOrFail($form4Id);
        return redirect()->route('company.orders.form5', $form4->repair_order_id);
    }

    public function viewOrder($form4Id)
    {
        $form4 = RepairOrderForm4::with(['repairOrder', 'location', 'status','machineNumber'])->findOrFail($form4Id);
        $this->dispatch('show-order-details', [
            'form4Id' => $form4Id,
            'form4Data' => $form4->toArray(),
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
                             ->orWhere('machine_number', 'like', '%' . $this->search . '%');
                    });
                });
            }

            if ($this->filterByOrderNumber) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('order_number', 'like', '%' . $this->filterByOrderNumber . '%');
                });
            }

            if ($this->filterByMachineNumber) {
                $query->whereHas('repairOrder', function ($q) {
                    $q->where('machine_number', 'like', '%' . $this->filterByMachineNumber . '%');
                });
            }

            if ($this->filterByStatus) {
                $query->where('status_id', $this->filterByStatus);
            }

            if ($this->filterByLocation) {
                $query->where('location_id', $this->filterByLocation);
            }

            $form4s = $query->get();

            $exportData = $form4s->map(function ($form4) {
                return [
                    'Ordem' => $form4->repairOrder?->order_number ?? '',
                    'Data' => $form4->carimbo?->format('d/m/Y H:i') ?? '',
                    'Localização' => $form4->location?->name ?? '',
                    'Status' => $form4->status?->name ?? '',
                    'Nº Máquina' => $form4->machine_number ?? $form4->repairOrder?->machine_number ?? '',
                ];
            })->toArray();

            $filename = 'ordens-form4-' . date('Y-m-d-H-i-s') . '.' . $format;
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
                    $html .= '<h1>Ordens de Reparação - Formulário 4</h1>';
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
            \Log::error('Erro na exportação Form4: ' . $e->getMessage());
        }
    }

    public function getForm4sProperty()
    {
        $query = $this->getBaseQuery();

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('repairOrder', function ($subQ) {
                    $subQ->where('order_number', 'like', '%' . $this->search . '%')
                         ->orWhere('machine_number', 'like', '%' . $this->search . '%');
                })
                ->orWhere('machine_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterByOrderNumber) {
            $query->whereHas('repairOrder', function ($q) {
                $q->where('order_number', 'like', '%' . $this->filterByOrderNumber . '%');
            });
        }

        if ($this->filterByMachineNumber) {
            $query->where(function ($q) {
                $q->whereHas('repairOrder', function ($subQ) {
                    $subQ->where('machine_number', 'like', '%' . $this->filterByMachineNumber . '%');
                })
                ->orWhere('machine_number', 'like', '%' . $this->filterByMachineNumber . '%');
            });
        }

        if ($this->filterByStatus) {
            $query->where('status_id', $this->filterByStatus);
        }

        if ($this->filterByLocation) {
            $query->where('location_id', $this->filterByLocation);
        }

        if ($this->sortField === 'repairOrder.order_number') {
            $query->join('repair_orders', 'repair_order_form4.repair_order_id', '=', 'repair_orders.id')
                  ->orderBy('repair_orders.order_number', $this->sortDirection);
        } elseif ($this->sortField === 'location.name') {
            $query->join('locations', 'repair_order_form4.location_id', '=', 'locations.id')
                  ->orderBy('locations.name', $this->sortDirection);
        } elseif ($this->sortField === 'status.name') {
            $query->join('statuses', 'repair_order_form4.status_id', '=', 'statuses.id')
                  ->orderBy('statuses.name', $this->sortDirection);
        } else {
            $query->orderBy('repair_order_form4.' . $this->sortField, $this->sortDirection);
        }

        return $query->select('repair_order_form4.*')->paginate($this->perPage);
    }

    public function getActiveFiltersCountProperty()
    {
        return collect([
            'search' => $this->search,
            'filterByOrderNumber' => $this->filterByOrderNumber,
            'filterByMachineNumber' => $this->filterByMachineNumber,
            'filterByStatus' => $this->filterByStatus,
            'filterByLocation' => $this->filterByLocation,
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
        return view('livewire.company.listings.repair-orders-form4-list',[
            'form4s' => $this->form4s,
            'metrics' => $this->metrics,
            'activeFiltersCount' => $this->activeFiltersCount,
            'hasPermissionToCreate' => $this->hasPermissionToCreate,
            'hasPermissionToExport' => $this->hasPermissionToExport,
        ])->layout('layouts.company', [
            'title' => 'Formulário 4 - Máquinas',
        ]);
    }
}
