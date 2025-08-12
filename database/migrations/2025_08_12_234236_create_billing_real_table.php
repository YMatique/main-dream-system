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
        Schema::create('billing_real', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained()->onDelete('cascade');

            // Dados do Form3 (fixos após geração)
            $table->date('billing_date'); // Data de faturação do Form3
            $table->decimal('billed_hours', 8, 2); // Horas faturadas do Form3
            $table->decimal('hourly_price_mzn', 10, 2); // Preço/hora baseado no cliente
            $table->decimal('hourly_price_usd', 10, 2); // Preço/hora baseado no cliente

            // Valores totais (fixos)
            $table->decimal('total_mzn', 12, 2); // Total em meticais
            $table->decimal('total_usd', 12, 2); // Total em dólares

            // Moeda escolhida para faturação (ÚNICA coisa editável após aprovação)
            $table->enum('billing_currency', ['MZN', 'USD'])->default('MZN');
            $table->decimal('billed_amount', 12, 2); // Valor na moeda escolhida

            // Dados de contexto (snapshot do Form3)
            // $table->string('client_name');
            // $table->string('machine_number')->nullable();
            // $table->text('description')->nullable();

            $table->timestamps();

            // Índices
            $table->index(['company_id', 'repair_order_id']);
            $table->index('billing_date');
            $table->unique(['company_id', 'repair_order_id']); // Uma faturação real por ordem
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_real');
    }
};
