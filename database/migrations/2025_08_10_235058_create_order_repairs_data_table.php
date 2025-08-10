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
        // 1. TABELA PRINCIPAL - ORDENS DE REPARAÇÃO
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('order_number')->unique(); // Ordem de reparação (híbrida auto/manual)
            $table->enum('current_form', ['form1', 'form2', 'form3', 'form4', 'form5'])->default('form1');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->index(['company_id', 'order_number']);
            $table->index(['company_id', 'current_form']);
        });

        // 2. FORMULÁRIO 1 - ORDEM INICIAL
        Schema::create('repair_order_form1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->timestamp('carimbo')->default(now()); // Timestamp automático
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->text('descricao_avaria');
            $table->integer('mes')->min(1)->max(12);
            $table->integer('ano');
            $table->foreignId('requester_id')->constrained('requesters')->onDelete('cascade');
            $table->foreignId('machine_number_id')->constrained('machine_numbers')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. FORMULÁRIO 2 - TÉCNICOS E MATERIAIS
        Schema::create('repair_order_form2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->timestamp('carimbo')->default(now());
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->decimal('tempo_total_horas', 8, 2)->default(0); // Calculado automaticamente
            $table->text('actividade_realizada');
            $table->timestamps();
        });

        // 3.1. TÉCNICOS AFETOS NO FORMULÁRIO 2 (Relacionamento N:N com horas individuais)
        Schema::create('repair_order_form2_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form2_id')->constrained('repair_order_form2')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('horas_trabalhadas', 8, 2);
            $table->timestamps();

            $table->unique(['form2_id', 'employee_id']);
        });

        // 3.2. MATERIAIS UTILIZADOS NO FORMULÁRIO 2 (Materiais cadastrados)
        Schema::create('repair_order_form2_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form2_id')->constrained('repair_order_form2')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->decimal('quantidade', 10, 3);
            $table->timestamps();

            $table->unique(['form2_id', 'material_id']);
        });

        // 3.3. MATERIAIS ADICIONAIS NO FORMULÁRIO 2 (Materiais não cadastrados)
        Schema::create('repair_order_form2_additional_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form2_id')->constrained('repair_order_form2')->onDelete('cascade');
            $table->string('nome_material');
            $table->decimal('custo_unitario', 10, 2);
            $table->decimal('quantidade', 10, 3);
            $table->decimal('custo_total', 10, 2); // custo_unitario * quantidade
            $table->timestamps();
        });

        // 4. FORMULÁRIO 3 - FATURAÇÃO REAL
        Schema::create('repair_order_form3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->timestamp('carimbo')->default(now());
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->date('data_faturacao');
            $table->decimal('horas_faturadas', 8, 2);
            $table->timestamps();
        });

        // 4.1. MATERIAIS FATURADOS NO FORMULÁRIO 3
        Schema::create('repair_order_form3_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form3_id')->constrained('repair_order_form3')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->decimal('quantidade', 10, 3);
            $table->timestamps();

            $table->unique(['form3_id', 'material_id']);
        });

        // 5. FORMULÁRIO 4 - NÚMERO DE MÁQUINA
        Schema::create('repair_order_form4', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->timestamp('carimbo')->default(now());
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->foreignId('machine_number_id')->constrained('machine_numbers')->onDelete('cascade'); // Carregado dinamicamente
            $table->timestamps();
        });

        // 6. FORMULÁRIO 5 - EQUIPAMENTO E VALIDAÇÃO
        Schema::create('repair_order_form5', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->onDelete('cascade');
            $table->timestamp('carimbo')->default(now());
            $table->foreignId('machine_number_id')->constrained('machine_numbers')->onDelete('cascade'); // Dinâmico
            $table->date('data_faturacao_1');
            $table->decimal('horas_faturadas_1', 8, 2);
            $table->date('data_faturacao_2');
            $table->decimal('horas_faturadas_2', 8, 2);
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Dinâmico
            $table->text('descricao_actividades');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Técnico selecionado
            $table->timestamps();

            // Validação: diferença máxima de 4 dias entre as datas (implementar no model)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_order_form5');
        Schema::dropIfExists('repair_order_form4');
        Schema::dropIfExists('repair_order_form3_materials');
        Schema::dropIfExists('repair_order_form3');
        Schema::dropIfExists('repair_order_form2_additional_materials');
        Schema::dropIfExists('repair_order_form2_materials');
        Schema::dropIfExists('repair_order_form2_employees');
        Schema::dropIfExists('repair_order_form2');
        Schema::dropIfExists('repair_order_form1');
        Schema::dropIfExists('repair_orders');
    }
};
