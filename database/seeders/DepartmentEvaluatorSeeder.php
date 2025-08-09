<?php

namespace Database\Seeders;

use App\Models\DepartmentEvaluator;
use App\Models\System\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentEvaluatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $companies = Company::with(['users', 'departments'])->take(2)->get();

        foreach ($companies as $company) {
            // Buscar usuários avaliadores da empresa
            $evaluators = $company->users()
                ->whereHas('permissionGroups', function($q) {
                    $q->where('name', 'Usuário Avaliador');
                })
                ->get();

            // Buscar gestores que também podem avaliar
            $managers = $company->users()
                ->whereHas('permissionGroups', function($q) {
                    $q->where('name', 'Usuário Gestor');
                })
                ->get();

            $allEvaluators = $evaluators->merge($managers);

            // Atribuir avaliadores a departamentos
            foreach ($company->departments as $department) {
                foreach ($allEvaluators as $evaluator) {
                    DepartmentEvaluator::updateOrCreate([
                        'user_id' => $evaluator->id,
                        'department_id' => $department->id,
                        'company_id' => $company->id,
                    ], [
                        'is_active' => true,
                        'assigned_at' => now(),
                        'assigned_by' => $company->users()
                            ->where('user_type', 'company_admin')
                            ->first()?->id,
                    ]);

                    $this->command->info("✓ Avaliador {$evaluator->name} atribuído ao departamento {$department->name}");
                }
            }
        }

        $this->command->info('✓ Avaliadores de departamento configurados!');
    }
}
