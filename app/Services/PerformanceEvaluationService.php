<?php
// app/Services/PerformanceEvaluationService.php

namespace App\Services;

use App\Models\Company\EvaluationApprovalStage;
use App\Models\Company\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowPerformanceNotification;
use App\Mail\EvaluationApprovalRequest;
use App\Models\Company\Evaluation\EvaluationResponse;
use App\Models\Company\Evaluation\PerformanceEvaluation;
use App\Models\Company\Evaluation\PerformanceMetric;

class PerformanceEvaluationService
{
    /**
     * Criar uma nova avaliação para um funcionário
     */
    public function createEvaluation($employeeId, $evaluatorId, $period = null)
    {
        $employee = Employee::findOrFail($employeeId);
        $evaluator = User::findOrFail($evaluatorId);
        
        // Verificar se o avaliador tem permissão para avaliar este departamento
        if (!$this->canEvaluateEmployee($evaluator, $employee)) {
            throw new \Exception('Sem permissão para avaliar funcionários deste departamento');
        }

        $evaluationPeriod = $period ? Carbon::parse($period) : now()->startOfMonth();

        // Verificar se já existe avaliação para este período
        $existingEvaluation = PerformanceEvaluation::where('company_id', $employee->company_id)
            ->where('employee_id', $employeeId)
            ->where('evaluation_period', $evaluationPeriod)
            ->first();

        if ($existingEvaluation) {
            throw new \Exception('Já existe uma avaliação para este funcionário neste período');
        }

        return PerformanceEvaluation::create([
            'company_id' => $employee->company_id,
            'employee_id' => $employeeId,
            'evaluator_id' => $evaluatorId,
            'evaluation_period' => $evaluationPeriod,
            'status' => 'draft',
            'recommendations' => ''
        ]);
    }

    /**
     * Verificar se um usuário pode avaliar um funcionário
     */
    public function canEvaluateEmployee(User $evaluator, Employee $employee)
    {
        // // Admin master pode avaliar qualquer um da empresa
        // if ($evaluator->user_type === 'company_admin' && $evaluator->company_id === $employee->company_id) {
        //     return true;
        // }

        // // Verificar se tem permissão específica para o departamento
        // return $evaluator->hasPermission("evaluation.department.{$employee->department_id}");
        // Admin master pode avaliar qualquer um da empresa
        if ($evaluator->user_type === 'company_admin' && $evaluator->company_id === $employee->company_id) {
            return true;
        }

        // ✅ CORREÇÃO: Verificar permissão geral + departamentos atribuídos
        if (!$evaluator->hasPermission('evaluation.create')) {
            return false;
        }

        // Verificar se tem acesso ao departamento do funcionário via DepartmentEvaluator
        $hasAccessToDepartment = \App\Models\DepartmentEvaluator::where('user_id', $evaluator->id)
            ->where('company_id', $evaluator->company_id)
            ->where('department_id', $employee->department_id)
            ->where('is_active', true)
            ->exists();

        return $hasAccessToDepartment && $employee->company_id === $evaluator->company_id;
    }

    /**
     * Salvar respostas da avaliação
     */
    public function saveEvaluationResponses($evaluationId, array $responses)
    {
        $evaluation = PerformanceEvaluation::findOrFail($evaluationId);

        if (!$evaluation->canBeEdited()) {
            throw new \Exception('Esta avaliação não pode mais ser editada');
        }

        DB::transaction(function () use ($evaluation, $responses) {
            // Limpar respostas existentes
            $evaluation->responses()->delete();

            $totalWeightUsed = 0;

            foreach ($responses as $metricId => $response) {
                $metric = PerformanceMetric::findOrFail($metricId);
                $totalWeightUsed += $metric->weight;

                $evaluationResponse = EvaluationResponse::create([
                    'evaluation_id' => $evaluation->id,
                    'metric_id' => $metricId,
                    'numeric_value' => $response['numeric_value'] ?? null,
                    'rating_value' => $response['rating_value'] ?? null,
                    'comments' => $response['comments'] ?? null
                ]);

                // Calcular score automaticamente
                $evaluationResponse->calculateScore();
            }

            // Verificar se o peso total é 100%
            if ($totalWeightUsed !== 100) {
                throw new \Exception("O peso total das métricas deve ser 100%. Atual: {$totalWeightUsed}%");
            }

            // Recalcular score final
            $evaluation->calculateFinalScore();
        });

        return $evaluation->fresh();
    }

    /**
     * Submeter avaliação para aprovação
     */
    public function submitEvaluation($evaluationId, $recommendations)
    {
        $evaluation = PerformanceEvaluation::findOrFail($evaluationId);

        if (!$evaluation->canBeSubmitted()) {
            throw new \Exception('Esta avaliação não pode ser submetida');
        }

        $evaluation->update(['recommendations' => $recommendations]);
        $evaluation->submit();

        // Enviar notificações para aprovadores do primeiro estágio
        $this->sendApprovalNotifications($evaluation);

        return $evaluation->fresh();
    }

