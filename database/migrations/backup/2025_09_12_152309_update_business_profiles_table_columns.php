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
        // Add any missing columns to the business_profiles table
        Schema::table('business_profiles', function (Blueprint $table) {
            // List of required columns and their definitions
            $columns = [
                'business_name' => 'string',
                'description' => 'text',
                'contact_number' => 'string',
                'address' => 'text',
                'website' => 'string',
                'business_permit_path' => 'string',
                'status' => 'string',
                'logo' => 'string',
                'cover_photo' => 'string',
                'business_hours' => 'text',
                'is_approved' => 'boolean',
                'rejection_reason' => 'text',
                'setup_completed' => 'boolean',
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('business_profiles', $column)) {
                    $columnMethod = $type === 'boolean' ? 'boolean' : $type;
                    $columnDefinition = $table->$columnMethod($column);
                    
                    // Set default values for certain columns
                    if ($column === 'is_approved') {
                        $columnDefinition->default(false);
                    } elseif ($column === 'setup_completed') {
                        $columnDefinition->default(false);
                    } elseif ($column === 'status') {
                        $columnDefinition->default('pending');
                    } else {
                        $columnDefinition->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a safe migration that doesn't drop any columns by default
        // If you need to drop columns, create a separate migration
    }
};
