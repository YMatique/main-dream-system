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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->boolean('is_super_admin')->default(false)->after('email_verified_at');
            $table->foreignId('company_id')->nullable()->after('is_super_admin')->constrained('companies')->onDelete('cascade');
            
            // Índices para performance
            $table->index(['is_super_admin']);
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
               $table->dropForeign(['company_id']);
            $table->dropIndex(['is_super_admin']);
            $table->dropIndex(['company_id']);
            $table->dropColumn(['is_super_admin', 'company_id']);
        });
    }
};
