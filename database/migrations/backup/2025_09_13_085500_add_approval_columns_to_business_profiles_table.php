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
        Schema::table('business_profiles', function (Blueprint $table) {
            // Add approval related columns
            if (!Schema::hasColumn('business_profiles', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('business_profiles', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approved_at')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('business_profiles', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            
            if (Schema::hasColumn('business_profiles', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};
