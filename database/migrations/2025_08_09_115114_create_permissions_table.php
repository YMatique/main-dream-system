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
        Schema::create('permissions', function (Blueprint $table) {
             $table->id();
            $table->string('name')->unique(); // Ex: 'forms.form1.access'
            $table->string('description');
            $table->string('category')->index(); // Ex: 'forms', 'billing', 'evaluation'
            $table->string('group')->nullable(); // Para agrupar permissões relacionadas
            $table->boolean('is_system')->default(false); // Permissões que não podem ser removidas
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
