<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando setup do sistema de permissões...');

        // 1. Criar permissões
        $this->command->info('📝 Criando permissões...');
        $this->call(PermissionSeeder::class);

        // 2. Criar grupos de permissões
        $this->command->info('👥 Criando grupos de permissões...');
        $this->call(PermissionGroupSeeder::class);

        // 3. Criar usuários de exemplo
        $this->command->info('👤 Criando usuários de exemplo...');
        $this->call(DefaultUsersSeeder::class);

        // 4. Configurar avaliadores de departamento
        $this->command->info('🏢 Configurando avaliadores de departamento...');
        $this->call(DepartmentEvaluatorSeeder::class);

        $this->command->info('✅ Sistema de permissões configurado com sucesso!');
        $this->command->info('');
        $this->command->info('📋 Usuários criados:');
        $this->command->info('• Super Admin: superadmin@sistema.com');
        $this->command->info('• Company Admins: admin@{company_id}.com');
        $this->command->info('• Usuários de exemplo por empresa');
        $this->command->info('');
        $this->command->info('🔑 Senha padrão para todos: password');
    }
}
