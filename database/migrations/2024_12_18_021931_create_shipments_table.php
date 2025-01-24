<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->date('ship_load_date');              // Date when the shipment is loaded
            $table->string('ship_pickup_location');      // Pickup location
            $table->string('ship_delivery_location');   // Delivery location
            $table->string('ship_driver');               // Name or ID of the driver
            $table->decimal('ship_weight', 10, 2);       // Weight of the shipment
            $table->enum('ship_ftl_ltl', ['FTL', 'LTL']); // Type of freight: Full Truckload or Less Than Truckload
            $table->boolean('ship_tarp')->default(false); // Whether a tarp was used (boolean)
            $table->string('ship_equipment')->nullable(); // Equipment used for the shipment (optional)
            $table->decimal('ship_price', 10, 2);         // Price of the shipment
            $table->text('ship_notes')->nullable();      // Additional notes related to the shipment
            $table->timestamps(); // Created at / Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
