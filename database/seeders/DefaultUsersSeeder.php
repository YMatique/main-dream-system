<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use App\Models\System\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $superAdmin = User::updateOrCreate([
            'email' => 'superadmin@sistema.com'
        ], [
            'name' => 'Super Administrador',
            'user_type' => 'super_admin',
            'company_id' => null, // Super admin não pertence a empresa específica
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info("✓ Super Admin criado: {$superAdmin->email}");

        // Criar usuários de exemplo para cada empresa
        $companies = Company::take(2)->get(); // Pega as primeiras 2 empresas

        foreach ($companies as $company) {
            // Company Admin
            $companyAdmin = User::updateOrCreate([
                'email' => "admin@{$company->id}.com"
            ], [
                'name' => "Admin {$company->name}",
                'user_type' => 'company_admin',
                'company_id' => $company->id,
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]);

            $this->command->info("✓ Company Admin criado: {$companyAdmin->email}");

            // Usuários de exemplo com diferentes grupos
            $this->createExampleUsers($company);
        }
    }
     private function createExampleUsers(Company $company): void
    {
        $userExamples = [
            [
                'name' => 'João Silva - Ordens',
                'email' => "joao.ordens@{$company->id}.com",
                'group' => 'Usuário Ordens',
            ],
            [
                'name' => 'Maria Santos - Faturação',
                'email' => "maria.faturacao@{$company->id}.com",
                'group' => 'Usuário Faturação',
            ],
            [
                'name' => 'Pedro Costa - Avaliador',
                'email' => "pedro.avaliador@{$company->id}.com",
                'group' => 'Usuário Avaliador',
            ],
            [
                'name' => 'Ana Ferreira - Gestor',
                'email' => "ana.gestor@{$company->id}.com",
                'group' => 'Usuário Gestor',
            ],
            [
                'name' => 'Carlos Oliveira - Visualizador',
                'email' => "carlos.view@{$company->id}.com",
                'group' => 'Usuário Visualizador',
            ],
        ];

        foreach ($userExamples as $userData) {
            $user = User::updateOrCreate([
                'email' => $userData['email']
            ], [
                'name' => $userData['name'],
                'user_type' => 'company_user',
                'company_id' => $company->id,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // Atribuir grupo de permissões
            $group = PermissionGroup::where('name', $userData['group'])->first();
            if ($group) {
                $user->assignPermissionGroup($group->id);
                $this->command->info("✓ Usuário criado: {$user->email} - Grupo: {$group->name}");
            }
        }
    }
}
