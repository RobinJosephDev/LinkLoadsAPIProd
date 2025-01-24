<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // Primary key for the contacts table
            // Foreign key for carrier
            $table->foreignId('carrier_id')->nullable()->constrained('carriers')->onDelete('cascade');
            // Foreign key for customer
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            // Foreign key for shipment (this can be added when you create the shipments table)
            // Email field
            $table->string('email');
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
