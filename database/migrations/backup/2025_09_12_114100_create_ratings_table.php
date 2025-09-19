<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();

                // Ensure a rating is either for a business or a product, but not both
                $table->unique(['user_id', 'business_id', 'product_id']);
            });

            // Add check constraint using raw SQL
            \DB::statement('ALTER TABLE ratings ADD CONSTRAINT chk_rating_target CHECK ((business_id IS NOT NULL AND product_id IS NULL) OR (business_id IS NULL AND product_id IS NOT NULL))');
        }
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
};
