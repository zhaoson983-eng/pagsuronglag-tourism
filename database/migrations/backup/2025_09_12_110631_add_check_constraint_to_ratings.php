<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a check constraint to ensure a rating is either for a business or a product, but not both
        if (Schema::hasTable('ratings')) {
            DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating_type CHECK ((business_id IS NOT NULL AND product_id IS NULL) OR (business_id IS NULL AND product_id IS NOT NULL))');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the check constraint if it exists
        if (Schema::hasTable('ratings')) {
            DB::statement('ALTER TABLE ratings DROP CONSTRAINT IF EXISTS chk_rating_type');
        }
    }
};
