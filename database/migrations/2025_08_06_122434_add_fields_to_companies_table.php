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
            // Informações geográficas essenciais
            
            // Configurações essenciais
            $table->json('settings')->nullable()->after('city'); // Configurações gerais
            $table->json('notification_settings')->nullable()->after('settings'); // Configurações de notificações
            
            // Moeda preferida (essencial para faturação)
            $table->enum('preferred_currency', ['MZN', 'USD'])->default('MZN')->after('last_activity_at');
            
            // Campos de auditoria essenciais
            $table->foreignId('created_by')->nullable()->after('preferred_currency')->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable()->after('created_by');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->onDelete('set null');
            
            // Notas internas para administradores
            $table->text('internal_notes')->nullable()->after('verified_by');
            
            // Índices essenciais
            $table->index(['created_by']);
            $table->index(['verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
              // Remover colunas
            $table->dropColumn([
                'settings',
                'notification_settings',
                'preferred_currency',
                'created_by',
                'verified_at',
                'verified_by',
                'internal_notes'
            ]);
            
            // Remover índices
            $table->dropIndex(['created_by']);
            $table->dropIndex(['verified_at']);
        });
    }
};
