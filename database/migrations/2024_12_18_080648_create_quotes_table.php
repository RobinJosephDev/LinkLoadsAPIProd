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
            $table->string('quote_type');
            $table->string('quote_customer')->nullable();
            $table->string('quote_cust_ref_no')->nullable();
            $table->string('quote_booked_by')->nullable();
            $table->float('quote_temperature')->nullable();
            $table->boolean('quote_hot')->default(false)->nullable();
            $table->boolean('quote_team')->default(false)->nullable();
            $table->boolean('quote_air_ride')->default(false)->nullable();
            $table->boolean('quote_tarp')->default(false)->nullable();
            $table->boolean('quote_hazmat')->default(false)->nullable();
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
