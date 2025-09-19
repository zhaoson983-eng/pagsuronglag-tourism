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
        // Users table guards
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'business_type')) {
                Schema::table('users', function (Blueprint $table) {
                    // Use string for compatibility with later migrations
                    $table->string('business_type')->nullable()->after('role');
                });
            }
            if (!Schema::hasColumn('users', 'is_archived')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->boolean('is_archived')->default(false)->after('remember_token');
                });
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }

        // business_profiles table guards
        if (Schema::hasTable('business_profiles')) {
            if (!Schema::hasColumn('business_profiles', 'status')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
                });
            }
            if (!Schema::hasColumn('business_profiles', 'rejection_reason')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->text('rejection_reason')->nullable();
                });
            }
            if (!Schema::hasColumn('business_profiles', 'approved_at')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->timestamp('approved_at')->nullable();
                });
            }
            if (!Schema::hasColumn('business_profiles', 'approved_by')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->foreignId('approved_by')->nullable()->constrained('users');
                });
            }
            if (!Schema::hasColumn('business_profiles', 'is_published')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->boolean('is_published')->default(false);
                });
            }
            if (!Schema::hasColumn('business_profiles', 'business_permit_path')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->string('business_permit_path')->nullable();
                });
            }
            if (!Schema::hasColumn('business_profiles', 'business_license_path')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->string('business_license_path')->nullable();
                });
            }
            if (!Schema::hasColumn('business_profiles', 'other_documents')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->json('other_documents')->nullable();
                });
            }
            if (!Schema::hasColumn('business_profiles', 'deleted_at')) {
                Schema::table('business_profiles', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
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
                foreach (['status','rejection_reason','approved_at','approved_by','is_published','business_permit_path','business_license_path','other_documents'] as $col) {
                    if (Schema::hasColumn('business_profiles', $col)) $table->dropColumn($col);
                }
                if (Schema::hasColumn('business_profiles', 'deleted_at')) $table->dropSoftDeletes();
            });
        }
    }
};
