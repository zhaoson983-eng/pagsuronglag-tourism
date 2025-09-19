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
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'full_name')) {
                $table->string('full_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('profiles', 'birthday')) {
                $table->date('birthday')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('profiles', 'age')) {
                $table->integer('age')->nullable()->after('birthday');
            }
            if (!Schema::hasColumn('profiles', 'sex')) {
                $table->enum('sex', ['Male', 'Female', 'Other'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('profiles', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('sex');
            }
            if (!Schema::hasColumn('profiles', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('profiles', 'facebook')) {
                $table->string('facebook')->nullable()->after('profile_picture');
            }
            if (!Schema::hasColumn('profiles', 'instagram')) {
                $table->string('instagram')->nullable()->after('facebook');
            }
            if (!Schema::hasColumn('profiles', 'twitter')) {
                $table->string('twitter')->nullable()->after('instagram');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'birthday', 'age', 'sex', 'phone_number', 'profile_picture', 'facebook', 'instagram', 'twitter']);
        });
    }
};
