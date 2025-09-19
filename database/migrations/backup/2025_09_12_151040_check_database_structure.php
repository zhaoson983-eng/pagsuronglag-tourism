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
        $tables = DB::select('SHOW TABLES');
        echo "\n\n=== Database Tables ===\n";
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "Table: " . $tableName . "\n";
            
            // Get column information
            $columns = DB::select("DESCRIBE `$tableName`");
            foreach ($columns as $column) {
                echo "  - " . $column->Field . " (" . $column->Type . ")";
                if ($column->Key === 'PRI') echo " [PRIMARY KEY]";
                if ($column->Null === 'NO') echo " [NOT NULL]";
                if ($column->Default !== null) echo " [DEFAULT: " . $column->Default . "]";
                echo "\n";
            }
            echo "\n";
        }
        echo "=== End of Database Tables ===\n\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
