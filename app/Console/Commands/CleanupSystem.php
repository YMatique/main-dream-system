<?php

namespace App\Console\Commands;

use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CleanupSystem extends Command
{
    protected $signature = 'system:cleanup {--audit-days=365 : Days to keep audit logs} {--notification-days=30 : Days to keep notifications}';
    protected $description = 'Cleanup old system data (audit logs, notifications, etc.)';

    /**
     * Execute the console command.
     */
    public function handle(AuditService $auditService, NotificationService $notificationService)
    {
        $auditDays = $this->option('audit-days');
        $notificationDays = $this->option('notification-days');
        
        $this->info('Iniciando limpeza do sistema...');
        
        // Limpar logs de auditoria antigos
        $this->info("Removendo logs de auditoria com mais de {$auditDays} dias...");
        $deletedAudits = $auditService->cleanupOldLogs($auditDays);
        $this->info("✅ {$deletedAudits} logs de auditoria removidos.");
        
        // Limpar notificações antigas
        $this->info("Removendo notificações com mais de {$notificationDays} dias...");
        $deletedNotifications = $notificationService->cleanupOldNotifications($notificationDays);
        $this->info("✅ {$deletedNotifications} notificações removidas.");
        
        // Limpar arquivos temporários
        $this->info('Limpando arquivos temporários...');
        $tempPath = storage_path('app/temp');
        if (is_dir($tempPath)) {
            $files = glob($tempPath . '/*');
            $deletedFiles = 0;
            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < strtotime('-7 days')) {
                    unlink($file);
                    $deletedFiles++;
                }
            }
            $this->info("✅ {$deletedFiles} arquivos temporários removidos.");
        }
        
        $this->info('🎉 Limpeza do sistema concluída!');
        return Command::SUCCESS;
    }
}
