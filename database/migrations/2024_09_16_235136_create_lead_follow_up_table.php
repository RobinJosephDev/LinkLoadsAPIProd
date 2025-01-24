<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_follow_up', function (Blueprint $table) {
            $table->id();
            $table->string('lead_status'); // Status of the lead
            $table->date('next_follow_up_date'); // Date for the next follow-up
            $table->text('remarks')->nullable(); // Optional remarks
            $table->string('equipment')->nullable(); // Equipment information
            $table->longText('products')->nullable(); // New field for product details
            $table->string('lead_no')->unique(); // Unique identifier for the lead
            $table->date('lead_date'); // Date when the lead was created
            $table->string('customer_name'); // Customer's name
            $table->string('phone'); // Customer's phone number
            $table->string('email')->nullable(); // Customer's email address
            $table->text('address'); // Customer's address
            $table->string('city'); // Customer's city
            $table->string('state'); // Customer's state
            $table->string('country'); // Customer's country
            $table->string('postal_code'); // Customer's postal code
            $table->string('unit_no')->nullable(); // Unit number
            $table->string('lead_type'); // Type of the lead
            $table->string('contact_person')->nullable(); // Contact person's name
            $table->text('notes')->nullable(); // Additional notes
            $table->text('contacts')->nullable(); // Additional contacts info
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_follow_up');
    }
};
