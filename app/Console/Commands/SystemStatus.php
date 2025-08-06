<?php

namespace App\Console\Commands;

use App\Models\System\Company;
use App\Models\System\Subscription;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SystemStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:system-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show system status and health check';

    public function handle(NotificationService $notificationService)
    {
        $this->info('🔍 SISTEMA DE MANUTENÇÃO - STATUS');
        $this->newLine();
        
        // Estatísticas gerais
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        
        $this->table(['Métrica', 'Total', 'Ativos'], [
            ['Empresas', $totalCompanies, $activeCompanies],
            ['Usuários', $totalUsers, $activeUsers],
            ['Subscrições', $totalSubscriptions, $activeSubscriptions],
        ]);
        
        // Subscrições expirando
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->count();
        
        $expired = Subscription::where('status', 'expired')->count();
        
        $this->newLine();
        $this->info('⚠️  ALERTAS:');
        
        if ($expiringSoon > 0) {
            $this->warn("• {$expiringSoon} subscrições expiram em 7 dias");
        }
        
        if ($expired > 0) {
            $this->error("• {$expired} subscrições expiradas");
        }
        
        if ($expiringSoon === 0 && $expired === 0) {
            $this->info('• Nenhum alerta de subscrição');
        }
        
        // Teste de email
        $this->newLine();
        $this->info('📧 TESTE DE EMAIL:');
        
        $emailTest = $notificationService->testEmailConfiguration();
        
        if ($emailTest['smtp_connection']) {
            $this->info('✅ Conexão SMTP: OK');
        } else {
            $this->error('❌ Conexão SMTP: FALHOU');
        }
        
        if ($emailTest['test_email_sent']) {
            $this->info('✅ Envio de email: OK');
        } else {
            $this->error('❌ Envio de email: FALHOU');
        }
        
        if (!empty($emailTest['errors'])) {
            $this->error('Erros encontrados:');
            foreach ($emailTest['errors'] as $error) {
                $this->error("• {$error}");
            }
        }
        
        // Uso de armazenamento
        $this->newLine();
        $this->info('💾 ARMAZENAMENTO:');
        
        $storageUsed = $this->getDirectorySize(storage_path());
        $this->info("• Storage usado: " . $this->formatBytes($storageUsed));
        
        // Performance do banco
        $this->newLine();
        $this->info('🗃️  BANCO DE DADOS:');
        
        $dbSize = DB::select("SELECT SUM(data_length + index_length) / 1024 / 1024 AS 'size_mb' FROM information_schema.tables WHERE table_schema = ?", [config('database.connections.mysql.database')]);
        
        if (!empty($dbSize)) {
            $this->info('• Tamanho do BD: ' . number_format($dbSize[0]->size_mb, 2) . ' MB');
        }
        
        // Últimas atividades
        $this->newLine();
        $this->info('📊 ATIVIDADE RECENTE (últimas 24h):');
        
        $newCompanies = Company::where('created_at', '>=', now()->subDay())->count();
        $newUsers = User::where('created_at', '>=', now()->subDay())->count();
        $newSubscriptions = Subscription::where('created_at', '>=', now()->subDay())->count();
        
        $this->table(['Tipo', 'Quantidade'], [
            ['Novas empresas', $newCompanies],
            ['Novos usuários', $newUsers],
            ['Novas subscrições', $newSubscriptions],
        ]);
        
        $this->newLine();
        $this->info('🎉 Verificação de status concluída!');
        
        return Command::SUCCESS;
    }
    
    private function getDirectorySize($directory)
    {
        $size = 0;
        if (is_dir($directory)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
