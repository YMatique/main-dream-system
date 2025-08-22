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
use App\Models\Company\RepairOrder\RepairOrderForm2AdditionalMaterial;
use App\Models\Company\RepairOrder\RepairOrderForm2Material;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{

    // Propriedades para filtros avançados
    public $selectedPeriod = 'current_month';
    public $customStartDate = '';
    public $customEndDate = '';
    public $dashboardData = [];

    public function mount()
    {
        // Definir datas padrão para filtro customizado
        $this->customStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->customEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->loadDashboardData();
    }

    public function updatedSelectedPeriod()
    {
        $this->loadDashboardData();
    }

    public function updatedCustomStartDate()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->loadDashboardData();
        }
    }

    public function updatedCustomEndDate()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->loadDashboardData();
        }
    }

    public function loadDashboardData()
    {
        $companyId = auth()->user()->company_id;
        $dateRange = $this->getAdvancedDateRange();

        $this->dashboardData = [
            'metrics' => $this->getMetrics($companyId, $dateRange),
            'workflow_metrics' => $this->getWorkflowMetrics($companyId, $dateRange),
            'top_departments' => $this->getTopDepartments($companyId, $dateRange),
            'materials_breakdown' => $this->getMaterialsBreakdown($companyId, $dateRange),
            'charts' => $this->getChartsData($companyId, $dateRange),
            'recent_orders' => $this->getRecentOrders($companyId),
            'alerts' => $this->getAlerts($companyId),
        ];
    }

    private function getAdvancedDateRange()
    {
        $now = Carbon::now();

        return match ($this->selectedPeriod) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'previous_start' => $now->copy()->subDay()->startOfDay(),
                'previous_end' => $now->copy()->subDay()->endOfDay(),
            ],
            'last_7_days' => [
                'start' => $now->copy()->subDays(7)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'previous_start' => $now->copy()->subDays(14)->startOfDay(),
                'previous_end' => $now->copy()->subDays(7)->endOfDay(),
            ],
            'last_30_days' => [
                'start' => $now->copy()->subDays(30)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'previous_start' => $now->copy()->subDays(60)->startOfDay(),
                'previous_end' => $now->copy()->subDays(30)->endOfDay(),
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek()->startOfDay(),
                'end' => $now->copy()->endOfWeek()->endOfDay(),
                'previous_start' => $now->copy()->subWeek()->startOfWeek()->startOfDay(),
                'previous_end' => $now->copy()->subWeek()->endOfWeek()->endOfDay(),
            ],
            'custom' => [
                'start' => Carbon::parse($this->customStartDate)->startOfDay(),
                'end' => Carbon::parse($this->customEndDate)->endOfDay(),
                'previous_start' => Carbon::parse($this->customStartDate)->subDays(
                    Carbon::parse($this->customEndDate)->diffInDays(Carbon::parse($this->customStartDate))
                )->startOfDay(),
                'previous_end' => Carbon::parse($this->customStartDate)->subDay()->endOfDay(),
            ],
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

    private function getWorkflowMetrics($companyId, $dateRange)
    {
        try {
            // Ordens completas (passaram do form5)
            $ordersCompleted = RepairOrder::where('company_id', $companyId)
                ->where('is_completed', true)
                ->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']])
                ->count();

            // Ordens pendentes
            $ordersPending = RepairOrder::where('company_id', $companyId)
                ->where('is_completed', false)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->count();

            // Ordens por estágio
            $ordersByStage = [];
            $forms = ['form1', 'form2', 'form3', 'form4', 'form5'];

            foreach ($forms as $form) {
                $ordersByStage[$form] = RepairOrder::where('company_id', $companyId)
                    ->where('current_form', $form)
                    ->count();
            }

            // Tempo médio de conclusão (em dias)
            $avgCompletionTime = RepairOrder::where('company_id', $companyId)
                ->where('is_completed', true)
                ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
                ->first()
                ->avg_days ?? 0;

            // Ordens criadas hoje
            $ordersCreatedToday = RepairOrder::where('company_id', $companyId)
                ->whereDate('created_at', Carbon::today())
                ->count();

            return [
                'orders_completed' => $ordersCompleted,
                'orders_pending' => $ordersPending,
                'orders_by_stage' => $ordersByStage,
                'avg_completion_time' => round($avgCompletionTime, 1),
                'orders_created_today' => $ordersCreatedToday,
                'completion_rate' => $ordersPending > 0
                    ? round(($ordersCompleted / ($ordersCompleted + $ordersPending)) * 100, 1)
                    : 100,
            ];
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar métricas de workflow: ' . $e->getMessage());

            return [
                'orders_completed' => 0,
                'orders_pending' => 0,
                'orders_by_stage' => ['form1' => 0, 'form2' => 0, 'form3' => 0, 'form4' => 0, 'form5' => 0],
                'avg_completion_time' => 0,
                'orders_created_today' => 0,
                'completion_rate' => 100,
            ];
        }
    }

    private function getTopDepartments($companyId, $dateRange)
    {
        return Department::where('company_id', $companyId)
            ->where('is_active', true)
            ->withCount(['employees as total_employees' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->map(function ($dept) use ($dateRange, $companyId) {
                // Performance média do departamento
                $avgPerformance = PerformanceEvaluation::whereHas('employee', function ($query) use ($dept) {
                    $query->where('department_id', $dept->id);
                })
                    ->where('status', 'approved')
                    ->whereBetween('evaluation_period', [$dateRange['start'], $dateRange['end']])
                    ->avg('final_percentage') ?? 0;

                // Total de horas trabalhadas no período
                $totalHours = DB::table('repair_order_form2_employees')
                    ->join('employees', 'repair_order_form2_employees.employee_id', '=', 'employees.id')
                    ->join('repair_order_form2', 'repair_order_form2_employees.form2_id', '=', 'repair_order_form2.id')
                    ->where('employees.department_id', $dept->id)
                    ->where('employees.company_id', $companyId)
                    ->whereBetween('repair_order_form2.carimbo', [$dateRange['start'], $dateRange['end']])
                    ->sum('repair_order_form2_employees.horas_trabalhadas') ?? 0;

                // Número de ordens trabalhadas
                $ordersWorked = DB::table('repair_order_form2_employees')
                    ->join('employees', 'repair_order_form2_employees.employee_id', '=', 'employees.id')
                    ->join('repair_order_form2', 'repair_order_form2_employees.form2_id', '=', 'repair_order_form2.id')
                    ->where('employees.department_id', $dept->id)
                    ->where('employees.company_id', $companyId)
                    ->whereBetween('repair_order_form2.carimbo', [$dateRange['start'], $dateRange['end']])
                    ->distinct('repair_order_form2.repair_order_id')
                    ->count() ?? 0;

                // Cálculo do score de produtividade
                $productivityScore = 0;
                if ($dept->total_employees > 0) {
                    $hoursPerEmployee = $totalHours / $dept->total_employees;
                    $ordersPerEmployee = $ordersWorked / $dept->total_employees;
                    $productivityScore = ($avgPerformance * 0.4) +
                        (min($hoursPerEmployee * 2, 100) * 0.3) +
                        (min($ordersPerEmployee * 10, 100) * 0.3);
                }

                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'total_employees' => $dept->total_employees,
                    'avg_performance' => round($avgPerformance, 1),
                    'total_hours' => round($totalHours, 1),
                    'orders_worked' => $ordersWorked,
                    'productivity_score' => round($productivityScore, 1),
                    'hours_per_employee' => $dept->total_employees > 0
                        ? round($totalHours / $dept->total_employees, 1)
                        : 0,
                ];
            })
            ->sortByDesc('productivity_score')
            ->take(3)
            ->values();
    }

    private function getMaterialsBreakdown($companyId, $dateRange)
    {
        // Materiais cadastrados utilizados
        $registeredMaterials = collect();
        $additionalMaterials = collect();

        // Verificar se as tabelas existem antes de fazer query
        try {
            $registeredMaterials = RepairOrderForm2Material::whereHas('form2.repairOrder', function ($query) use ($companyId, $dateRange) {
                $query->where('company_id', $companyId)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            })
                ->join('materials', 'repair_order_form2_materials.material_id', '=', 'materials.id')
                ->selectRaw('
                    materials.name,
                    materials.unit,
                    SUM(repair_order_form2_materials.quantidade) as total_qty,
                    SUM(repair_order_form2_materials.quantidade * materials.cost_per_unit_mzn) as total_mzn,
                    SUM(repair_order_form2_materials.quantidade * materials.cost_per_unit_usd) as total_usd,
                    COUNT(DISTINCT repair_order_form2_materials.form2_id) as orders_count
                ')
                ->groupBy('materials.id', 'materials.name', 'materials.unit')
                ->orderByDesc('total_mzn')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar materiais cadastrados: ' . $e->getMessage());
        }

        // Materiais adicionais (não cadastrados)
        try {
            $additionalMaterials = RepairOrderForm2AdditionalMaterial::whereHas('form2.repairOrder', function ($query) use ($companyId, $dateRange) {
                $query->where('company_id', $companyId)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            })
                ->selectRaw('
                    nome_material,
                    SUM(quantidade) as total_qty,
                    AVG(custo_unitario) as avg_unit_cost,
                    SUM(custo_total) as total_cost,
                    COUNT(DISTINCT form2_id) as orders_count
                ')
                ->groupBy('nome_material')
                ->orderByDesc('total_cost')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar materiais adicionais: ' . $e->getMessage());
        }

        // Totais
        $registeredTotal = $registeredMaterials->sum('total_mzn');
        $additionalTotal = $additionalMaterials->sum('total_cost');
        $grandTotal = $registeredTotal + $additionalTotal;

        return [
            'registered' => $registeredMaterials,
            'additional' => $additionalMaterials,
            'totals' => [
                'registered_mzn' => $registeredTotal,
                'additional_mzn' => $additionalTotal,
                'grand_total_mzn' => $grandTotal,
                'registered_percentage' => $grandTotal > 0 ? round(($registeredTotal / $grandTotal) * 100, 1) : 0,
                'additional_percentage' => $grandTotal > 0 ? round(($additionalTotal / $grandTotal) * 100, 1) : 0,
            ],
            'summary' => [
                'total_materials_types' => $registeredMaterials->count() + $additionalMaterials->count(),
                'most_used_registered' => $registeredMaterials->first()?->name ?? 'N/A',
                'most_expensive_additional' => $additionalMaterials->first()?->nome_material ?? 'N/A',
            ]
        ];
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
                SUM(total_mzn) as total_mzn,
                SUM(total_usd) as total_usd
            ')
            ->first();

        $totalCount = $data->count ?: 1;

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
                'billing_mzn' => round($billing / 1000, 0),
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
            ->map(function ($client) {
                return [
                    'name' => $client->name,
                    'orders' => $client->orders,
                    'billing' => $client->billing,
                    'growth' => rand(-10, 30),
                ];
            });

        return [
            'monthly_orders' => $monthlyData,
            'top_clients' => $topClients,
        ];
    }

    private function getRecentOrders($companyId)
    {
        return RepairOrder::where('repair_orders.company_id', $companyId)
            ->with(['form1.client', 'form1.status', 'form2.employees'])
            ->orderBy('repair_orders.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_number ?? 'ORD-' . $order->id,
                    'client' => $order->form1->client->name ?? 'N/A',
                    'status' => $order->form1->status->name ?? 'N/A',
                    'days_ago' => $order->created_at->diffInDays(now()),
                    'technician' => $order->form2?->employees?->first()?->name ?? 'N/A',
                    'priority' => rand(0, 2) == 0 ? 'high' : (rand(0, 1) == 0 ? 'medium' : 'low'),
                ];
            });
    }

    private function getAlerts($companyId)
    {
        $alerts = [];

        // Ordens com prazo vencendo
        $ordersOverdue = RepairOrder::where('company_id', $companyId)
            ->whereHas('form3', function ($query) {
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
            ->whereHas('form5')
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
        // $this->dispatch('show-notification', [
        //     'type' => 'info',
        //     'message' => 'Funcionalidade de exportação será implementada em breve.'
        // ]);
        try {
            // Verificar se PDFShift está habilitado
            if (env('PDFSHIFT_ENABLED', false) && env('PDFSHIFT_API_KEY')) {
                return $this->exportViaPDFShift();
            } else {
                return $this->exportViaMPDF();
            }
        } catch (\Exception $e) {
            \Log::error('Erro na exportação PDF: ' . $e->getMessage());

            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Erro ao gerar PDF. Tente novamente em alguns minutos.'
            ]);
        }
    }

    private function getPeriodLabel()
    {
        return match ($this->selectedPeriod) {
            'today' => 'Hoje',
            'last_7_days' => 'Últimos 7 dias',
            'last_30_days' => 'Últimos 30 dias',
            'current_month' => 'Mês Atual',
            'custom' => "De {$this->customStartDate} até {$this->customEndDate}",
            default => 'Período Atual'
        };
    }
    private function exportViaPDFShift()
    {
        $client = new \GuzzleHttp\Client();

        // ✅ GARANTIR QUE DADOS ESTÃO CARREGADOS
        if (empty($this->dashboardData)) {
            $this->loadDashboardData();
        }

        // ✅ PREPARAR DADOS CORRETOS PARA O PDF
        $pdfData = [
            'company' => [
                'name' => auth()->user()->company->name ?? 'Empresa',
                'period' => $this->getPeriodLabel(),
                'generated_at' => now()->format('d/m/Y H:i')
            ],
            // ⚡ PASSAR OS DADOS EXATAMENTE COMO A DASHBOARD USA
            'dashboardData' => $this->dashboardData
        ];

        // Renderizar HTML otimizado para PDF
        $html = view('exports.dashboard-pdfshift', $pdfData)->render();


        try {
            $response = $client->post('https://api.pdfshift.io/v3/convert/pdf', [
                'headers' => [
                    'X-API-Key' => env('PDFSHIFT_API_KEY'),
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'source' => $html,
                    // 'landscape' => false,
                    // 'use_print' => false,
                    'format' => 'A4',
                    // 'margin' => '20mm',
                    // 'wait_for' => 3000,
                    // 'javascript' => true 
                ],
                'timeout' => 30
            ]);
            $pdfContent = $response->getBody()->getContents();

            $filename = 'dashboard-' . now()->format('Y-m-d-H-i') . '.pdf';

            return response()->streamDownload(function () use ($pdfContent) {
                echo $pdfContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Length' => strlen($pdfContent),
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('PDFShift API Error: ' . $e->getMessage());

            // Se API falhar, usar fallback
            // return $this->exportViaLocalPDF();
        }
    }

    #[Layout('layouts.company')]
    #[Title('Dashboard')]

    public function render()
    {
        return view('livewire.company.dashboard');
    }
}
