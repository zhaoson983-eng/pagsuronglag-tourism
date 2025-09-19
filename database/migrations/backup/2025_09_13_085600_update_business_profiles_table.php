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
            // Add approved_at if it doesn't exist
            if (!Schema::hasColumn('business_profiles', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            
            // Add approved_by if it doesn't exist
            if (!Schema::hasColumn('business_profiles', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approved_at')
                    ->constrained('users')->nullOnDelete();
            }
            
            // Add is_published if it doesn't exist
            if (!Schema::hasColumn('business_profiles', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('status');
            }
            
            // Add published_at if it doesn't exist
            if (!Schema::hasColumn('business_profiles', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We'll keep the columns in case they contain important data
        // If you need to rollback, create a new migration to drop these columns
    }
};
