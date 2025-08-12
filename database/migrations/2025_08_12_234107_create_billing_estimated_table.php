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
        Schema::create('billing_estimated', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained()->onDelete('cascade');
            
            // Dados ajustáveis (diferença da HH)
            $table->decimal('estimated_hours', 8, 2); // Horas estimadas (editável)
            $table->decimal('hourly_price_mzn', 10, 2); // Preço/hora ajustado (MZN)
            $table->decimal('hourly_price_usd', 10, 2); // Preço/hora ajustado (USD)
            
            // Valores totais
            $table->decimal('total_mzn', 12, 2); // Total em meticais
            $table->decimal('total_usd', 12, 2); // Total em dólares
            
            // Moeda escolhida para faturação
            $table->enum('billing_currency', ['MZN', 'USD'])->default('MZN');
            $table->decimal('billed_amount', 12, 2); // Valor na moeda escolhida
            
            // Observações sobre ajustes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['company_id', 'repair_order_id']);
            $table->unique(['company_id', 'repair_order_id']); // Uma faturação estimada por ordem
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_estimated');
    }
};
