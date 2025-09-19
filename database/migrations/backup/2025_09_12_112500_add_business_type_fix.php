<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('businesses', 'business_type')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('business_type')->default('local_products')->after('id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('businesses', 'business_type')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('business_type');
            });
        }
    }
};
