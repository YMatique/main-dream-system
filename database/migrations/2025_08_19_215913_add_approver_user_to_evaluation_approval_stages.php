<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluation_approval_stages', function (Blueprint $table) {
            //
            // Usuário específico que aprova este estágio

            $table->foreignId('approver_user_id')
                ->nullable()
                ->after('stage_name')
                ->constrained('users')
                ->onDelete('set null');

            // Departamento alvo (para qual departamento este estágio se aplica)
            $table->foreignId('target_department_id')
                ->nullable()
                ->after('approver_user_id')
                ->constrained('departments')
                ->onDelete('cascade');

            // Campo para marcar se é o último estágio
            $table->boolean('is_final_stage')
                ->default(false)
                ->after('is_active');

            // Índices para performance
            // $table->index(['target_department_id', 'stage_number','department_stage_number']);
            $table->index(['target_department_id', 'stage_number'], 'eval_stages_dept_stage_idx');
            // $table->index(['approver_user_id', 'is_active','aprrover_is_active']);
            $table->index(['approver_user_id', 'is_active'], 'eval_stages_approver_idx');
            $table->index(['company_id', 'target_department_id', 'is_active'], 'eval_stages_company_dept_idx');
            $table->index(['target_department_id', 'is_final_stage'], 'eval_depatament_final_stage_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_approval_stages', function (Blueprint $table) {


            $table->dropForeign(['approver_user_id']);
            $table->dropForeign(['target_department_id']);
            $table->dropIndex('eval_stages_dept_stage_idx');
            $table->dropIndex('eval_stages_approver_idx');
            $table->dropIndex('eval_stages_company_dept_idx');
            $table->dropIndex('eval_depatament_final_stage_idx');
            // $table->dropIndex(['target_department_id', 'stage_number','department_stage_number']);
            // $table->dropIndex(['approver_user_id', 'is_active','aprrover_is_active']);
            // $table->dropIndex(['target_department_id', 'is_final_stage','depatament_final_stage']);
            $table->dropColumn(['approver_user_id', 'target_department_id']);
            $table->dropColumn('is_final_stage');
        });
    }
};
