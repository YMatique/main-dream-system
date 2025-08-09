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
        Schema::create('evaluation_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('performance_evaluations')->onDelete('cascade');
            $table->integer('stage_number'); // Estágio da aprovação (1, 2, 3...)
            $table->string('stage_name'); // Nome do estágio (ex: "Gestor Departamento", "RH", "Direção")
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable(); // Comentários do aprovador
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['evaluation_id', 'stage_number']);
            $table->index(['approver_id', 'status']);
            $table->unique(['evaluation_id', 'stage_number']); // Um registro por estágio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_approvals');
    }
};
