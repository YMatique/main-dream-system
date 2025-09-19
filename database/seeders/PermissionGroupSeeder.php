<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $groups = [
            // ========== USUÁRIO ORDENS ==========
            [
                'name' => 'Usuário Ordens',
                'description' => 'Pode criar e gerir ordens de reparação',
                'color' => '#3B82F6',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 10,
                'permissions' => [
                    'forms.form1.access',
                    'forms.form2.access',
                    'forms.form3.access',
                    'forms.form4.access',
                    'forms.form5.access',
                    'repair_orders.create',
                    'repair_orders.edit',
                    'repair_orders.view_all',
                    'repair_orders.export',
                    'evaluation.view_own',
                ],
            ],

            // ========== USUÁRIO FATURAÇÃO ==========
            [
                'name' => 'Usuário Faturação',
                'description' => 'Pode gerir todas as faturações',
                'color' => '#10B981',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 20,
                'permissions' => [
                    'forms.form3.access',
                    'billing.hh.manage',
                    'billing.estimated.manage',
                    'billing.real.manage',
                    // 'billing.currency.change',
                    'billing.view_all',
                    // 'repair_orders.view_all',
                    // 'evaluation.view_own',
                ],
            ],

            // ========== USUÁRIO AVALIADOR ==========
            [
                'name' => 'Usuário Avaliador',
                'description' => 'Pode avaliar funcionários (por departamento)',
                'color' => '#F59E0B',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 30,
                'permissions' => [
                    'evaluation.create',
                    'evaluation.approve',
                    'repair_orders.view_all', // Para contexto das avaliações
                    'reports.view',
                ],
            ],

            // ========== USUÁRIO GESTOR ==========
            [
                'name' => 'Usuário Gestor',
                'description' => 'Acesso amplo para gestão operacional',
                'color' => '#8B5CF6',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 40,
                'permissions' => [
                    // Formulários
                    // 'forms.form1.access',
                    // 'forms.form2.access',
                    // 'forms.form3.access',
                    // 'forms.form4.access',
                    // 'forms.form5.access',
                    // Ordens
                    // 'repair_orders.create',
                    // 'repair_orders.edit',
                    // 'repair_orders.view_all',
                    // 'repair_orders.delete',
                    // 'repair_orders.export',
                    // Faturação
                    // 'billing.hh.manage',
                    // 'billing.estimated.manage',
                    // 'billing.real.manage',
                    // 'billing.currency.change',
                    // 'billing.view_all',
                    // Dados Mestres
                    'masters.clients.manage',
                    'masters.employees.manage',
                    'masters.materials.manage',
                    'masters.maintenance_types.manage',
                    'masters.costs.manage',
                    'masters.statuses.manage',
                    'masters.users.manage'
                    // Avaliação
                    // 'evaluation.create',
                    // 'evaluation.view_own',
                    // Relatórios
                    // 'reports.view',
                    // 'exports.data',
                ],
            ],

            // ========== USUÁRIO VISUALIZADOR ==========
            [
                'name' => 'Usuário Visualizador',
                'description' => 'Somente leitura - visualização de dados',
                'color' => '#6B7280',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 50,
                'permissions' => [
                    'repair_orders.view_all',
                    'billing.view_all',
                    'evaluation.view_own',
                    'reports.view',
                ],
            ],

            // ========== ADMIN EMPRESA ==========
            [
                'name' => 'Admin Empresa',
                'description' => 'Acesso administrativo completo (Company Admin)',
                'color' => '#EF4444',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 60,
                'permissions' => [
                    // Todas as permissões (Company Admin já tem via user_type)
                    // Este grupo é mais para referência/documentação
                    'users.manage',
                    'users.permissions.manage',
                    'company.settings',
                    'evaluation.approve',
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            $permissions = $groupData['permissions'];
            unset($groupData['permissions']);

            // Criar o grupo
            $group = PermissionGroup::updateOrCreate(
                ['name' => $groupData['name']],
                $groupData
            );

            // Buscar IDs das permissões
            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');

            // Sincronizar permissões do grupo
            $group->permissions()->sync($permissionIds);

            $this->command->info("✓ Grupo '{$group->name}' criado com " . count($permissionIds) . " permissões");
        }

        $this->command->info('✓ Grupos de permissões criados com sucesso!');
    }
}
