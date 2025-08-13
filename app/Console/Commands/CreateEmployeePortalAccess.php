<?php

namespace App\Console\Commands;

use App\Models\Company\Employee;
use App\Models\Company\EmployeePortalAccess;
use Illuminate\Console\Command;

class CreateEmployeePortalAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:create-employee-portal-access {employee_id} {email} {password}';

     protected $description = 'Criar acesso ao portal para um funcionário';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employeeId = $this->argument('employee_id');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $employee = Employee::findOrFail($employeeId);

        // Verificar se já existe acesso
        $existingAccess = EmployeePortalAccess::where('employee_id', $employeeId)->first();
        if ($existingAccess) {
            $this->error("Já existe acesso ao portal para este funcionário!");
            return 1;
        }

        // Verificar se email já está em uso
        $existingEmail = EmployeePortalAccess::where('email', $email)->first();
        if ($existingEmail) {
            $this->error("Este email já está em uso!");
            return 1;
        }

        // Criar acesso
        $portalAccess = EmployeePortalAccess::createForEmployee($employee, $email, $password);

        $this->info("Acesso ao portal criado com sucesso!");
        $this->table(['Campo', 'Valor'], [
            ['ID', $portalAccess->id],
            ['Funcionário', $employee->name],
            ['Email', $email],
            ['Token de Acesso', $portalAccess->access_token],
            ['Status', $portalAccess->is_active ? 'Ativo' : 'Inativo']
        ]);

        return 0;
    }
}