    /**
     * Aprovar uma avaliação
     */
    public function approveEvaluation($evaluationId, $approverId, $comments = null)
    {
        $evaluation = PerformanceEvaluation::findOrFail($evaluationId);

        if (!$evaluation->canBeApproved($approverId)) {
            throw new \Exception('Você não tem permissão para aprovar esta avaliação');
        }

        $evaluation->approve($approverId, $comments);

        // Se foi a aprovação final, enviar notificações
        if ($evaluation->fresh()->status === 'approved') {
            $this->sendFinalApprovalNotifications($evaluation);
        }

        return $evaluation->fresh();
    }

    /**
     * Rejeitar uma avaliação
     */
    public function rejectEvaluation($evaluationId, $approverId, $comments)
    {
        $evaluation = PerformanceEvaluation::findOrFail($evaluationId);

        if (!$evaluation->canBeApproved($approverId)) {
            throw new \Exception('Você não tem permissão para rejeitar esta avaliação');
        }

        $evaluation->reject($approverId, $comments);

        // Enviar notificações de rejeição
        $this->sendRejectionNotifications($evaluation, $comments);

        return $evaluation->fresh();
    }

    /**
     * Obter métricas de um departamento
     */
    public function getDepartmentMetrics($departmentId, $companyId)
    {
        return PerformanceMetric::where('company_id', $companyId)
            ->where('department_id', $departmentId)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Validar se as métricas de um departamento estão configuradas corretamente
     */
    public function validateDepartmentMetrics($departmentId, $companyId)
    {
        $metrics = $this->getDepartmentMetrics($departmentId, $companyId);
        
        if ($metrics->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'Nenhuma métrica configurada para este departamento'
            ];
        }

        $totalWeight = $metrics->sum('weight');
        
        if ($totalWeight !== 100) {
            return [
                'valid' => false,
                'message' => "O peso total das métricas deve ser 100%. Atual: {$totalWeight}%"
            ];
        }

        return ['valid' => true];
    }

    /**
     * Obter relatório de desempenho de um funcionário
     */
    public function getEmployeePerformanceReport($employeeId, $year = null)
    {
        $year = $year ?? now()->year;

        $evaluations = PerformanceEvaluation::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->whereYear('evaluation_period', $year)
            ->orderBy('evaluation_period')
            ->get();

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $evaluation = $evaluations->where('evaluation_period', Carbon::create($year, $month, 1))->first();
            
            $monthlyData[] = [
                'month' => $month,
                'month_name' => Carbon::create($year, $month, 1)->format('F'),
                'evaluation' => $evaluation,
                'percentage' => $evaluation?->final_percentage ?? null,
                'performance_class' => $evaluation?->performance_class ?? 'Não Avaliado'
            ];
        }

        $averagePerformance = $evaluations->avg('final_percentage') ?? 0;
        $totalEvaluations = $evaluations->count();
        $belowThresholdCount = $evaluations->where('is_below_threshold', true)->count();

