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
        // Ensure existing data is converted to valid JSON before altering column type
        DB::statement('ALTER TABLE customers ALTER COLUMN cust_contact TYPE JSON USING cust_contact::json');
        DB::statement('ALTER TABLE customers ALTER COLUMN cust_equipment TYPE JSON USING cust_equipment::json');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to text type
        DB::statement('ALTER TABLE customers ALTER COLUMN cust_contact TYPE TEXT USING cust_contact::text');
        DB::statement('ALTER TABLE customers ALTER COLUMN cust_equipment TYPE TEXT USING cust_equipment::text');
    }
};
