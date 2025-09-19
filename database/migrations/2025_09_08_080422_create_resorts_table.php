<?php
// [file name]: 2025_09_08_070000_create_resorts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resorts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->text('short_info');
            $table->text('full_info')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('room_details')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resorts');
    }
};