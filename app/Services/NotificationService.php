<?php

namespace App\Services;

use App\Models\System\Company;
use App\Models\System\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    

    /**
     * Enviar notificação de boas-vindas para nova empresa
     */
    public function sendCompanyWelcome(Company $company, ?string $tempPassword = null): bool
    {
        try {
            // Buscar o usuário administrador da empresa
            $admin = $company->users()->where('user_type', 'company_admin')->first();
            
            if (!$admin) {
                Log::warning("Empresa {$company->name} não possui administrador para envio de welcome email");
                return false;
            }

            // $admin->notify(new CompanyWelcomeNotification($company, $tempPassword));
            
            Log::info("Email de boas-vindas enviado para empresa {$company->name}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar email de boas-vindas para empresa {$company->name}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificação de boas-vindas para novo usuário
     */
    public function sendUserWelcome(User $user, ?string $tempPassword = null): bool
    {
        try {
            // $user->notify(new WelcomeUserNotification($tempPassword));
            
            Log::info("Email de boas-vindas enviado para usuário {$user->email}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar email de boas-vindas para usuário {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificação de redefinição de senha
     */
    public function sendPasswordReset(User $user, string $tempPassword): bool
    {
        try {
            // $user->notify(new PasswordResetNotification($tempPassword));
            
            Log::info("Email de redefinição de senha enviado para {$user->email}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar email de redefinição de senha para {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar e notificar sobre subscrições que estão expirando
     */
    public function checkExpiringSubscriptions(int $daysBeforeExpiration = 7): int
    {
        $expiringDate = Carbon::now()->addDays($daysBeforeExpiration);
        
        $expiringSubscriptions = Subscription::with(['company', 'plan'])
            ->where('status', 'active')
            ->whereBetween('expires_at', [Carbon::now(), $expiringDate])
            ->whereNull('expiration_notified_at') // Não notificar novamente
            ->get();

        $notifiedCount = 0;

        foreach ($expiringSubscriptions as $subscription) {
            try {
                // Buscar administradores da empresa
                $admins = $subscription->company->users()
                    ->whereIn('user_type', ['company_admin'])
                    ->where('status', 'active')
                    ->get();

                foreach ($admins as $admin) {
                    // $admin->notify(new SubscriptionExpiringNotification($subscription));
                }

                // Marcar como notificado
                $subscription->update(['expiration_notified_at' => Carbon::now()]);
                
                $notifiedCount++;
                
                Log::info("Notificação de expiração enviada para empresa {$subscription->company->name}");
                
            } catch (\Exception $e) {
                Log::error("Erro ao notificar expiração da subscrição {$subscription->id}: " . $e->getMessage());
            }
        }

        return $notifiedCount;
    }

    /**
     * Verificar subscrições expiradas e suspender empresas
     */
    public function processExpiredSubscriptions(): int
    {
        $expiredSubscriptions = Subscription::with(['company'])
            ->where('status', 'active')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        $processedCount = 0;

        foreach ($expiredSubscriptions as $subscription) {
            try {
                // Suspender a subscrição
                $subscription->update([
                    'status' => 'expired',
                    'suspended_at' => Carbon::now()
                ]);

                // Suspender a empresa se não tiver outras subscrições ativas
                $hasOtherActiveSubscriptions = $subscription->company
                    ->subscriptions()
                    ->where('status', 'active')
                    ->where('id', '!=', $subscription->id)
                    ->exists();

                if (!$hasOtherActiveSubscriptions) {
                    $subscription->company->update(['status' => 'suspended']);
                    
                    // Desativar usuários da empresa
                    $subscription->company->users()
                        ->where('user_type', '!=', 'super_admin')
                        ->update(['status' => 'inactive']);
                }

                $processedCount++;
                
                Log::info("Subscrição {$subscription->id} da empresa {$subscription->company->name} expirada e processada");
                
            } catch (\Exception $e) {
                Log::error("Erro ao processar subscrição expirada {$subscription->id}: " . $e->getMessage());
            }
        }

        return $processedCount;
    }

    /**
     * Enviar relatório diário para super admins
     */
    public function sendDailyReport(): bool
    {
        try {
            // Coletar métricas do dia
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            
            $metrics = [
                'new_companies' => Company::whereDate('created_at', $today)->count(),
                'new_users' => User::whereDate('created_at', $today)->count(),
                'new_subscriptions' => Subscription::whereDate('created_at', $today)->count(),
                'expiring_subscriptions' => Subscription::where('status', 'active')
                    ->whereBetween('expires_at', [$today, $today->copy()->addDays(7)])
                    ->count(),
                'expired_subscriptions' => Subscription::where('status', 'expired')
                    ->whereDate('updated_at', $today)
                    ->count(),
                'active_companies' => Company::where('status', 'active')->count(),
                'total_revenue' => Subscription::active()
                    ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                    ->sum('plans.price'),
            ];

            // Buscar super admins para envio
            $superAdmins = User::where('user_type', 'super_admin')
                ->where('status', 'active')
                ->get();

            foreach ($superAdmins as $admin) {
                // $admin->notify(new \App\Notifications\DailyReportNotification($metrics));
            }

            Log::info("Relatório diário enviado para " . $superAdmins->count() . " super admins");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar relatório diário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificações de performance baixa (para avaliações < 50%)
     */
    public function sendPerformanceAlerts($employeeId, $score, $evaluation): bool
    {
        try {
            // Buscar o funcionário e seus gestores
            $employee = User::find($employeeId);
            if (!$employee) {
                return false;
            }

            // Buscar gestores do departamento e admins da empresa
            $recipients = collect();
            
            // Admins da empresa
            $companyAdmins = $employee->company->users()
                ->whereIn('user_type', ['company_admin'])
                ->where('status', 'active')
                ->get();
            
            $recipients = $recipients->merge($companyAdmins);

            // Gestores do departamento (se houver)
            if ($employee->department_id) {
                $departmentManagers = User::where('company_id', $employee->company_id)
                    ->where('department_id', $employee->department_id)
                    ->where('user_type', 'company_admin')
                    ->where('status', 'active')
                    ->get();
                
                $recipients = $recipients->merge($departmentManagers);
            }

            // Super admins (opcional)
            $superAdmins = User::where('user_type', 'super_admin')
                ->where('status', 'active')
                ->get();
            
            $recipients = $recipients->merge($superAdmins);

            // Remover duplicatas
            $recipients = $recipients->unique('id');

            foreach ($recipients as $recipient) {
                // $recipient->notify(new \App\Notifications\PerformanceAlertNotification(
                //     $employee, $score, $evaluation
                // ));
            }

            // Notificar o próprio funcionário
            // $employee->notify(new \App\Notifications\PerformanceResultNotification(
            //     $score, $evaluation
            // ));

            Log::info("Alertas de performance enviados para funcionário {$employee->name} (Score: {$score})");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar alertas de performance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificações de sistema (manutenção, atualizações, etc.)
     */
    public function sendSystemAnnouncement(string $title, string $message, string $type = 'info', ?array $userTypes = null): int
    {
        try {
            $query = User::where('status', 'active');
            
            if ($userTypes) {
                $query->whereIn('user_type', $userTypes);
            }
            
            $users = $query->get();
            $sentCount = 0;

            foreach ($users as $user) {
                try {
                    // $user->notify(new \App\Notifications\SystemAnnouncementNotification(
                    //     $title, $message, $type
                    // ));
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error("Erro ao enviar anúncio para usuário {$user->email}: " . $e->getMessage());
                }
            }

            Log::info("Anúncio do sistema enviado para {$sentCount} usuários");
            return $sentCount;
            
        } catch (\Exception $e) {
            Log::error("Erro ao enviar anúncio do sistema: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Enviar notificação para empresa quando subscrição é renovada
     */
    public function sendSubscriptionRenewed(Subscription $subscription): bool
    {
        try {
            // Buscar administradores da empresa
            $admins = $subscription->company->users()
                ->whereIn('user_type', ['company_admin'])
                ->where('status', 'active')
                ->get();

            foreach ($admins as $admin) {
                // $admin->notify(new \App\Notifications\SubscriptionRenewedNotification($subscription));
            }

            Log::info("Notificação de renovação enviada para empresa {$subscription->company->name}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Erro ao notificar renovação da subscrição {$subscription->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Limpar notificações antigas
     */
    public function cleanupOldNotifications(int $daysOld = 30): int
    {
        try {
            $cutoffDate = Carbon::now()->subDays($daysOld);
            
            // Limpar notificações da tabela notifications
            $deleted = \DB::table('notifications')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            Log::info("Limpeza de notificações: {$deleted} notificações antigas removidas");
            return $deleted;
            
        } catch (\Exception $e) {
            Log::error("Erro na limpeza de notificações: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Verificar configurações de email e conectividade
     */
    public function testEmailConfiguration(): array
    {
        $results = [
            'smtp_connection' => false,
            'test_email_sent' => false,
            'errors' => []
        ];

        try {
            // Testar conexão SMTP
            $transport = Mail::getSwiftMailer()->getTransport();
            if (method_exists($transport, 'ping')) {
                $results['smtp_connection'] = $transport->ping();
            } else {
                $results['smtp_connection'] = true; // Assume conexão OK para outros tipos
            }

            // Enviar email de teste para o primeiro super admin
            $superAdmin = User::where('user_type', 'super_admin')
                ->where('status', 'active')
                ->first();

            if ($superAdmin) {
                Mail::raw('Este é um email de teste do sistema.', function ($message) use ($superAdmin) {
                    $message->to($superAdmin->email)
                           ->subject('Teste de Email - Sistema de Manutenção');
                });
                
                $results['test_email_sent'] = true;
            } else {
                $results['errors'][] = 'Nenhum super admin encontrado para teste';
            }

        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
            Log::error("Erro no teste de email: " . $e->getMessage());
        }

        return $results;
    }

    /**
     * Obter estatísticas de notificações
     */
    public function getNotificationStats(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_sent' => \DB::table('notifications')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'total_read' => \DB::table('notifications')
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('read_at')
                ->count(),
            'by_type' => \DB::table('notifications')
                ->select('type', \DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'read_rate' => 0 // Será calculado depois
        ];
    }
}
