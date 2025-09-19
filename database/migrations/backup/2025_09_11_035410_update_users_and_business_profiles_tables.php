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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'business_type')) {
                    $table->string('business_type')->nullable()->after('role');
                }
                if (!Schema::hasColumn('users', 'is_archived')) {
                    $table->boolean('is_archived')->default(false)->after('role');
                }
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
        
        if (Schema::hasTable('business_profiles')) {
            Schema::table('business_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('business_profiles', 'business_permit_path')) {
                    $table->string('business_permit_path')->nullable()->after('facebook_page');
                }
                if (!Schema::hasColumn('business_profiles', 'business_license_path')) {
                    $table->string('business_license_path')->nullable()->after('business_permit_path');
                }
                if (!Schema::hasColumn('business_profiles', 'other_documents')) {
                    $table->json('other_documents')->nullable()->after('business_license_path');
                }
                if (!Schema::hasColumn('business_profiles', 'status')) {
                    $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('other_documents');
                }
                if (!Schema::hasColumn('business_profiles', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('status');
                }
                if (!Schema::hasColumn('business_profiles', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->constrained('users')->after('rejection_reason');
                }
                if (!Schema::hasColumn('business_profiles', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('business_profiles', 'is_published')) {
                    $table->boolean('is_published')->default(false)->after('approved_at');
                }
                if (!Schema::hasColumn('business_profiles', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'business_type')) $table->dropColumn('business_type');
                if (Schema::hasColumn('users', 'is_archived')) $table->dropColumn('is_archived');
                if (Schema::hasColumn('users', 'deleted_at')) $table->dropSoftDeletes();
            });
        }
        
        if (Schema::hasTable('business_profiles')) {
            Schema::table('business_profiles', function (Blueprint $table) {
                if (Schema::hasColumn('business_profiles', 'approved_by')) $table->dropForeign(['approved_by']);
                foreach (['business_permit_path','business_license_path','other_documents','status','rejection_reason','approved_by','approved_at','is_published'] as $col) {
                    if (Schema::hasColumn('business_profiles', $col)) $table->dropColumn($col);
                }
                if (Schema::hasColumn('business_profiles', 'deleted_at')) $table->dropSoftDeletes();
            });
        }
    }
};
