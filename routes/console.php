<?php

use App\Models\System\Company;
use App\Models\System\Subscription;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// Comando para criar super admin via tinker
Artisan::command('make:super-admin {name} {email}', function ($name, $email) {
    $password = \Illuminate\Support\Str::random(12);
    
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'user_type' => 'super_admin',
        'status' => 'active',
        'permissions' => ['*'],
        'email_verified_at' => now(),
    ]);
    
    $this->info("Super Admin criado com sucesso!");
    $this->info("Email: {$email}");
    $this->info("Senha temporária: {$password}");
    $this->warn("IMPORTANTE: Altere a senha no primeiro login!");
    
})->purpose('Criar um novo Super Administrador');

// Comando para reset de senha
Artisan::command('user:reset-password {email}', function ($email) {
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        $this->error("Usuário não encontrado: {$email}");
        return;
    }
    
    $password = \Illuminate\Support\Str::random(12);
    
    $user->update([
        'password' => Hash::make($password),
        'password_reset_required' => true,
    ]);
    
    $this->info("Senha redefinida para: {$user->name}");
    $this->info("Nova senha: {$password}");
    
})->purpose('Redefinir senha de um usuário');

// Comando para estatísticas rápidas
Artisan::command('stats:quick', function () {
    $this->info('📊 ESTATÍSTICAS RÁPIDAS DO SISTEMA');
    $this->newLine();
    
    $stats = [
        'Empresas Ativas' => Company::where('status', 'active')->count(),
        'Total de Usuários' => User::count(),
        'Usuários Ativos' => User::where('status', 'active')->count(),
        'Subscrições Ativas' => Subscription::where('status', 'active')->count(),
        'Subscrições Expirando (7 dias)' => Subscription::where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->count(),
    ];
    
    foreach ($stats as $label => $value) {
        $this->info("• {$label}: {$value}");
    }
    
})->purpose('Mostrar estatísticas rápidas do sistema');

// Comando para verificar configuração
Artisan::command('config:check', function () {
    $this->info('🔧 VERIFICAÇÃO DE CONFIGURAÇÃO');
    $this->newLine();
    
    $checks = [
        'APP_ENV' => config('app.env'),
        'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
        'Database Connection' => config('database.default'),
        'Cache Driver' => config('cache.default'),
        'Session Driver' => config('session.driver'),
        'Queue Driver' => config('queue.default'),
        'Mail Driver' => config('mail.default'),
    ];
    
    foreach ($checks as $key => $value) {
        $this->info("• {$key}: {$value}");
    }
    
    // Verificar conexões críticas
    $this->newLine();
    $this->info('🔍 VERIFICAÇÕES DE CONECTIVIDADE:');
    
    try {
        DB::connection()->getPdo();
        $this->info('✅ Banco de dados: Conectado');
    } catch (\Exception $e) {
        $this->error('❌ Banco de dados: Erro de conexão');
    }
    
    try {
        Cache::put('test', 'value', 60);
        $test = Cache::get('test');
        if ($test === 'value') {
            $this->info('✅ Cache: Funcionando');
            Cache::forget('test');
        } else {
            $this->error('❌ Cache: Não está funcionando');
        }
    } catch (\Exception $e) {
        $this->error('❌ Cache: Erro - ' . $e->getMessage());
    }
    
})->purpose('Verificar configurações do sistema');

// Comando para verificar subscrições expirando
Schedule::command('subscriptions:check-expiring --days=7')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->onSuccess(function () {
                     Log::info('Verificação de subscrições expirando executada com sucesso');
                 })
                 ->onFailure(function () {
                     Log::error('Falha na verificação de subscrições expirando');
                 });

Schedule::command('subscriptions:check-expiring --days=3') ->dailyAt('14:00')
                 ->withoutOverlapping();

// Verificar subscrições expirando em 1 dia - diariamente às 18:00
Schedule::command('subscriptions:check-expiring --days=1')
    ->dailyAt('18:00')
    ->withoutOverlapping();

// Enviar relatórios diários - todos os dias às 08:00
Schedule::command('reports:send-daily')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Relatórios diários enviados com sucesso');
    });

// Limpeza do sistema - toda segunda-feira às 02:00
Schedule::command('system:cleanup --audit-days=365 --notification-days=30')
    ->weeklyOn(1, '02:00')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Limpeza do sistema executada com sucesso');
    });

// Limpeza mais agressiva de logs - primeiro dia do mês às 03:00
Schedule::command('system:cleanup --audit-days=180 --notification-days=15')
    ->monthlyOn(1, '03:00')
    ->withoutOverlapping();

// Backup automático do banco - diariamente às 01:00
Schedule::command('backup:run --only-db')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Backup do banco executado com sucesso');
    })
    ->onFailure(function () {
        Log::error('Falha no backup do banco');
        // Enviar notificação para super admins sobre falha no backup
    });

// Verificação de saúde do sistema - a cada 6 horas
Schedule::command('system:status')
    ->cron('0 */6 * * *')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::call(function () {
            DB::statement('OPTIMIZE TABLE audit_logs');
            DB::statement('OPTIMIZE TABLE notifications');
            DB::statement('ANALYZE TABLE users, companies, subscriptions');
            Log::info('Otimização do banco executada');
        })->weeklyOn(5, '04:00');

Schedule::call(function () {
            // Atualizar last_activity_at das empresas baseado na atividade dos usuários
            DB::statement("
                UPDATE companies c 
                SET last_activity_at = (
                    SELECT MAX(last_login_at) 
                    FROM users u 
                    WHERE u.company_id = c.id 
                    AND u.last_login_at IS NOT NULL
                )
                WHERE c.status = 'active'
            ");
            
            Log::info('Estatísticas de empresas atualizadas');
        })->dailyAt('06:00');

Schedule::command('logs:clean --days=90')->monthly();