<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTemperatureColumnInOrdersTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE NUMERIC(10,2) USING temperature::NUMERIC(10,2)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE VARCHAR USING temperature::TEXT');
    }
}
