<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('hotels', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('location');
        $table->text('short_info');
        $table->text('full_info')->nullable();
        $table->string('cover_photo');
        $table->text('gallery_images')->nullable();   // Changed from json()
        $table->text('room_details');                 // Changed from json()
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};