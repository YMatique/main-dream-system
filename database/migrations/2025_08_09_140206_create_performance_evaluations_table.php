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
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->date('evaluation_period'); // Mês/ano da avaliação
            $table->decimal('total_score', 8, 2)->default(0); // Pontuação total
            $table->decimal('final_percentage', 5, 2)->default(0); // Percentual final
            $table->text('recommendations'); // Campo obrigatório de recomendações
            $table->enum('status', ['draft', 'submitted', 'in_approval', 'approved', 'rejected'])->default('draft');
            $table->integer('current_approval_stage')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_below_threshold')->default(false); // Para avaliações <50%
            $table->boolean('notifications_sent')->default(false); // Controle de notificações
            $table->timestamps();

            $table->index(['company_id', 'employee_id', 'evaluation_period'],'comp_emp_eval_index');
            $table->index(['status', 'current_approval_stage'],'sta_cur_stage_index');
            $table->unique(['company_id', 'employee_id', 'evaluation_period'],'comp_emp_eval_unique'); // Uma avaliação por mês
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};
