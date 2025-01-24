<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('broker_name');
            $table->string('broker_address')->nullable();
            $table->string('broker_city')->nullable();
            $table->string('broker_state')->nullable();
            $table->string('broker_country')->nullable();
            $table->string('broker_postal')->nullable();
            $table->string('broker_email')->nullable();
            $table->string('broker_phone')->nullable();
            $table->string('broker_ext')->nullable();
            $table->string('broker_fax')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brokers');
    }
};
