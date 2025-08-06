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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
             // Relacionamentos
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            
            // Informações da actividade
            $table->string('action'); // create, update, delete, login, logout, etc.
            $table->string('model')->nullable(); // Company, User, RepairOrder, etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID do modelo afectado
            $table->string('description'); // Descrição legível da acção
            
            // Dados da mudança
            $table->json('old_values')->nullable(); // Valores antes da mudança
            $table->json('new_values')->nullable(); // Valores após a mudança
            $table->json('metadata')->nullable(); // Dados adicionais (form_type, etc.)
            
            // Informações técnicas
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('route')->nullable(); // Rota acessada
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            
            // Categorização
            $table->enum('level', ['info', 'warning', 'error', 'critical'])->default('info');
            $table->enum('category', [
                'auth', 'system', 'company', 'user', 'repair_order', 
                'billing', 'employee', 'client', 'material', 'performance'
            ])->default('system');
            $table->timestamps();

             // Índices para performance
            $table->index(['user_id', 'created_at']);
            $table->index(['company_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['level', 'created_at']);
            $table->index(['model', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
