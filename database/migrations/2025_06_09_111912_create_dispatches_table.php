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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('carrier');
            $table->string('contact');
            $table->string('equipment');
            $table->string('driver_mobile');
            $table->string('truck_unit_no');
            $table->string('trailer_unit_no');
            $table->string('paps_pars_no');
            $table->string('tracking_code');
            $table->string('border');
            $table->string('currency');
            $table->decimal('rate', 10, 2);
            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->decimal('gst', 10, 2)->nullable();
            $table->decimal('pst', 10, 2)->nullable();
            $table->decimal('hst', 10, 2)->nullable();
            $table->decimal('qst', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
