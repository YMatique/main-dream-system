<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(   public ?string $temporaryPassword = null)
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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      $companyName = $notifiable->company?->name ?? 'Sistema';
        
        $message = (new MailMessage)
            ->subject('Bem-vindo ao Sistema de Manutenção - ' . $companyName)
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('Sua conta foi criada com sucesso no Sistema de Manutenção.');

        if ($this->temporaryPassword) {
            $message->line('**Dados de Acesso:**')
                    ->line('Email: ' . $notifiable->email)
                    ->line('Senha temporária: **' . $this->temporaryPassword . '**')
                    ->line('')
                    ->line('⚠️ **IMPORTANTE**: Esta é uma senha temporária. Você será solicitado a alterá-la no primeiro login.')
                    ->action('Fazer Login', route('login'));
        } else {
            $message->line('Você pode fazer login usando o email e senha fornecidos pelo administrador.')
                    ->action('Acessar Sistema', route('login'));
        }

        $message->line('')
                ->line('**Tipo de Conta:** ' . $this->getUserTypeLabel($notifiable->user_type))
                ->line('**Empresa:** ' . $companyName)
                ->line('')
                ->line('Se você não esperava receber este email ou tem dúvidas sobre sua conta, entre em contato com o administrador.')
                ->salutation('Atenciosamente, Equipe do Sistema de Manutenção');

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
            'type' => 'welcome',
            'title' => 'Bem-vindo ao Sistema!',
            'message' => 'Sua conta foi criada com sucesso. ' . 
                        ($this->temporaryPassword ? 'Verifique seu email para os dados de acesso.' : 'Você já pode fazer login.'),
            'action_url' => route('login'),
            'action_text' => 'Fazer Login',
            'user_type' => $notifiable->user_type,
            'company_name' => $notifiable->company?->name,
            'has_temp_password' => !is_null($this->temporaryPassword),
        ];
    }
     /**
     * Get user type label
     */
    private function getUserTypeLabel(string $userType): string
    {
        return match($userType) {
            'super_admin' => 'Super Administrador',
            'company_admin' => 'Administrador da Empresa',
            'company_user' => 'Usuário da Empresa',
            default => 'Usuário'
        };
    }
}
