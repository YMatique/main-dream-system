<?php 

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionGroupSeederNew extends Seeder
{
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
                    'forms.form1.list',
                    'forms.form1.access',
                    'forms.form2.list',
                    'forms.form2.access',
                    'forms.form3.list',
                    'forms.form3.access',
                    'forms.form4.list',
                    'forms.form4.access',
                    'forms.form5.list',
                    'forms.form5.access',
                    'repair_orders.view_all',
                    'repair_orders.export',
                ],
            ],

            // ========== USUÁRIO FATURAÇÃO ========== 
            // ✅ CORRIGIDO: Removidas permissões que não fazem sentido
            [
                'name' => 'Usuário Faturação',
                'description' => 'Pode gerir todas as faturações',
                'color' => '#10B981',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 20,
                'permissions' => [
                    // 'forms.form3.list',        // Lista de faturação
                    // 'forms.form3.access',      // Acesso ao form de faturação
                    'billing.hh.manage',
                    'billing.estimated.manage',
                    'billing.real.manage',
                    'billing.view_all',
                    'reports.view',            // Para ver relatórios de faturação
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
                    'evaluation.reports',
                    'evaluation.metrics',
                    'evaluation.stages',
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
                    'masters.clients.manage',
                    'masters.employees.manage',
                    'masters.materials.manage',
                    'masters.maintenance_types.manage',
                    'masters.costs.manage',
                    'masters.statuses.manage',
                    'masters.locations.manage',
                    'masters.machines.manage',
                    'masters.requesters.manage',
                    'masters.departments.manage',
                    'masters.users.manage',
                    'reports.view',
                    'exports.data',
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
                    'reports.view',
                    'dashboard.view',
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
                    'company.settings',
                    'evaluation.approve',
                    'masters.users.manage',
                    'reports.view',
                    'exports.data',
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            $permissions = $groupData['permissions'];
            unset($groupData['permissions']);

            $group = PermissionGroup::updateOrCreate(
                ['name' => $groupData['name']],
                $groupData
            );

            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
            $group->permissions()->sync($permissionIds);

            $this->command->info("✓ Grupo '{$group->name}' - " . count($permissionIds) . " permissões");
        }

        // Limpar cache de todos os usuários
        \App\Models\User::all()->each(function($user) {
            if (method_exists($user, 'clearPermissionCache')) {
                $user->clearPermissionCache();
            }
        });

        $this->command->info('✓ Grupos criados e cache limpo!');
    }
}