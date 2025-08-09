<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $permissions = [
            // ========== FORMULÁRIOS ==========
            [
                'name' => 'forms.form1.access',
                'description' => 'Acesso ao Formulário 1 (Inicial)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'forms.form2.access',
                'description' => 'Acesso ao Formulário 2 (Técnicos + Materiais)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'forms.form3.access',
                'description' => 'Acesso ao Formulário 3 (Faturação)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'forms.form4.access',
                'description' => 'Acesso ao Formulário 4 (Máquina)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'forms.form5.access',
                'description' => 'Acesso ao Formulário 5 (Equipamento)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 50,
            ],

            // ========== ORDENS DE REPARAÇÃO ==========
            [
                'name' => 'repair_orders.create',
                'description' => 'Criar ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'repair_orders.edit',
                'description' => 'Editar ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'repair_orders.view_all',
                'description' => 'Ver todas as ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'repair_orders.delete',
                'description' => 'Eliminar ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'repair_orders.export',
                'description' => 'Exportar dados de ordens',
                'category' => 'repair_orders',
                'group' => 'export_data',
                'is_system' => true,
                'sort_order' => 50,
            ],

            // ========== FATURAÇÃO ==========
            [
                'name' => 'billing.hh.manage',
                'description' => 'Gerir Faturação HH',
                'category' => 'billing',
                'group' => 'billing_management',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'billing.estimated.manage',
                'description' => 'Gerir Faturação Estimada',
                'category' => 'billing',
                'group' => 'billing_management',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'billing.real.manage',
                'description' => 'Gerir Faturação Real',
                'category' => 'billing',
                'group' => 'billing_management',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'billing.currency.change',
                'description' => 'Alterar moeda de faturação',
                'category' => 'billing',
                'group' => 'billing_management',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'billing.view_all',
                'description' => 'Ver todas as faturações',
                'category' => 'billing',
                'group' => 'billing_view',
                'is_system' => true,
                'sort_order' => 50,
            ],

            // ========== AVALIAÇÃO ==========
            [
                'name' => 'evaluation.create',
                'description' => 'Criar avaliações de funcionários',
                'category' => 'evaluation',
                'group' => 'evaluation_management',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'evaluation.approve',
                'description' => 'Aprovar avaliações (só Company Admin)',
                'category' => 'evaluation',
                'group' => 'evaluation_management',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'evaluation.view_own',
                'description' => 'Ver próprias avaliações',
                'category' => 'evaluation',
                'group' => 'evaluation_view',
                'is_system' => true,
                'sort_order' => 30,
            ],

            // ========== DADOS MESTRES ==========
            [
                'name' => 'masters.clients.manage',
                'description' => 'Gerir clientes',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'masters.employees.manage',
                'description' => 'Gerir funcionários',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'masters.materials.manage',
                'description' => 'Gerir materiais',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'masters.maintenance_types.manage',
                'description' => 'Gerir tipos de manutenção',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'masters.costs.manage',
                'description' => 'Gerir custos por cliente',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 50,
            ],
            [
                'name' => 'masters.statuses.manage',
                'description' => 'Gerir estados e localizações',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 60,
            ],

            // ========== GESTÃO DE USUÁRIOS ==========
            [
                'name' => 'users.manage',
                'description' => 'Gerir usuários da empresa',
                'category' => 'users',
                'group' => 'user_management',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'users.permissions.manage',
                'description' => 'Gerir permissões de usuários',
                'category' => 'users',
                'group' => 'user_management',
                'is_system' => true,
                'sort_order' => 20,
            ],

            // ========== CONFIGURAÇÕES ==========
            [
                'name' => 'company.settings',
                'description' => 'Configurações da empresa',
                'category' => 'company',
                'group' => 'company_management',
                'is_system' => true,
                'sort_order' => 10,
            ],

            // ========== RELATÓRIOS ==========
            [
                'name' => 'reports.view',
                'description' => 'Ver relatórios',
                'category' => 'reports',
                'group' => 'reporting',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'exports.data',
                'description' => 'Exportar dados',
                'category' => 'exports',
                'group' => 'export_data',
                'is_system' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('✓ Permissões criadas com sucesso!');
    }
}
