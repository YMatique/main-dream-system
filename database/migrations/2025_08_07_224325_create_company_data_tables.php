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
        // 1. Departamentos
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['company_id', 'name']);
        });

        // 2. Funcionários/Técnicos
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'is_active']);
        });

        // 3. Clientes
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'is_active']);
        });

        // 4. Tipos de Manutenção
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('hourly_rate_mzn', 8, 2)->default(0);
            $table->decimal('hourly_rate_usd', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['company_id', 'name']);
        });

        // 5. Materiais
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit'); // peça, metro, litro, etc.
            $table->decimal('cost_per_unit_mzn', 10, 2)->default(0);
            $table->decimal('cost_per_unit_usd', 10, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'is_active']);
        });

        // 6. Estados (por formulário)
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->enum('form_type', ['form1', 'form2', 'form3', 'form4', 'form5']);
            $table->string('color')->default('#6B7280');
            $table->boolean('is_final')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['company_id', 'name', 'form_type']);
        });

        // 7. Localizações (por formulário)
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->enum('form_type', ['form1', 'form2', 'form3', 'form4', 'form5']);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['company_id', 'name', 'form_type']);
        });

        // 8. Números de Máquina
        Schema::create('machine_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('number');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['company_id', 'number']);
        });

        // 9. Solicitantes
        Schema::create('requesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['company_id', 'is_active']);
        });

        // 10. Custos por Cliente
        Schema::create('client_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->onDelete('cascade');
            $table->decimal('cost_mzn', 10, 2)->default(0);
            $table->decimal('cost_usd', 10, 2)->default(0);
            $table->date('effective_date')->default(now());
            $table->timestamps();
            
            $table->unique(['company_id', 'client_id', 'maintenance_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('company_data_tables');
        Schema::dropIfExists('client_costs');
        Schema::dropIfExists('requesters');
        Schema::dropIfExists('machine_numbers');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('maintenance_types');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
};
