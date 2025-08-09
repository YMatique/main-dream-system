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
        Schema::create('permission_caches', function (Blueprint $table) {
              $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('permissions'); // Cache das permissões do usuário
            $table->json('department_permissions')->nullable(); // Cache das permissões por departamento
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            // Unique constraint
            $table->unique('user_id');
            
            // Indexes
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_caches');
    }
};
