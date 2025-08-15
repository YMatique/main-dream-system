<?php

namespace App\Livewire\Company;

use App\Models\Company\Billing\BillingEstimated;
use App\Models\Company\Billing\BillingHH;
use App\Models\Company\Billing\BillingReal;
use App\Models\Company\Client;
use App\Models\Company\Department;
use App\Models\Company\Employee;
use App\Models\Company\Evaluation\PerformanceEvaluation;
use App\Models\Company\RepairOrder\RepairOrder;
use App\Models\Company\RepairOrder\RepairOrderForm2Material;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
 
        public $selectedPeriod = 'current_month';
    public $dashboardData = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function updatedSelectedPeriod()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $companyId = auth()->user()->company_id;
        $dateRange = $this->getDateRange();

        $this->dashboardData = [
            'metrics' => $this->getMetrics($companyId, $dateRange),
            'charts' => $this->getChartsData($companyId, $dateRange),
            'recent_orders' => $this->getRecentOrders($companyId),
            'alerts' => $this->getAlerts($companyId),
        ];
    }

    private function getDateRange()
    {
        $now = Carbon::now();
        
        return match ($this->selectedPeriod) {
            'current_month' => [
                'start' => $now->startOfMonth()->copy(),
                'end' => $now->endOfMonth()->copy(),
                'previous_start' => $now->subMonth()->startOfMonth()->copy(),
                'previous_end' => $now->subMonth()->endOfMonth()->copy(),
            ],
            'last_month' => [
                'start' => $now->subMonth()->startOfMonth()->copy(),
                'end' => $now->subMonth()->endOfMonth()->copy(),
                'previous_start' => $now->subMonth()->startOfMonth()->copy(),
                'previous_end' => $now->subMonth()->endOfMonth()->copy(),
            ],
            'quarter' => [
                'start' => $now->startOfQuarter()->copy(),
                'end' => $now->endOfQuarter()->copy(),
                'previous_start' => $now->subQuarter()->startOfQuarter()->copy(),
                'previous_end' => $now->subQuarter()->endOfQuarter()->copy(),
            ],
            'year' => [
                'start' => $now->startOfYear()->copy(),
                'end' => $now->endOfYear()->copy(),
                'previous_start' => $now->subYear()->startOfYear()->copy(),
                'previous_end' => $now->subYear()->endOfYear()->copy(),
            ],
            default => [
                'start' => $now->startOfMonth()->copy(),
                'end' => $now->endOfMonth()->copy(),
                'previous_start' => $now->subMonth()->startOfMonth()->copy(),
                'previous_end' => $now->subMonth()->endOfMonth()->copy(),
            ],
        };
    }

    private function getMetrics($companyId, $dateRange)
    {
        // ===== ORDENS DE REPARAÇÃO =====
        $currentOrders = RepairOrder::where('repair_orders.company_id', $companyId)
            ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        $previousOrders = RepairOrder::where('repair_orders.company_id', $companyId)
            ->whereBetween('repair_orders.created_at', [$dateRange['previous_start'], $dateRange['previous_end']])
            ->count();

        $ordersPercentageChange = $previousOrders > 0 
            ? (($currentOrders - $previousOrders) / $previousOrders) * 100 
            : 0;

        // Status das ordens
        $ordersByStatus = RepairOrder::where('repair_orders.company_id', $companyId)
            ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->join('repair_order_form1', 'repair_orders.id', '=', 'repair_order_form1.repair_order_id')
            ->join('statuses', 'repair_order_form1.status_id', '=', 'statuses.id')
            ->select('statuses.name as status', DB::raw('count(*) as count'), 'statuses.color')
            ->groupBy('statuses.id', 'statuses.name', 'statuses.color')
            ->get()
            ->toArray();

        // ===== FATURAÇÃO =====
        $billingHH = $this->getBillingData(BillingHH::class, $companyId, $dateRange);
        $billingEstimated = $this->getBillingData(BillingEstimated::class, $companyId, $dateRange);
        $billingReal = $this->getBillingData(BillingReal::class, $companyId, $dateRange);
        
        // ===== FATURAÇÃO DE MATERIAIS =====
        $billingMaterials = $this->getMaterialsBillingData($companyId, $dateRange);

        // ===== FUNCIONÁRIOS =====
        $employeesActive = Employee::where('employees.company_id', $companyId)
            ->where('is_active', true)
            ->count();

        $employeesInactive = Employee::where('employees.company_id', $companyId)
            ->where('is_active', false)
            ->count();

        // Performance média
        $avgPerformance = PerformanceEvaluation::where('performance_evaluations.company_id', $companyId)
            ->where('status', 'approved')
            ->whereBetween('evaluation_period', [$dateRange['start'], $dateRange['end']])
            ->avg('final_percentage') ?? 0;

        $evaluationsPending = PerformanceEvaluation::where('performance_evaluations.company_id', $companyId)
            ->where('status', 'submitted')
            ->count();

        // ===== CLIENTES =====
        $clientsActive = Client::where('clients.company_id', $companyId)
            ->where('is_active', true)
            ->count();

        $clientsNewThisPeriod = Client::where('clients.company_id', $companyId)
            ->whereBetween('clients.created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        return [
            'orders' => [
                'current_period' => $currentOrders,
                'previous_period' => $previousOrders,
                'percentage_change' => round($ordersPercentageChange, 1),
                'by_status' => $ordersByStatus,
            ],
            'billing' => [
                'hh' => $billingHH,
                'estimated' => $billingEstimated,
                'real' => $billingReal,
                'materials' => $billingMaterials,
            ],
            'employees' => [
                'total_active' => $employeesActive,
                'total_inactive' => $employeesInactive,
                'avg_performance' => round($avgPerformance, 1),
                'evaluations_pending' => $evaluationsPending,
            ],
            'clients' => [
                'total_active' => $clientsActive,
                'new_this_period' => $clientsNewThisPeriod,
            ],
        ];
    }

    private function getBillingData($model, $companyId, $dateRange)
    {
        $data = $model::where('company_id', $companyId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                COUNT(*) as count,
                SUM(CASE WHEN billing_currency = "MZN" THEN 1 ELSE 0 END) as mzn_count,
                SUM(CASE WHEN billing_currency = "USD" THEN 1 ELSE 0 END) as usd_count,
                SUM(CASE WHEN billing_currency = "MZN" THEN billed_amount ELSE 0 END) as total_mzn,
                SUM(CASE WHEN billing_currency = "USD" THEN billed_amount ELSE 0 END) as total_usd
            ')
            ->first();

        $totalCount = $data->count ?: 1; // Evitar divisão por zero

        // dd($data);
        return [
            'total_mzn' => $data->total_mzn ?: 0,
            'total_usd' => $data->total_usd ?: 0,
            'count' => $data->count ?: 0,
            'currency_split' => [
                'mzn' => round(($data->mzn_count / $totalCount) * 100, 0),
                'usd' => round(($data->usd_count / $totalCount) * 100, 0),
            ],
        ];
    }

    private function getMaterialsBillingData($companyId, $dateRange)
    {
        // Calcular faturação de materiais baseado nas ordens do período
        $materialsData = RepairOrderForm2Material::whereHas('form2.repairOrder', function($query) use ($companyId, $dateRange) {
                $query->where('repair_orders.company_id', $companyId)
                      ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']]);
            })
            ->join('materials', 'repair_order_form2_materials.material_id', '=', 'materials.id')
            ->selectRaw('
                COUNT(DISTINCT repair_order_form2_materials.form2_id) as orders_count,
                SUM(repair_order_form2_materials.quantidade * materials.cost_per_unit_mzn) as total_mzn,
                SUM(repair_order_form2_materials.quantidade * materials.cost_per_unit_usd) as total_usd
            ')
            ->first();

        // Materiais adicionais (não cadastrados) - baseado no modelo atual
        $additionalMaterialsData = DB::table('repair_order_form2_additional_materials')
            ->join('repair_order_form2', 'repair_order_form2_additional_materials.form2_id', '=', 'repair_order_form2.id')
            ->join('repair_orders', 'repair_order_form2.repair_order_id', '=', 'repair_orders.id')
            ->where('repair_orders.company_id', $companyId)
            ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                SUM(repair_order_form2_additional_materials.custo_total) as additional_total_mzn,
                SUM(repair_order_form2_additional_materials.custo_total) as additional_total_usd
            ')
            ->first();

        return [
            'total_mzn' => ($materialsData->total_mzn ?: 0) + ($additionalMaterialsData->additional_total_mzn ?: 0),
            'total_usd' => ($materialsData->total_usd ?: 0) + ($additionalMaterialsData->additional_total_usd ?: 0),
            'orders_count' => $materialsData->orders_count ?: 0,
            'materials_count' => RepairOrderForm2Material::whereHas('form2.repairOrder', function($query) use ($companyId, $dateRange) {
                    $query->where('repair_orders.company_id', $companyId)
                          ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']]);
                })->distinct('material_id')->count(),
        ];
    }

    private function getChartsData($companyId, $dateRange)
    {
        // ===== EVOLUÇÃO MENSAL =====
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $orders = RepairOrder::where('repair_orders.company_id', $companyId)
                ->whereBetween('repair_orders.created_at', [$startOfMonth, $endOfMonth])
                ->count();

            $billing = BillingReal::where('billing_real.company_id', $companyId)
                ->whereBetween('billing_real.created_at', [$startOfMonth, $endOfMonth])
                ->sum('billed_amount');

            $monthlyData->push([
                'month' => $date->format('M'),
                'orders' => $orders,
                'billing_mzn' => round($billing / 1000, 0), // Em milhares
                'revenue' => $billing,
            ]);
        }

        // ===== TOP CLIENTES =====
        $topClients = RepairOrder::where('repair_orders.company_id', $companyId)
            ->whereBetween('repair_orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->join('repair_order_form1', 'repair_orders.id', '=', 'repair_order_form1.repair_order_id')
            ->join('clients', 'repair_order_form1.client_id', '=', 'clients.id')
            ->leftJoin('billing_real', 'repair_orders.id', '=', 'billing_real.repair_order_id')
            ->select(
                'clients.name',
                DB::raw('COUNT(repair_orders.id) as orders'),
                DB::raw('COALESCE(SUM(billing_real.billed_amount), 0) as billing')
            )
            ->groupBy('clients.id', 'clients.name')
            ->orderBy('orders', 'desc')
            ->limit(5)
            ->get()
            ->map(function($client) {
                return [
                    'name' => $client->name,
                    'orders' => $client->orders,
                    'billing' => $client->billing,
                    'growth' => rand(-10, 30), // Simular crescimento - implementar lógica real depois
                ];
            });

        // ===== PERFORMANCE POR DEPARTAMENTO =====
        $departmentPerformance = Department::where('departments.company_id', $companyId)
            ->where('departments.is_active', true)
            ->withCount(['employees' => function($query) {
                $query->where('employees.is_active', true);
            }])
            ->get()
            ->map(function($dept) use ($dateRange) {
                $avgScore = PerformanceEvaluation::whereHas('employee', function($query) use ($dept) {
                        $query->where('employees.department_id', $dept->id);
                    })
                    ->where('performance_evaluations.status', 'approved')
                    ->whereBetween('performance_evaluations.evaluation_period', [$dateRange['start'], $dateRange['end']])
                    ->avg('final_percentage') ?? 0;

                return [
                    'department' => $dept->name,
                    'avg_score' => round($avgScore, 1),
                    'employees' => $dept->employees_count,
                    'trend' => $avgScore >= 85 ? 'up' : ($avgScore >= 70 ? 'stable' : 'down'),
                ];
            });

        // ===== COMPARAÇÃO DE FATURAÇÃO =====
        $billingComparison = [
            [
                'type' => 'HH',
                'mzn' => $this->dashboardData['metrics']['billing']['hh']['total_mzn'] ?? 0,
                'usd' => $this->dashboardData['metrics']['billing']['hh']['total_usd'] ?? 0,
            ],
            [
                'type' => 'Estimada',
                'mzn' => $this->dashboardData['metrics']['billing']['estimated']['total_mzn'] ?? 0,
                'usd' => $this->dashboardData['metrics']['billing']['estimated']['total_usd'] ?? 0,
            ],
            [
                'type' => 'Real',
                'mzn' => $this->dashboardData['metrics']['billing']['real']['total_mzn'] ?? 0,
                'usd' => $this->dashboardData['metrics']['billing']['real']['total_usd'] ?? 0,
            ],
            [
                'type' => 'Materiais',
                'mzn' => $this->dashboardData['metrics']['billing']['materials']['total_mzn'] ?? 0,
                'usd' => $this->dashboardData['metrics']['billing']['materials']['total_usd'] ?? 0,
            ],
        ];

        // ===== DISTRIBUIÇÃO DE STATUS =====
        $statusDistribution = collect($this->dashboardData['metrics']['orders']['by_status'] ?? [])
            ->map(function($status) {
                return [
                    'name' => $status['status'],
                    'value' => $status['count'],
                    'color' => $status['color'] ?? '#6B7280',
                ];
            });

        return [
            'monthly_orders' => $monthlyData,
            'top_clients' => $topClients,
            'department_performance' => $departmentPerformance,
            'billing_comparison' => $billingComparison,
            'status_distribution' => $statusDistribution,
        ];
    }

    private function getRecentOrders($companyId)
    {
        return RepairOrder::where('repair_orders.company_id', $companyId)
            ->with(['form1.client', 'form1.status', 'form2.employees'])
            ->orderBy('repair_orders.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->order_number ?? 'ORD-' . $order->id,
                    'client' => $order->form1->client->name ?? 'N/A',
                    'status' => $order->form1->status->name ?? 'N/A',
                    'days_ago' => $order->created_at->diffInDays(now()),
                    'technician' => $order->form2?->employees?->first()?->name ?? 'N/A',
                    'priority' => rand(0, 2) == 0 ? 'high' : (rand(0, 1) == 0 ? 'medium' : 'low'), // Simular prioridade
                ];
            });
    }

    // private function getAlerts($companyId)
    // {
    //     $alerts = [];

    //     // Ordens com prazo vencendo
    //     $ordersOverdue = RepairOrder::where('repair_orders.company_id', $companyId)
    //         ->whereHas('form3', function($query) {
    //             $query->where('data_faturacao', '<', now()->subDays(7));
    //         })
    //         ->whereDoesntHave('billingReal')
    //         ->count();

    //     if ($ordersOverdue > 0) {
    //         $alerts[] = [
    //             'type' => 'warning',
    //             'message' => "{$ordersOverdue} Ordens com prazo de faturação vencendo",
    //             'count' => $ordersOverdue,
    //             'icon' => '⚠️',
    //         ];
    //     }

    //     // Avaliações pendentes
    //     $evaluationsPending = PerformanceEvaluation::where('performance_evaluations.company_id', $companyId)
    //         ->where('status', 'submitted')
    //         ->count();

    //     if ($evaluationsPending > 0) {
    //         $alerts[] = [
    //             'type' => 'info',
    //             'message' => "{$evaluationsPending} Avaliações aguardando aprovação",
    //             'count' => $evaluationsPending,
    //             'icon' => '📋',
    //         ];
    //     }

    //     // Ordens concluídas hoje
    //     $ordersCompletedToday = RepairOrder::where('repair_orders.company_id', $companyId)
    //         ->whereHas('form1.status', function($query) {
    //             $query->where('name', 'Concluída');
    //         })
    //         ->whereDate('repair_orders.updated_at', today())
    //         ->count();

    //     if ($ordersCompletedToday > 0) {
    //         $alerts[] = [
    //             'type' => 'success',
    //             'message' => "{$ordersCompletedToday} Ordens concluídas hoje",
    //             'count' => $ordersCompletedToday,
    //             'icon' => '✅',
    //         ];
    //     }

    //     return $alerts;
    // }

    private function getAlerts($companyId)
    {
        $alerts = [];

        // Ordens com prazo vencendo
        $ordersOverdue = RepairOrder::where('company_id', $companyId)
            ->whereHas('form3', function($query) {
                $query->where('data_faturacao', '<', now()->subDays(7));
            })
            ->whereDoesntHave('billingReal')
            ->count();

        if ($ordersOverdue > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$ordersOverdue} Ordens com prazo de faturação vencendo",
                'count' => $ordersOverdue,
                'icon' => '⚠️',
            ];
        }

        // Avaliações pendentes
        $evaluationsPending = PerformanceEvaluation::where('company_id', $companyId)
            ->where('status', 'submitted')
            ->count();

        if ($evaluationsPending > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$evaluationsPending} Avaliações aguardando aprovação",
                'count' => $evaluationsPending,
                'icon' => '📋',
            ];
        }

        // Ordens concluídas hoje
        $ordersCompletedToday = RepairOrder::where('company_id', $companyId)
            ->whereHas('form1.status', function($query) {
                $query->where('name', 'Concluída');
            })
            ->whereDate('updated_at', today())
            ->count();

        if ($ordersCompletedToday > 0) {
            $alerts[] = [
                'type' => 'success',
                'message' => "{$ordersCompletedToday} Ordens concluídas hoje",
                'count' => $ordersCompletedToday,
                'icon' => '✅',
            ];
        }

        return $alerts;
    }

    public function exportReport()
    {
        // Implementar exportação de relatório
        $this->dispatch('show-notification', [
            'type' => 'info',
            'message' => 'Funcionalidade de exportação será implementada em breve.'
        ]);
    }


    #[Layout('layouts.company')]
    #[Title('Dashboard')]

    public function render()
    {
        return view('livewire.company.dashboard');
    }
}
