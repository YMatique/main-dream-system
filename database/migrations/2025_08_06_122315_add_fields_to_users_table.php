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
               // Campos de segurança essenciais (sem duplicar os existentes)
            $table->json('password_history')->nullable()->after('password');
            $table->timestamp('password_updated_at')->nullable()->after('password_history');

            // Controle de sessão e segurança (sem duplicar last_login_at)
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->integer('failed_login_attempts')->default(0)->after('last_login_ip');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            
            // Configurações de segurança pessoais
            $table->json('security_settings')->nullable()->after('created_by_super_admin');
            
            // Índices adicionais (sem duplicar os existentes)
            $table->index(['failed_login_attempts']);
            $table->index(['locked_until']);
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
                'last_login_ip',
                'failed_login_attempts',
                'locked_until',
                'security_settings'
            ]);
            
            // Remover índices
            $table->dropIndex(['failed_login_attempts']);
            $table->dropIndex(['locked_until']);
        });
    }
};
