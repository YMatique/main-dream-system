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
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->id();
              $table->string('name'); // Ex: 'Usuário Ordens', 'Usuário Faturação', 'Usuário Avaliador'
            $table->string('description');
            $table->string('color')->default('#6B7280'); // Cor para identificação visual
            $table->boolean('is_system')->default(false); // Grupos padrão do sistema
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_groups');
    }
};
