<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
         Schema::table('performance_evaluations', function (Blueprint $table) {
            $table->integer('current_stage_number')->nullable()->after('status');
        });
                // 2. ATUALIZAR STATUS ENUM (se necessário)
        // Adicionar 'in_approval' aos status possíveis
        // DB::statement("ALTER TABLE performance_evaluations MODIFY COLUMN status ENUM('draft', 'submitted', 'in_approval', 'approved', 'rejected') DEFAULT 'draft'");

        // 3. VERIFICAR se EvaluationApproval tem todos os campos
        if (!Schema::hasColumn('evaluation_approvals', 'stage_name')) {
            Schema::table('evaluation_approvals', function (Blueprint $table) {
                $table->string('stage_name')->after('stage_number');
            });
        }

        // 4. ADICIONAR STATUS EXTRAS ao EvaluationApproval (se necessário)
        DB::statement("ALTER TABLE evaluation_approvals MODIFY COLUMN status ENUM('waiting', 'pending', 'approved', 'rejected', 'cancelled') DEFAULT 'waiting'");
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::table('performance_evaluations', function (Blueprint $table) {
            $table->dropColumn('current_stage_number');
        });

        // Reverter enum status
        // DB::statement("ALTER TABLE performance_evaluations MODIFY COLUMN status ENUM('draft', 'submitted', 'approved', 'rejected') DEFAULT 'draft'");
        DB::statement("ALTER TABLE evaluation_approvals MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    
    }
};
