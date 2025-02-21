<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeProductsColumnTypeInLeadFollowUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            // Change the column type to JSON with an explicit cast
            DB::statement('ALTER TABLE lead_follow_up ALTER COLUMN products TYPE JSON USING products::json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            // Revert back to TEXT if needed
            $table->text('products')->change();
        });
    }
}
