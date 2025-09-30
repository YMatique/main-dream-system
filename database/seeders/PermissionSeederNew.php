<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeederNew extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // ========== FORMULÁRIOS ==========
            // Form 1
            [
                'name' => 'forms.form1.list',
                'description' => 'Listar Formulário 1 (Inicial)',
                'category' => 'forms',
                'group' => 'form_list',
                'is_system' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'forms.form1.access',
                'description' => 'Acessar/Editar Formulário 1 (Inicial)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 12,
            ],
            
            // Form 2
            [
                'name' => 'forms.form2.list',
                'description' => 'Listar Formulário 2 (Técnicos + Materiais)',
                'category' => 'forms',
                'group' => 'form_list',
                'is_system' => true,
                'sort_order' => 21,
            ],
            [
                'name' => 'forms.form2.access',
                'description' => 'Acessar/Editar Formulário 2 (Técnicos + Materiais)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 22,
            ],
            
            // Form 3
            [
                'name' => 'forms.form3.list',
                'description' => 'Listar Formulário 3 (Faturação)',
                'category' => 'forms',
                'group' => 'form_list',
                'is_system' => true,
                'sort_order' => 31,
            ],
            [
                'name' => 'forms.form3.access',
                'description' => 'Acessar/Editar Formulário 3 (Faturação)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 32,
            ],
            
            // Form 4
            [
                'name' => 'forms.form4.list',
                'description' => 'Listar Formulário 4 (Máquina)',
                'category' => 'forms',
                'group' => 'form_list',
                'is_system' => true,
                'sort_order' => 41,
            ],
            [
                'name' => 'forms.form4.access',
                'description' => 'Acessar/Editar Formulário 4 (Máquina)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 42,
            ],
            
            // Form 5
            [
                'name' => 'forms.form5.list',
                'description' => 'Listar Formulário 5 (Equipamento)',
                'category' => 'forms',
                'group' => 'form_list',
                'is_system' => true,
                'sort_order' => 51,
            ],
            [
                'name' => 'forms.form5.access',
                'description' => 'Acessar/Editar Formulário 5 (Equipamento)',
                'category' => 'forms',
                'group' => 'form_access',
                'is_system' => true,
                'sort_order' => 52,
            ],

            // ========== ORDENS DE REPARAÇÃO ==========
            [
                'name' => 'repair_orders.view_initial_page',
                'description' => 'Ver a Página de Listagem de formulários',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'repair_orders.view_all',
                'description' => 'Ver todas as ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'repair_orders.delete',
                'description' => 'Eliminar ordens de reparação',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'repair_orders.export',
                'description' => 'Exportar dados de ordens',
                'category' => 'repair_orders',
                'group' => 'export_data',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'repair_orders.advanced_listing',
                'description' => 'Ver Listagem Avançada',
                'category' => 'repair_orders',
                'group' => 'repair_management',
                'is_system' => true,
                'sort_order' => 40,
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
                'name' => 'billing.view_all',
                'description' => 'Ver todas as faturações',
                'category' => 'billing',
                'group' => 'billing_view',
                'is_system' => true,
                'sort_order' => 40,
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
                'name' => 'evaluation.reports',
                'description' => 'Ver Relatórios de Avaliação',
                'category' => 'evaluation',
                'group' => 'evaluation_management',
                'is_system' => true,
                'sort_order' => 30,
            ],
            [
                'name' => 'evaluation.metrics',
                'description' => 'Gerir Métricas de Avaliação',
                'category' => 'evaluation',
                'group' => 'evaluation_management',
                'is_system' => true,
                'sort_order' => 40,
            ],
            [
                'name' => 'evaluation.stages',
                'description' => 'Gerir Estágios de Aprovação',
                'category' => 'evaluation',
                'group' => 'evaluation_management',
                'is_system' => true,
                'sort_order' => 50,
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
                'name' => 'masters.statuses_location.manage',
                'description' => 'Gerir estados e localizações',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 60,
            ],
            // [
            //     'name' => 'masters.locations.manage',
            //     'description' => 'Gerir localizações',
            //     'category' => 'masters',
            //     'group' => 'master_data',
            //     'is_system' => true,
            //     'sort_order' => 70,
            // ],
            [
                'name' => 'masters.machines.manage',
                'description' => 'Gerir Máquinas',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 80,
            ],
            [
                'name' => 'masters.requesters.manage',
                'description' => 'Gerir Solicitantes',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 90,
            ],
            [
                'name' => 'masters.departments.manage',
                'description' => 'Gerir Departamentos',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 100,
            ],
            [
                'name' => 'masters.users.manage',
                'description' => 'Gerir usuários da empresa',
                'category' => 'masters',
                'group' => 'master_data',
                'is_system' => true,
                'sort_order' => 110,
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

            // ========== RELATÓRIOS E EXPORTAÇÃO ==========
            // [
            //     'name' => 'reports.view',
            //     'description' => 'Ver relatórios',
            //     'category' => 'reports',
            //     'group' => 'reporting',
            //     'is_system' => true,
            //     'sort_order' => 10,
            // ],
            [
                'name' => 'exports.data',
                'description' => 'Exportar dados',
                'category' => 'exports',
                'group' => 'export_data',
                'is_system' => true,
                'sort_order' => 10,
            ],
            
            // ========== DASHBOARD ==========
            [
                'name' => 'dashboard.view',
                'description' => 'Ver Dashboard',
                'category' => 'dashboard',
                'group' => 'dashboard',
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

        $this->command->info('✓ Permissões criadas/atualizadas com sucesso!');
    }
}