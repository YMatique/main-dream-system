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

                      // Índices para performance
            $table->index(['target_department_id', 'stage_number']);
            $table->index(['approver_user_id', 'is_active']);
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
            $table->dropIndex(['target_department_id', 'stage_number']);
            $table->dropIndex(['approver_user_id', 'is_active']);
            $table->dropColumn(['approver_user_id', 'target_department_id']);
        });
    }
};
