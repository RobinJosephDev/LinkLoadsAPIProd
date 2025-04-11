<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeShipWeightAndPriceToStringInShipmentsTable extends Migration
{
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Change column types to string
            $table->string('ship_weight')->change();
            $table->string('ship_price')->change();
        });
    }

    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Revert column types back to decimal
            $table->decimal('ship_weight', 10, 2)->change();
            $table->decimal('ship_price', 10, 2)->change();
        });
    }
}
