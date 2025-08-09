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
        Schema::create('employee_portal_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('access_token')->unique(); // Token único para acesso
            $table->string('email')->unique(); // Email para login
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Senha hash
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->integer('login_count')->default(0);
            $table->timestamps();

            $table->index(['company_id', 'employee_id', 'is_active'],'comp_employee_active_index');
            $table->index(['access_token', 'is_active'],'access_token_act_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_portal_accesses');
    }
};
