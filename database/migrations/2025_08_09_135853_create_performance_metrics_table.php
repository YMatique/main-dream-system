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
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('name'); // Nome da métrica
            $table->text('description')->nullable();
            $table->enum('type', ['numeric', 'rating', 'boolean']); // Tipo de avaliação
            $table->integer('weight')->default(10); // Peso da métrica (%)
            $table->decimal('min_value', 5, 2)->default(0);
            $table->decimal('max_value', 5, 2)->default(10);
            $table->json('rating_options')->nullable(); // ['Péssimo', 'Satisfatório', 'Bom', 'Excelente']
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['company_id', 'department_id', 'is_active']);
            $table->unique(['company_id', 'department_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_metrics');
    }
};
