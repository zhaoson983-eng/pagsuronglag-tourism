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
        Schema::table('hotels', function (Blueprint $table) {
            // Add soft deletes
            $table->softDeletes();
            
            // Add nullable to some fields if needed
            $table->text('full_info')->nullable()->change();
            $table->text('gallery_images')->nullable()->change();
            
            // Add new columns if needed
            // $table->string('contact_number')->nullable();
            // $table->string('email')->nullable();
            // $table->string('website')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Remove soft deletes
            $table->dropSoftDeletes();
            
            // Remove nullable if needed
            $table->text('full_info')->nullable(false)->change();
            $table->text('gallery_images')->nullable(false)->change();
            
            // Remove added columns
            // $table->dropColumn(['contact_number', 'email', 'website']);
        });
    }
};