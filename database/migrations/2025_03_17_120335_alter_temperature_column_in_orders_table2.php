<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTemperatureColumnInOrdersTable2 extends Migration
{
    public function up()
    {
        // Change temperature from NUMERIC to STRING
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE VARCHAR USING temperature::TEXT');
    }

    public function down()
    {
        // Revert temperature back to NUMERIC if needed
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE NUMERIC(10,2) USING temperature::NUMERIC(10,2)');
    }
}
