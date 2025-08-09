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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade'); // Para permissões específicas de departamento
            $table->json('metadata')->nullable(); // Para dados adicionais (ex: limitações específicas)
            $table->timestamp('granted_at')->useCurrent();
            $table->foreignId('granted_by')->nullable()->constrained('users')->onDelete('set null'); // Quem concedeu a permissão
            $table->timestamps();
            
            // Unique constraint - usuário não pode ter a mesma permissão duplicada para o mesmo departamento
            $table->unique(['user_id', 'permission_id', 'department_id'], 'user_permission_department_unique');
            
            // Indexes
            $table->index(['user_id', 'permission_id']);
            $table->index(['department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
