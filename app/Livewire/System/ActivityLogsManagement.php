<?php

namespace App\Livewire\System;

use App\Models\System\ActivityLog;
use App\Models\System\Company;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogsManagement extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $userFilter = '';
    public $companyFilter = '';
    public $actionFilter = '';
    public $categoryFilter = '';
    public $levelFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 25;

    // Modal para detalhes
    public $showDetailsModal = false;
    public $selectedLog = null;

    // Opções para filtros
    public $users = [];
    public $companies = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'userFilter' => ['except' => ''],
        'companyFilter' => ['except' => ''],
        'actionFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'levelFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function mount()
    {
        // Carregar opções para filtros
        $this->users = User::select('id', 'name')
            ->orderBy('name')
            ->get();
            
        $this->companies = Company::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Definir data padrão (últimos 30 dias)
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUserFilter()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingLevelFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'userFilter', 'companyFilter', 'actionFilter', 
            'categoryFilter', 'levelFilter'
        ]);
        
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        
        $this->resetPage();
    }

    public function showDetails($logId)
    {
        $this->selectedLog = ActivityLog::with(['user', 'company'])->find($logId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedLog = null;
    }

    public function exportLogs()
    {
        // Aqui você pode implementar a exportação
        // Por exemplo, usando Laravel Excel ou gerando CSV/PDF
        session()->flash('message', 'Função de exportação será implementada em breve.');
    }

    public function render()
    {
        $logs = ActivityLog::query()
            ->with(['user', 'company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('action', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('company', function ($companyQuery) {
                          $companyQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->userFilter, function ($query) {
                $query->where('user_id', $this->userFilter);
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->actionFilter, function ($query) {
                $query->where('action', $this->actionFilter);
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->levelFilter, function ($query) {
                $query->where('level', $this->levelFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->where('created_at', '>=', Carbon::parse($this->dateFrom)->startOfDay());
            })
            ->when($this->dateTo, function ($query) {
                $query->where('created_at', '<=', Carbon::parse($this->dateTo)->endOfDay());
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Estatísticas para o dashboard
        $stats = [
            'total_today' => ActivityLog::whereDate('created_at', today())->count(),
            'total_week' => ActivityLog::where('created_at', '>=', now()->subWeek())->count(),
            'total_month' => ActivityLog::where('created_at', '>=', now()->subMonth())->count(),
            'errors_today' => ActivityLog::whereDate('created_at', today())
                ->whereIn('level', ['error', 'critical'])->count(),
        ];

        // Ações mais comuns
        $commonActions = ActivityLog::selectRaw('action, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Categorias para filtro
        $categories = [
            'auth' => 'Autenticação',
            'system' => 'Sistema',
            'company' => 'Empresa',
            'user' => 'Usuário',
            'repair_order' => 'Ordens de Reparação',
            'billing' => 'Faturação',
            'employee' => 'Funcionários',
            'client' => 'Clientes',
            'material' => 'Materiais',
            'performance' => 'Desempenho'
        ];

        // Níveis para filtro
        $levels = [
            'info' => 'Informação',
            'warning' => 'Aviso',
            'error' => 'Erro',
            'critical' => 'Crítico'
        ];
        return view('livewire.system.activity-logs-management',[
            'logs' => $logs,
            'stats' => $stats,
            'commonActions' => $commonActions,
            'categories' => $categories,
            'levels' => $levels
        ]);
    }
     // Métodos auxiliares para a interface
    public function getActionOptions()
    {
        return ActivityLog::distinct()
            ->pluck('action')
            ->sort()
            ->mapWithKeys(function ($action) {
                return [$action => ucfirst(str_replace('_', ' ', $action))];
            });
    }

    public function getLevelBadgeClass($level)
    {
        return match($level) {
            'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'error' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'critical' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
    }

    public function getCategoryBadgeClass($category)
    {
        return match($category) {
            'auth' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
            'system' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'company' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            'repair_order' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'billing' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
    }
}
