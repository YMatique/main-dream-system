<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expiring-subscriptions {--days=7 : Days before expiration to notify}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for subscriptions expiring soon and send notifications';


    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $days = $this->option('days');
        
        $this->info("Verificando subscrições que expiram em {$days} dias...");
        
        $notifiedCount = $notificationService->checkExpiringSubscriptions($days);
        
        $this->info("✅ {$notifiedCount} notificações de expiração enviadas.");
        
        // Processar subscrições já expiradas
        $this->info("Processando subscrições expiradas...");
        $expiredCount = $notificationService->processExpiredSubscriptions();
        
        $this->info("✅ {$expiredCount} subscrições expiradas processadas.");
        
        return Command::SUCCESS;
    
    }
}
