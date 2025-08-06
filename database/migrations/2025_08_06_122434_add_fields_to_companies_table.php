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
        Schema::table('companies', function (Blueprint $table) {
            //
             // Informações adicionais da empresa
            $table->string('tax_id')->nullable()->after('email'); // NIF/NUIT
            $table->string('phone')->nullable()->after('tax_id'); // Telefone da empresa
            $table->text('address')->nullable()->after('phone'); // Endereço completo
            $table->string('city')->nullable()->after('address'); // Cidade
            $table->string('country')->default('MZ')->after('city'); // País
            $table->string('postal_code')->nullable()->after('country'); // Código postal
            
            // Configurações da empresa
            $table->json('settings')->nullable()->after('postal_code'); // Configurações gerais
            $table->json('allowed_ips')->nullable()->after('settings'); // IPs permitidos
            $table->json('notification_settings')->nullable()->after('allowed_ips'); // Configurações de notificações
            
            // Controle de atividade
            $table->timestamp('last_activity_at')->nullable()->after('notification_settings'); // Última atividade
            $table->integer('total_users')->default(0)->after('last_activity_at'); // Total de usuários
            $table->integer('active_users')->default(0)->after('total_users'); // Usuários ativos
            
            // Limites e cotas
            $table->integer('max_users')->nullable()->after('active_users'); // Máximo de usuários permitidos
            $table->integer('max_repair_orders_per_month')->nullable()->after('max_users'); // Limite de ordens por mês
            $table->json('features_enabled')->nullable()->after('max_repair_orders_per_month'); // Recursos habilitados
            
            // Faturação e cobrança
            $table->string('billing_email')->nullable()->after('features_enabled'); // Email para faturação
            $table->enum('preferred_currency', ['MZN', 'USD'])->default('MZN')->after('billing_email'); // Moeda preferida
            $table->string('timezone')->default('Africa/Maputo')->after('preferred_currency'); // Fuso horário
            
            // Campos de auditoria
            $table->foreignId('created_by')->nullable()->after('timezone')->constrained('users')->onDelete('set null'); // Quem criou a empresa
            $table->timestamp('verified_at')->nullable()->after('created_by'); // Quando foi verificada
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->onDelete('set null'); // Quem verificou
            
            // Status mais detalhado
            $table->string('status')->default('active')->change(); // active, inactive, suspended, trial, expired
            
            // Notas internas
            $table->text('internal_notes')->nullable()->after('verified_by'); // Notas para administradores
            
            // Índices para performance
            $table->index(['status', 'created_at']);
            $table->index(['last_activity_at']);
            $table->index(['created_by']);
            $table->index(['verified_at']);
            $table->index(['country', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
             $table->dropColumn([
                'tax_id',
                'phone',
                'address', 
                'city',
                'country',
                'postal_code',
                'settings',
                'allowed_ips',
                'notification_settings',
                'last_activity_at',
                'total_users',
                'active_users',
                'max_users',
                'max_repair_orders_per_month',
                'features_enabled',
                'billing_email',
                'preferred_currency',
                'timezone',
                'created_by',
                'verified_at',
                'verified_by',
                'internal_notes'
            ]);
            
            // Remover índices
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['last_activity_at']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['verified_at']);
            $table->dropIndex(['country', 'city']);
        });
    }
};
