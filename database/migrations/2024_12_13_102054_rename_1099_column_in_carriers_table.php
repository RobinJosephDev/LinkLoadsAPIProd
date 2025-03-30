<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Rename1099ColumnInCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite workaround: Create a new table with the desired column name
            Schema::create('carriers_temp', function ($table) {
                $table->id();
                $table->boolean('form_1099')->default(false);  // New column name
                $table->timestamps();
            });

            // Copy data from old table to the new table
            $carriers = DB::table('carriers')->get(); // Use get() instead of each()
            foreach ($carriers as $carrier) {
                DB::table('carriers_temp')->insert([
                    'form_1099' => $carrier->form_1099,  // Assuming `1099` is the old column name
                    'created_at' => $carrier->created_at,
                    'updated_at' => $carrier->updated_at,
                ]);
            }

            // Drop the old table
            Schema::dropIfExists('carriers');

            // Rename the new table to the original table name
            Schema::rename('carriers_temp', 'carriers');
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: Rename the column and alter the type
            DB::statement("ALTER TABLE carriers RENAME COLUMN \"1099\" TO form_1099");
            DB::statement("ALTER TABLE carriers ALTER COLUMN form_1099 TYPE BOOLEAN USING (form_1099::BOOLEAN)");
            DB::statement("ALTER TABLE carriers ALTER COLUMN form_1099 SET DEFAULT false");
        } else {
            // For MySQL or other databases, use the original approach
            DB::statement('ALTER TABLE carriers CHANGE COLUMN `1099` `form_1099` TINYINT(1) NOT NULL DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // Reverse the process for SQLite: Rename column back to original
            Schema::create('carriers_temp', function ($table) {
                $table->id();
                $table->integer('1099')->default(0);  // Old column name (integer type)
                $table->timestamps();
            });

            // Copy data from the old table (with `form_1099`) to the new table (with `1099`)
            $carriers = DB::table('carriers')->get(); // Use get() instead of each()
            foreach ($carriers as $carrier) {
                DB::table('carriers_temp')->insert([
                    '1099' => $carrier->form_1099,  // Assuming `form_1099` is the new column name
                    'created_at' => $carrier->created_at,
                    'updated_at' => $carrier->updated_at,
                ]);
            }

            // Drop the current table
            Schema::dropIfExists('carriers');

            // Rename the new table to the original table name
            Schema::rename('carriers_temp', 'carriers');
        } elseif ($driver === 'pgsql') {
            // Reverse for PostgreSQL: Rename the column and revert type
            DB::statement("ALTER TABLE carriers RENAME COLUMN form_1099 TO \"1099\"");
            DB::statement("ALTER TABLE carriers ALTER COLUMN \"1099\" TYPE SMALLINT USING (\"1099\"::SMALLINT)");
        } else {
            // For MySQL or other databases, revert the changes
            DB::statement('ALTER TABLE carriers CHANGE COLUMN `form_1099` `1099` TINYINT(1) NOT NULL DEFAULT 0');
        }
    }
}
