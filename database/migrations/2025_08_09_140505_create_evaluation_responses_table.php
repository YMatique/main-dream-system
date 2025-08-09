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
        Schema::create('evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('performance_evaluations')->onDelete('cascade');
            $table->foreignId('metric_id')->constrained('performance_metrics')->onDelete('cascade');
            $table->decimal('numeric_value', 5, 2)->nullable(); // Para métricas numéricas
            $table->string('rating_value')->nullable(); // Para avaliação rápida
            $table->decimal('calculated_score', 8, 2)->default(0); // Score calculado com peso
            $table->text('comments')->nullable(); // Comentários opcionais
            $table->timestamps();

            $table->index(['evaluation_id', 'metric_id']);
            $table->unique(['evaluation_id', 'metric_id']); // Uma resposta por métrica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_responses');
    }
};
