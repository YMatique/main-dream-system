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
         Schema::table('performance_evaluations', function (Blueprint $table) {
            // Adicionar campos para aprovação/rejeição simplificada
            if (!Schema::hasColumn('performance_evaluations', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('performance_evaluations', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_by');
            }
            
            if (!Schema::hasColumn('performance_evaluations', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_by');
            }
            
            if (!Schema::hasColumn('performance_evaluations', 'approval_comments')) {
                $table->text('approval_comments')->nullable()->after('rejection_reason');
            }
            
            // Remover campos relacionados a estágios (se existirem)
            if (Schema::hasColumn('performance_evaluations', 'current_approval_stage')) {
                $table->dropColumn('current_approval_stage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performance_evaluations', function (Blueprint $table) {
            // Restaurar campos removidos
            $table->integer('current_approval_stage')->default(1)->after('status');
            
            // Remover campos adicionados
            $table->dropColumn([
                'rejected_at',
                'rejected_by',
                'rejection_reason',
                'approval_comments'
            ]);
        });
    }
};
