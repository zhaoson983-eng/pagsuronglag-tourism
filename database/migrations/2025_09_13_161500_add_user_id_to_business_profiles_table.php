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
            if (!Schema::hasColumn('business_profiles', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            
            // Add other essential columns if missing
            if (!Schema::hasColumn('business_profiles', 'business_name')) {
                $table->string('business_name')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'contact_number')) {
                $table->string('contact_number')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'business_permit_path')) {
                $table->string('business_permit_path')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            if (!Schema::hasColumn('business_profiles', 'business_type')) {
                $table->enum('business_type', ['local_products', 'hotel', 'resort'])->default('local_products');
            }
            if (!Schema::hasColumn('business_profiles', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
            if (!Schema::hasColumn('business_profiles', 'profile_picture')) {
                $table->string('profile_picture')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('business_profiles', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'business_name', 'description', 'contact_number', 
                'email', 'address', 'website', 'business_permit_path', 
                'status', 'business_type', 'is_published', 'profile_picture', 'cover_image',
                'rejection_reason', 'approved_at', 'approved_by'
            ]);
        });
    }
};
