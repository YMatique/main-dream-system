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
        Schema::table('users', function (Blueprint $table) {
            // Tipo de usuário no sistema (substitui o is_super_admin)
            $table->enum('user_type', ['super_admin', 'company_admin', 'company_user'])
                  ->default('company_user')
                  ->after('company_id');
            
            // Status do usuário
            $table->enum('status', ['active', 'inactive', 'suspended'])
                  ->default('active')
                  ->after('user_type');
            
            // Informações adicionais
            $table->string('phone')->nullable()->after('status');
            $table->json('permissions')->nullable()->after('phone'); // Permissões específicas
            $table->timestamp('last_login_at')->nullable()->after('permissions');
            $table->boolean('password_reset_required')->default(false)->after('last_login_at');
            
            // Campos de auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('password_reset_required');
            $table->boolean('created_by_super_admin')->default(false)->after('created_by');
            
            // Índices para performance (company_id já tem índice)
            $table->index(['user_type', 'status']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropIndex(['user_type', 'status']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'user_type',
                'status',
                'phone',
                'permissions',
                'last_login_at',
                'password_reset_required',
                'created_by',
                'created_by_super_admin'
            ]);
        });
    }
};