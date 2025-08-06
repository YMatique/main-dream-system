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
            //
            // Campos de segurança e auditoria
            $table->json('password_history')->nullable()->after('password'); // Histórico de senhas
            $table->timestamp('password_updated_at')->nullable()->after('password_history'); // Quando a senha foi alterada
            $table->boolean('password_reset_required')->default(false)->after('password_updated_at'); // Forçar troca de senha
            
            // Controle de sessão
            $table->timestamp('last_login_at')->nullable()->after('password_reset_required'); // Último login
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at'); // IP do último login
            $table->integer('failed_login_attempts')->default(0)->after('last_login_ip'); // Tentativas de login falhadas
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts'); // Bloqueado até
            
            // Campos de auditoria
            $table->foreignId('created_by')->nullable()->after('locked_until')->constrained('users')->onDelete('set null'); // Quem criou
            $table->boolean('created_by_super_admin')->default(false)->after('created_by'); // Se foi criado por super admin
            
            // Status mais detalhado
            $table->string('status')->default('active')->change(); // active, inactive, suspended, pending
            
            // Configurações de segurança do usuário
            $table->json('security_settings')->nullable()->after('created_by_super_admin'); // Configurações pessoais de segurança
            
            // Índices para performance
            $table->index(['status', 'user_type']);
            $table->index(['company_id', 'status']);
            $table->index(['last_login_at']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn([
                'password_history',
                'password_updated_at', 
                'password_reset_required',
                'last_login_at',
                'last_login_ip',
                'failed_login_attempts',
                'locked_until',
                'created_by',
                'created_by_super_admin',
                'security_settings'
            ]);
            
            // Remover índices
            $table->dropIndex(['status', 'user_type']);
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['last_login_at']);
            $table->dropIndex(['created_by']);
        });
    }
};
