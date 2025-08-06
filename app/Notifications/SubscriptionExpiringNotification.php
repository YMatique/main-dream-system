<?php

namespace App\Notifications;

use App\Models\System\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
      public function __construct(
        public Subscription $subscription
    ) {}


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $companyName = $this->subscription->company->name;
        $planName = $this->subscription->plan->name;
        $expiresAt = Carbon::parse($this->subscription->expires_at);
        $daysLeft = now()->diffInDays($expiresAt, false);
        
        // Determinar urgência baseada nos dias restantes
        $urgency = $this->getUrgencyLevel($daysLeft);
        $subject = $this->getSubjectByUrgency($urgency, $companyName);
        
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Atenção!')
            ->line($this->getOpeningMessage($urgency, $daysLeft));

        // Informações da subscrição
        $message->line('**Detalhes da Subscrição:**')
                ->line('• Empresa: ' . $companyName)
                ->line('• Plano: ' . $planName)
                ->line('• Data de Expiração: ' . $expiresAt->format('d/m/Y H:i'))
                ->line('• Tempo Restante: ' . $this->getTimeRemaining($daysLeft));

        // Ações baseadas na urgência
        if ($daysLeft <= 0) {
            $message->line('')
                    ->line('🔴 **SUBSCRIÇÃO EXPIRADA**')
                    ->line('Sua subscrição expirou e os serviços podem ser interrompidos a qualquer momento.')
                    ->line('Entre em contato conosco imediatamente para renovar.');
        } elseif ($daysLeft <= 3) {
            $message->line('')
                    ->line('🟡 **AÇÃO URGENTE NECESSÁRIA**')
                    ->line('Sua subscrição expira em breve. Para evitar interrupção dos serviços, renove agora.');
        } else {
            $message->line('')
                    ->line('🟢 **RENOVAÇÃO RECOMENDADA**')
                    ->line('Prepare-se para renovar sua subscrição para manter o acesso ininterrupto.');
        }

        $message->line('')
                ->line('**Para Renovar:**')
                ->line('1. Entre em contato com nossa equipe comercial')
                ->line('2. Ou acesse o painel administrativo')
                ->line('3. Escolha seu plano e proceda com o pagamento');

        // Botão de ação
        if (route('system.subscriptions')) {
            $message->action('Ver Subscrições', route('system.subscriptions'));
        }

        $message->line('')
                ->line('**Contato:**')
                ->line('📧 Email: suporte@sistema-manutencao.co.mz')
                ->line('📞 Telefone: +258 XX XXX XXXX')
                ->line('')
                ->line('Se você já renovou sua subscrição, pode ignorar este email.')
                ->salutation('Atenciosamente, Equipe do Sistema de Manutenção');

        return $message;
        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', url('/'))
        //     ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $expiresAt = Carbon::parse($this->subscription->expires_at);
        $daysLeft = now()->diffInDays($expiresAt, false);
        $urgency = $this->getUrgencyLevel($daysLeft);

        return [
            'type' => 'subscription_expiring',
            'urgency' => $urgency,
            'title' => $this->getTitleByUrgency($urgency),
            'message' => $this->getNotificationMessage($daysLeft),
            'subscription_id' => $this->subscription->id,
            'company_name' => $this->subscription->company->name,
            'plan_name' => $this->subscription->plan->name,
            'expires_at' => $expiresAt->toISOString(),
            'days_left' => $daysLeft,
            'action_url' => route('system.subscriptions'),
            'action_text' => 'Ver Subscrições',
        ];
    }

     /**
     * Get urgency level based on days left
     */
    private function getUrgencyLevel(int $daysLeft): string
    {
        if ($daysLeft <= 0) {
            return 'critical';
        } elseif ($daysLeft <= 3) {
            return 'high';
        } elseif ($daysLeft <= 7) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Get email subject by urgency
     */
    private function getSubjectByUrgency(string $urgency, string $companyName): string
    {
        return match($urgency) {
            'critical' => '🔴 URGENTE: Subscrição Expirada - ' . $companyName,
            'high' => '⚠️ ATENÇÃO: Subscrição Expira em Breve - ' . $companyName,
            'medium' => '⏰ Lembrete: Renovação de Subscrição - ' . $companyName,
            'low' => '📋 Aviso: Subscrição a Expirar - ' . $companyName,
            default => 'Aviso de Expiração de Subscrição - ' . $companyName
        };
    }

    /**
     * Get title by urgency for database notification
     */
    private function getTitleByUrgency(string $urgency): string
    {
        return match($urgency) {
            'critical' => 'Subscrição Expirada!',
            'high' => 'Subscrição Expira Hoje!',
            'medium' => 'Subscrição Expira em Breve',
            'low' => 'Lembrete de Renovação',
            default => 'Aviso de Expiração'
        };
    }

    /**
     * Get opening message by urgency
     */
    private function getOpeningMessage(string $urgency, int $daysLeft): string
    {
        if ($daysLeft <= 0) {
            return 'Sua subscrição EXPIROU! Os serviços podem ser interrompidos a qualquer momento.';
        } elseif ($daysLeft <= 1) {
            return 'Sua subscrição expira HOJE! Renove agora para evitar interrupção dos serviços.';
        } elseif ($daysLeft <= 3) {
            return "Sua subscrição expira em {$daysLeft} dias. É altamente recomendado renovar o quanto antes.";
        } else {
            return "Sua subscrição expira em {$daysLeft} dias. Prepare-se para a renovação.";
        }
    }

    /**
     * Get time remaining description
     */
    private function getTimeRemaining(int $daysLeft): string
    {
        if ($daysLeft <= 0) {
            $hoursOverdue = abs(now()->diffInHours(Carbon::parse($this->subscription->expires_at), false));
            return "Expirou há {$hoursOverdue} horas";
        } elseif ($daysLeft < 1) {
            $hoursLeft = now()->diffInHours(Carbon::parse($this->subscription->expires_at), false);
            return "{$hoursLeft} horas restantes";
        } else {
            return "{$daysLeft} dias restantes";
        }
    }

    /**
     * Get notification message for database
     */
    private function getNotificationMessage(int $daysLeft): string
    {
        $companyName = $this->subscription->company->name;
        $planName = $this->subscription->plan->name;
        
        if ($daysLeft <= 0) {
            return "A subscrição do plano {$planName} para {$companyName} EXPIROU!";
        } elseif ($daysLeft <= 1) {
            return "A subscrição do plano {$planName} para {$companyName} expira hoje!";
        } else {
            return "A subscrição do plano {$planName} para {$companyName} expira em {$daysLeft} dias.";
        }
    }
}
