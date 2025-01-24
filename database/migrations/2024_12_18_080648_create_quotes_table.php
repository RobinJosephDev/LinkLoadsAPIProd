<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('quote_type'); // Dropdown (can be an enum or just a string)
            $table->string('quote_customer');
            $table->string('quote_cust_ref_no');
            $table->string('quote_booked_by');
            $table->float('quote_temperature')->nullable();
            $table->boolean('quote_hot')->default(false); // Checkbox
            $table->boolean('quote_team')->default(false); // Checkbox
            $table->boolean('quote_air_ride')->default(false); // Checkbox
            $table->boolean('quote_tarp')->default(false); // Checkbox
            $table->boolean('quote_hazmat')->default(false); // Checkbox
            $table->json('quote_pickup')->nullable(); // Array field (using JSON)
            $table->json('quote_delivery')->nullable(); // Array field (using JSON)
            $table->timestamps(); // Created at and updated at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
