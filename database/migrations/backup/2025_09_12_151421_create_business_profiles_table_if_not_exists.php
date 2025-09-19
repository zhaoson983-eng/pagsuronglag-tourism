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
        if (!Schema::hasTable('business_profiles')) {
            Schema::create('business_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('business_name');
                $table->string('business_type');
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->string('logo')->nullable();
                $table->string('cover_photo')->nullable();
                $table->string('business_hours')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->text('rejection_reason')->nullable();
                $table->boolean('setup_completed')->default(false);
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // Add any missing columns if the table already exists
            Schema::table('business_profiles', function (Blueprint $table) {
                $columns = [
                    'business_name' => 'string',
                    'business_type' => 'string',
                    'description' => 'text',
                    'address' => 'string',
                    'city' => 'string',
                    'state' => 'string',
                    'zip_code' => 'string',
                    'phone' => 'string',
                    'website' => 'string',
                    'logo' => 'string',
                    'cover_photo' => 'string',
                    'business_hours' => 'string',
                    'is_approved' => 'boolean',
                    'rejection_reason' => 'text',
                    'setup_completed' => 'boolean',
                ];

                foreach ($columns as $column => $type) {
                    if (!Schema::hasColumn('business_profiles', $column)) {
                        $columnMethod = $type === 'boolean' ? 'boolean' : $type;
                        $table->{$columnMethod}($column)->nullable();
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in the down method to prevent data loss
        // If you need to drop the table, create a separate migration
    }
};
