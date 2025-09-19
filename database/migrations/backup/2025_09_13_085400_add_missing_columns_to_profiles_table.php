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
            // Add all the missing columns that are being used in the application
            $columns = [
                'full_name' => 'string',
                'birthday' => 'date',
                'age' => 'integer',
                'sex' => 'string',
                'phone_number' => 'string',
                'address' => 'text',
                'bio' => 'text',
                'profile_picture' => 'string',
                'facebook' => 'string',
                'instagram' => 'string',
                'twitter' => 'string',
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('profiles', $column)) {
                    if ($type === 'string') {
                        $table->string($column)->nullable();
                    } elseif ($type === 'text') {
                        $table->text($column)->nullable();
                    } elseif ($type === 'integer') {
                        $table->integer($column)->nullable();
                    } elseif ($type === 'date') {
                        $table->date($column)->nullable();
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
        // We'll keep the columns in case they contain important data
        // If you need to rollback, create a new migration to drop these columns
    }
};
