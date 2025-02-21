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
            $table->string('lead_status');
            $table->date('next_follow_up_date')->nullable();
            $table->text('remarks')->nullable();
            $table->string('equipment')->nullable();
            $table->longText('products')->nullable();
            $table->string('lead_no')->unique();
            $table->date('lead_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('unit_no')->nullable();
            $table->string('lead_type')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();
            $table->text('contacts')->nullable();
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
