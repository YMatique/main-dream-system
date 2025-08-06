<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendDailyReports extends Command
{
      protected $signature = 'reports:send-daily';
    protected $description = 'Send daily reports to super admins';
    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Enviando relatórios diários...');
        
        $success = $notificationService->sendDailyReport();
        
        if ($success) {
            $this->info('✅ Relatórios diários enviados com sucesso!');
        } else {
            $this->error('❌ Erro ao enviar relatórios diários.');
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
