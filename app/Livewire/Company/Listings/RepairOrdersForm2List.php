<?php

namespace App\Livewire\Company\Listings;

use App\Models\Company\Employee;
use App\Models\Company\Location;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\Status;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RepairOrdersForm2List extends Component
{
    use WithPagination;

    public $search = '';
    public $filterByTechnician = '';
    public $filterByState = '';
    public $filterByLocation = '';
    public $filterByMonthYear = '';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $sortField = 'order_number';
    public $sortDirection = 'asc';
    public $perPage = 15;
    public $viewMode = 'table';
    public $showMetrics = true;
    public $metrics = [];
    public $activeFiltersCount = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterByTechnician' => ['except' => ''],
        'filterByState' => ['except' => ''],
        'filterByLocation' => ['except' => ''],
        'filterByMonthYear' => ['except' => ''],
        'filterStartDate' => ['except' => ''],
        'filterEndDate' => ['except' => ''],
        'sortField' => ['except' => 'order_number'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 15],
        'viewMode' => ['except' => 'table'],
    ];

    public function mount()
    {
        $this->calculateMetrics();
    }

    public function updated($propertyName)
    {
        $this->resetPage();
        $this->calculateMetrics();
        $this->activeFiltersCount = collect([
            'search' => $this->search,
            'filterByTechnician' => $this->filterByTechnician,
            'filterByState' => $this->filterByState,
            'filterByLocation' => $this->filterByLocation,
            'filterByMonthYear' => $this->filterByMonthYear,
            'filterStartDate' => $this->filterStartDate,
            'filterEndDate' => $this->filterEndDate,
        ])->filter()->count();
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

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'table' ? 'cards' : 'table';
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'filterByTechnician',
            'filterByState',
            'filterByLocation',
            'filterByMonthYear',
            'filterStartDate',
            'filterEndDate',
        ]);
        $this->resetPage();
        $this->activeFiltersCount = 0;
    }

    public function refreshData()
    {
        $this->resetPage();
        $this->calculateMetrics();
        $this->emit('refresh-complete');
    }

    public function viewOrder($orderId)
    {
        $order = RepairOrder::with('form2')->findOrFail($orderId);
        $this->emit('show-order-details', ['orderId' => $orderId, 'orderData' => $order->toArray()]);
    }

    public function continueOrder($orderId)
    {
        return redirect()->route('company.orders.form3', ['order' => $orderId]);
    }

    public function exportOrders($format)
    {
        // Implementar lógica de exportação (Excel, CSV, PDF)
        // Exemplo: return $this->exportTo($format);
    }

    protected function calculateMetrics()
    {
        $query = $this->getBaseQuery();

        $this->metrics = [
            'total_orders' => $query->clone()->count(),
            'orders_with_technicians' => $query->clone()->whereHas('form2.employees')->count(),
            'orders_with_additional_materials' => $query->clone()->whereNotNull('form2.additionalMaterials')->count(),
            'completed_orders' => $query->clone()->whereHas('form5')->count(),
            'period_start' => $this->filterStartDate ? Carbon::parse($this->filterStartDate)->format('d/m/Y') : null,
            'period_end' => $this->filterEndDate ? Carbon::parse($this->filterEndDate)->format('d/m/Y') : null,
        ];
    }

    protected function getBaseQuery()
    {
        $query = RepairOrder::query()
            ->whereHas('form2')
            ->with(['form2.location', 'form2.state', 'form2.technicians']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('form2.technicians', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('form2.materials', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        if ($this->filterByTechnician) {
            $query->whereHas('form2.technicians', fn($q) => $q->where('id', $this->filterByTechnician));
        }

        if ($this->filterByState) {
            $query->whereHas('form2.state', fn($q) => $q->where('id', $this->filterByState));
        }

        if ($this->filterByLocation) {
            $query->whereHas('form2.location', fn($q) => $q->where('id', $this->filterByLocation));
        }

        if ($this->filterByMonthYear) {
            [$month, $year] = explode('/', $this->filterByMonthYear);
            $query->whereMonth('form2.carimbo', $month)->whereYear('form2.carimbo', $year);
        }

        if ($this->filterStartDate) {
            $query->where('form2.carimbo', '>=', Carbon::parse($this->filterStartDate));
        }

        if ($this->filterEndDate) {
            $query->where('form2.carimbo', '<=', Carbon::parse($this->filterEndDate));
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }
    public function render()
    {
        return view('livewire.company.listings.repair-orders-form2-list',[
            'orders' => $this->getBaseQuery()->paginate($this->perPage),
            'technicians' => Employee::orderBy('name')->get(),
            'states' => Status::where('form_number', 2)->orderBy('name')->get(),
            'locations' => Location::where('form_number', 2)->orderBy('name')->get(),
        ]);
    }
}
