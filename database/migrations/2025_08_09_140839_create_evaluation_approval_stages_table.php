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
        Schema::create('evaluation_approval_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->integer('stage_number');
            $table->string('stage_name'); // Nome do estágio
            $table->text('description')->nullable();
            $table->json('approver_roles')->nullable(); // Roles que podem aprovar neste estágio
            $table->json('approver_departments')->nullable(); // Departamentos específicos
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['company_id', 'stage_number', 'is_active'],'comp_stage_act_index');
            // $table->unique(['company_id', 'stage_number'],'comp_stage_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_approval_stages');
    }
};
