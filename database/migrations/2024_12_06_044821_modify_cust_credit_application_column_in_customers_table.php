<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Step 1: Update the column with integer values for the case where it's 0 or 1
            DB::statement("UPDATE customers SET cust_credit_application =
                CASE
                    WHEN cust_credit_application = 0 THEN 0  -- false as 0
                    WHEN cust_credit_application = 1 THEN 1  -- true as 1
                    ELSE 0  -- default to 0 if any other value exists
                END");

            // Step 2: Alter the column type to BOOLEAN
            DB::statement("ALTER TABLE customers ALTER COLUMN cust_credit_application TYPE BOOLEAN USING
                CASE
                    WHEN cust_credit_application = 0 THEN false
                    WHEN cust_credit_application = 1 THEN true
                    ELSE false
                END");
        } else {
            // For MySQL or SQLite, apply standard logic
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('cust_credit_application')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Revert the column back to smallint
            DB::statement("ALTER TABLE customers ALTER COLUMN cust_credit_application TYPE SMALLINT USING
                CASE
                    WHEN cust_credit_application = false THEN 0
                    WHEN cust_credit_application = true THEN 1
                    ELSE 0
                END");
        } else {
            Schema::table('customers', function (Blueprint $table) {
                $table->tinyInteger('cust_credit_application')->nullable()->change();
            });
        }
    }
};
