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
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('full_name')->nullable();
                $table->date('birthday')->nullable();
                $table->integer('age')->nullable();
                $table->string('sex', 10)->nullable();
                $table->string('phone_number', 20)->nullable();
                $table->string('address')->nullable();
                $table->text('bio')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // If table exists, just add missing columns
            Schema::table('profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('profiles', 'profile_picture')) {
                    $table->string('profile_picture')->nullable()->after('user_id');
                }
                // Add other missing columns if needed
                $columns = [
                    'full_name', 'birthday', 'age', 'sex', 'phone_number', 
                    'address', 'bio', 'facebook', 'instagram', 'twitter'
                ];
                
                foreach ($columns as $column) {
                    if (!Schema::hasColumn('profiles', $column)) {
                        $method = match($column) {
                            'birthday' => 'date',
                            'age' => 'integer',
                            'bio' => 'text',
                            default => 'string',
                        };
                        $table->{$method}($column)->nullable();
                    }
                }
                
                // Add soft deletes if not exists
                if (!Schema::hasColumn('profiles', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the table if it's empty to prevent data loss
        $count = DB::table('profiles')->count();
        if ($count === 0) {
            Schema::dropIfExists('profiles');
        }
        // Otherwise, just remove the columns we might have added
        else {
            Schema::table('profiles', function (Blueprint $table) {
                $columnsToDrop = [
                    'profile_picture', 'full_name', 'birthday', 'age', 
                    'sex', 'phone_number', 'address', 'bio', 
                    'facebook', 'instagram', 'twitter'
                ];
                
                foreach ($columnsToDrop as $column) {
                    if (Schema::hasColumn('profiles', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                if (Schema::hasColumn('profiles', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
?>
