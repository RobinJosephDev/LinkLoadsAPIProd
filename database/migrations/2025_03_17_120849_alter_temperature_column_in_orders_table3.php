<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Change 'temperature' from NUMERIC to VARCHAR(255)
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE VARCHAR(255) USING temperature::TEXT');
    }

    public function down()
    {
        // Revert 'temperature' back to NUMERIC(10,2)
        DB::statement('ALTER TABLE orders ALTER COLUMN temperature TYPE NUMERIC(10,2) USING temperature::NUMERIC(10,2)');
    }
};