        return [
            'employee_id' => $employeeId,
            'year' => $year,
            'monthly_data' => $monthlyData,
            'average_performance' => round($averagePerformance, 2),
            'total_evaluations' => $totalEvaluations,
            'below_threshold_count' => $belowThresholdCount,
            'performance_trend' => $this->calculatePerformanceTrend($evaluations)
        ];
    }

    /**
     * Calcular tendência de desempenho
     */
    protected function calculatePerformanceTrend($evaluations)
    {
        if ($evaluations->count() < 2) {
            return 'stable';
        }

        $sorted = $evaluations->sortBy('evaluation_period');
        $first = $sorted->first()->final_percentage;
        $last = $sorted->last()->final_percentage;
        $difference = $last - $first;

        if ($difference > 10) {
            return 'improving';
        } elseif ($difference < -10) {
            return 'declining';
        }

        return 'stable';
    }

    /**
     * Obter dashboard de avaliações para um gestor
     */
    public function getEvaluationDashboard($userId, $companyId)
    {
        $user = User::findOrFail($userId);
        
        // Determinar departamentos que o usuário pode ver
        $departmentIds = $this->getAccessibleDepartments($user);

        $pendingEvaluations = PerformanceEvaluation::where('company_id', $companyId)
            ->whereIn('employee_id', function($query) use ($departmentIds) {
                $query->select('id')
                      ->from('employees')
                      ->whereIn('department_id', $departmentIds);
            })
            ->pendingApproval()
            ->count();

        $thisMonthEvaluations = PerformanceEvaluation::where('company_id', $companyId)
            ->whereIn('employee_id', function($query) use ($departmentIds) {
                $query->select('id')
                      ->from('employees')
                      ->whereIn('department_id', $departmentIds);
            })
            ->whereMonth('evaluation_period', now()->month)
            ->whereYear('evaluation_period', now()->year)
            ->count();

        $belowThresholdCount = PerformanceEvaluation::where('company_id', $companyId)
            ->whereIn('employee_id', function($query) use ($departmentIds) {
                $query->select('id')
                      ->from('employees')
                      ->whereIn('department_id', $departmentIds);
            })
            ->belowThreshold()
            ->whereMonth('evaluation_period', now()->month)
            ->whereYear('evaluation_period', now()->year)
            ->count();

        $averagePerformance = PerformanceEvaluation::where('company_id', $companyId)
            ->whereIn('employee_id', function($query) use ($departmentIds) {
                $query->select('id')
                      ->from('employees')
                      ->whereIn('department_id', $departmentIds);
            })
            ->where('status', 'approved')
            ->whereMonth('evaluation_period', now()->month)
            ->whereYear('evaluation_period', now()->year)
            ->avg('final_percentage') ?? 0;

        return [
            'pending_evaluations' => $pendingEvaluations,
            'this_month_evaluations' => $thisMonthEvaluations,
            'below_threshold_count' => $belowThresholdCount,
            'average_performance' => round($averagePerformance, 2)
        ];
    }

    /**
     * Obter departamentos acessíveis para um usuário
     */
    protected function getAccessibleDepartments(User $user)
    {
        /*
        if ($user->user_type === 'company_admin') {
            // Admin pode ver todos os departamentos da empresa
            return \App\Models\Company\Department::where('company_id', $user->company_id)
                ->pluck('id')
                ->toArray();
        }

        // Para usuários normais, verificar permissões de departamento
        $permissions = $user->getAllPermissions()
            ->filter(function($permission) {
                return str_starts_with($permission->name, 'evaluation.department.');
            });

        $departmentIds = [];
        foreach ($permissions as $permission) {
            $departmentId = str_replace('evaluation.department.', '', $permission->name);
            if (is_numeric($departmentId)) {
                $departmentIds[] = (int) $departmentId;
            }
        }

        return $departmentIds;
        */

         if ($user->user_type === 'company_admin') {
            // Admin pode ver todos os departamentos da empresa
            return \App\Models\Company\Department::where('company_id', $user->company_id)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
        }

        // ✅ CORREÇÃO: Usar tabela department_evaluators ao invés de permissões específicas
        if (!$user->hasPermission('evaluation.create')) {
            return [];
        }

        return \App\Models\DepartmentEvaluator::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->where('is_active', true)
            ->pluck('department_id')
            ->toArray();
    }

    /**
     * Enviar notificações de aprovação
     */
    protected function sendApprovalNotifications(PerformanceEvaluation $evaluation)
    {
        $approvers = $evaluation->approvals()
            ->where('stage_number', 1)
            ->where('status', 'pending')
            ->with('approver')
            ->get();

        foreach ($approvers as $approval) {
            try {
                Mail::to($approval->approver->email)
                    ->send(new EvaluationApprovalRequest($evaluation, $approval));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar notificação de aprovação', [
                    'evaluation_id' => $evaluation->id,
                    'approver_id' => $approval->approver_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Enviar notificações de baixo desempenho
     */
    protected function sendLowPerformanceNotifications(PerformanceEvaluation $evaluation)
    {
        if (!$evaluation->is_below_threshold || $evaluation->notifications_sent) {
            return;
        }

        $recipients = $this->getLowPerformanceNotificationRecipients($evaluation);

        foreach ($recipients as $email) {
            try {
                Mail::to($email)
                    ->send(new LowPerformanceNotification($evaluation));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar notificação de baixo desempenho', [
                    'evaluation_id' => $evaluation->id,
                    'recipient' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $evaluation->update(['notifications_sent' => true]);
    }

    /**
     * Obter destinatários para notificações de baixo desempenho
     */
    protected function getLowPerformanceNotificationRecipients(PerformanceEvaluation $evaluation)
    {
        $recipients = [];

        // Email da empresa
        if ($evaluation->company->notification_email) {
            $recipients[] = $evaluation->company->notification_email;
        }

        // Gestor do departamento (se configurado)
        $department = $evaluation->employee->department;
        if ($department && $department->manager_email) {
            $recipients[] = $department->manager_email;
        }

        // Email do funcionário (se tiver acesso ao portal)
        $portalAccess = $evaluation->employee->portalAccess;
        if ($portalAccess && $portalAccess->is_active) {
            $recipients[] = $portalAccess->email;
        }

        return array_unique($recipients);
    }

    /**
     * Enviar notificações de aprovação final
     */
    protected function sendFinalApprovalNotifications(PerformanceEvaluation $evaluation)
    {
        // Implementar notificações de aprovação final
    }

    /**
     * Enviar notificações de rejeição
     */
    protected function sendRejectionNotifications(PerformanceEvaluation $evaluation, $comments)
    {
        // Implementar notificações de rejeição
    }
}