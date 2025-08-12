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
        Schema::create('billing_hh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('repair_order_id')->constrained()->onDelete('cascade');
            
            // Dados calculados automaticamente após Form2
            $table->decimal('total_hours', 8, 2); // Total de horas do Form2
            // $table->decimal('system_price_mzn', 10, 2); // Preço/hora do sistema (MZN)
            // $table->decimal('system_price_usd', 10, 2); // Preço/hora do sistema (USD)
            
            // Valores totais calculados
            $table->decimal('total_mzn', 12, 2); // Total em meticais
            $table->decimal('total_usd', 12, 2); // Total em dólares
            
            // Moeda escolhida para faturação
            $table->enum('billing_currency', ['MZN', 'USD'])->default('MZN');
            $table->decimal('billed_amount', 12, 2); // Valor na moeda escolhida
            
            // Dados de contexto (snapshot)
            // $table->string('maintenance_type')->nullable();
            // $table->string('technicians')->nullable(); // Lista dos técnicos
            // $table->string('machine_number')->nullable();
            // $table->text('description')->nullable();
            $table->timestamps();

             // Índices
            $table->index(['company_id', 'repair_order_id']);
            $table->unique(['company_id', 'repair_order_id']); // Uma faturação HH por ordem
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_hh');
    }
};
