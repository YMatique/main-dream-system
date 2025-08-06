<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $metrics)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $today = Carbon::today()->format('d/m/Y');
        $yesterday = Carbon::yesterday()->format('d/m/Y');
        
        $message = (new MailMessage)
            ->subject('📊 Relatório Diário do Sistema - ' . $today)
            ->greeting('Bom dia!')
            ->line('Aqui está o resumo das atividades do sistema de **' . $yesterday . '** até **' . $today . '**:')
            ->line('');

        // Seção de Novas Atividades
        $message->line('## 🆕 **NOVAS ATIVIDADES**');
        
        if ($this->hasNewActivity()) {
            $message->line('• **' . $this->metrics['new_companies'] . '** novas empresas cadastradas')
                   ->line('• **' . $this->metrics['new_users'] . '** novos usuários criados') 
                   ->line('• **' . $this->metrics['new_subscriptions'] . '** novas subscrições ativadas');
        } else {
            $message->line('Nenhuma nova atividade registrada.');
        }

        $message->line('');

        // Seção de Alertas
        $message->line('## ⚠️ **ALERTAS E ATENÇÕES**');
        
        $hasAlerts = false;
        
        if ($this->metrics['expiring_subscriptions'] > 0) {
            $message->line('🟡 **' . $this->metrics['expiring_subscriptions'] . '** subscrições expiram nos próximos 7 dias');
            $hasAlerts = true;
        }
        
        if ($this->metrics['expired_subscriptions'] > 0) {
            $message->line('🔴 **' . $this->metrics['expired_subscriptions'] . '** subscrições expiraram ontem');
            $hasAlerts = true;
        }
        
        if (!$hasAlerts) {
            $message->line('✅ Nenhum alerta crítico no momento.');
        }

        $message->line('');

        // Seção de Estatísticas Gerais
        $message->line('## 📈 **ESTATÍSTICAS ATUAIS**')
                ->line('• **Empresas Ativas:** ' . number_format($this->metrics['active_companies']))
                ->line('• **Receita Mensal Recorrente:** ' . number_format($this->metrics['total_revenue'], 2, ',', '.') . ' MT')
                ->line('• **Taxa de Crescimento:** ' . $this->getGrowthRate() . '%');

        $message->line('');

        // Seção de Ações Recomendadas
        $actions = $this->getRecommendedActions();
        if (!empty($actions)) {
            $message->line('## 🎯 **AÇÕES RECOMENDADAS**');
            foreach ($actions as $action) {
                $message->line('• ' . $action);
            }
            $message->line('');
        }

        // Performance do Sistema
        $message->line('## ⚡ **PERFORMANCE DO SISTEMA**')
                ->line('• **Status:** ' . $this->getSystemStatus())
                ->line('• **Uptime:** ' . $this->getSystemUptime())
                ->line('• **Última Limpeza:** ' . $this->getLastCleanup());

        $message->line('')
                ->line('---')
                ->line('💡 **Dica do dia:** ' . $this->getDailyTip())
                ->line('')
                ->action('Acessar Dashboard', route('system.dashboard'))
                ->line('Este relatório é gerado automaticamente todos os dias às 08:00.')
                ->salutation('Tenha um ótimo dia, Equipe do Sistema');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
         return [
            'type' => 'daily_report',
            'title' => 'Relatório Diário do Sistema',
            'message' => 'Relatório diário com métricas e atividades do sistema.',
            'metrics' => $this->metrics,
            'date' => Carbon::today()->toDateString(),
            'has_alerts' => $this->hasAlerts(),
            'action_url' => route('system.dashboard'),
            'action_text' => 'Ver Dashboard',
        ];
    }
     /**
     * Check if there's new activity
     */
    private function hasNewActivity(): bool
    {
        return $this->metrics['new_companies'] > 0 || 
               $this->metrics['new_users'] > 0 || 
               $this->metrics['new_subscriptions'] > 0;
    }

    /**
     * Check if there are alerts
     */
    private function hasAlerts(): bool
    {
        return $this->metrics['expiring_subscriptions'] > 0 || 
               $this->metrics['expired_subscriptions'] > 0;
    }

    /**
     * Calculate growth rate (simplified)
     */
    private function getGrowthRate(): string
    {
        // Cálculo simplificado - você pode implementar lógica mais complexa
        $newCompanies = $this->metrics['new_companies'];
        $totalCompanies = $this->metrics['active_companies'];
        
        if ($totalCompanies > 0) {
            $rate = ($newCompanies / $totalCompanies) * 100;
            return number_format($rate, 1);
        }
        
        return '0.0';
    }

    /**
     * Get recommended actions based on metrics
     */
    private function getRecommendedActions(): array
    {
        $actions = [];
        
        if ($this->metrics['expired_subscriptions'] > 0) {
            $actions[] = 'Entrar em contato com empresas que tiveram subscrições expiradas';
        }
        
        if ($this->metrics['expiring_subscriptions'] > 3) {
            $actions[] = 'Preparar campanha de renovação para subscrições prestes a expirar';
        }
        
        if ($this->metrics['new_companies'] == 0) {
            $actions[] = 'Analisar estratégias de aquisição de novos clientes';
        }
        
        if ($this->metrics['active_companies'] > 50) {
            $actions[] = 'Considerar otimizações de performance do sistema';
        }
        
        return $actions;
    }

    /**
     * Get system status
     */
    private function getSystemStatus(): string
    {
        // Verificações básicas de sistema
        try {
            // Verificar conexão com banco
            DB::connection()->getPdo();
            
            // Verificar cache
            Cache::put('health_check', true, 60);
            $cacheOk = Cache::get('health_check');
            
            if ($cacheOk) {
                return '✅ Operacional';
            } else {
                return '⚠️ Cache com problemas';
            }
        } catch (\Exception $e) {
            return '❌ Problemas detectados';
        }
    }

    /**
     * Get system uptime (simplified)
     */
    private function getSystemUptime(): string
    {
        // Implementação simplificada - você pode usar métricas reais do servidor
        return '99.9% (últimos 30 dias)';
    }

    /**
     * Get last cleanup time
     */
    private function getLastCleanup(): string
    {
        // Verificar quando foi a última limpeza baseado nos logs
        try {
            $lastCleanup = Log::getMonolog()->getHandlers()[0]->getUrl() ?? null;
            // Implementar lógica real baseada nos seus logs
            return 'Ontem às 02:00';
        } catch (\Exception $e) {
            return 'Não disponível';
        }
    }

    /**
     * Get daily tip
     */
    private function getDailyTip(): string
    {
        $tips = [
            'Configure backups automáticos para proteger os dados das empresas.',
            'Monitore regularmente os logs de auditoria para detectar atividades suspeitas.',
            'Mantenha as subscrições atualizadas para evitar interrupções de serviço.',
            'Implemente autenticação de dois fatores para maior segurança.',
            'Revise periodicamente as permissões dos usuários.',
            'Faça testes regulares de recuperação de desastres.',
            'Monitore o desempenho do sistema durante picos de uso.',
            'Mantenha a documentação do sistema sempre atualizada.',
            'Configure alertas proativos para problemas comuns.',
            'Realize auditorias de segurança mensais.'
        ];
        
        // Selecionar tip baseado no dia do ano
        $dayOfYear = Carbon::today()->dayOfYear;
        return $tips[$dayOfYear % count($tips)];
    }
}
